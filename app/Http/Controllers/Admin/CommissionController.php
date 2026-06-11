<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;


class CommissionController extends Controller
{
    public function index()
    {
        $commissions = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'completed');
        })
        ->latest()
        ->paginate(10);

        $monthlyCommission = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'completed');
        })
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('platform_commission_amount');

        return view(
            'admin.commissions.index',
            compact('commissions','monthlyCommission')
        );
    }
    public function sellercommission()
    {
        $sellerId = auth()->id();


        $items = OrderItem::with(['order', 'product'])
            ->where('seller_id', $sellerId)
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->latest()
            ->paginate(10);


        $totalSales = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('line_total');

        $totalPlatformFee = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('platform_commission_amount');

        $totalEarnings = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('seller_earning');

        $totalOrders = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->count();

        return view('admin.commissions.sellerindex', compact(
            'items',
            'totalSales',
            'totalPlatformFee',
            'totalEarnings',
            'totalOrders'
        ));

    }
}
