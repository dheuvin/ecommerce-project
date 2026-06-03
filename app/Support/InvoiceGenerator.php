<?php

namespace App\Support;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceGenerator
{
    public function ensureGenerated(Order $order): Order
    {
        if (! $order->invoice_path || ! Storage::disk('local')->exists($order->invoice_path)) {
            $this->generate($order);
        }

        return $order->fresh(['items.product.images', 'items.seller', 'user', 'coupon']);
    }

    public function generate(Order $order): string
    {
        $order->loadMissing(['items.product.images', 'items.seller', 'user', 'coupon']);

        $path = $this->pathFor($order);
        $html = view('invoices.export', compact('order'))->render();

        Storage::disk('local')->put($path, $html);

        $order->forceFill([
            'invoice_path' => $path,
            'invoice_generated_at' => now(),
        ])->save();

        return $path;
    }

    public function render(Order $order): string
    {
        $order->loadMissing(['items.product.images', 'items.seller', 'user', 'coupon']);

        return view('invoices.document', compact('order'))->render();
    }

    public function storedContents(Order $order): string
    {
        $order = $this->ensureGenerated($order);

        return Storage::disk('local')->get($order->invoice_path);
    }

    public function downloadName(Order $order): string
    {
        return 'invoice-'.Str::slug($order->order_number).'.html';
    }

    private function pathFor(Order $order): string
    {
        return 'invoices/'.Str::slug($order->order_number).'.html';
    }
}
