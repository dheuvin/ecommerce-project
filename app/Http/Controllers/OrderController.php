<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\InvoiceGenerator;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::query()
            ->with('user')
            ->latest();

        if (Auth::user()->isCustomer()) {
            $orders->where('user_id', Auth::id());
        } elseif (Auth::user()->isSeller()) {
            $orders->whereHas('items', function ($query) {
                $query->where('seller_id', Auth::id());
            });
        }

        $orders = $orders->paginate(12);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product.images', 'items.seller', 'user', 'coupon']);

        return view('orders.show', compact('order'));
    }

    public function invoice(Order $order, InvoiceGenerator $invoiceGenerator)
    {
        $this->authorize('view', $order);

        $order = $invoiceGenerator->ensureGenerated($order);

        return view('invoices.show', compact('order'));
    }

    public function downloadInvoice(Order $order, InvoiceGenerator $invoiceGenerator)
    {
        $this->authorize('view', $order);

        return response($invoiceGenerator->storedContents($order), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$invoiceGenerator->downloadName($order).'"',
        ]);
    }
}
