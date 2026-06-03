<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Coupon::class);

        $coupons = Coupon::latest()->paginate(15);

        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        $this->authorize('create', Coupon::class);

        return view('coupons.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Coupon::class);

        $validated = $this->validateCoupon($request);

        Coupon::create([
            ...$validated,
            'created_by' => Auth::id(),
            'code' => strtoupper($validated['code']),
        ]);

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $this->authorize('update', $coupon);

        return view('coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $this->authorize('update', $coupon);

        $validated = $this->validateCoupon($request, $coupon);

        $coupon->update([
            ...$validated,
            'code' => strtoupper($validated['code']),
        ]);

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorize('delete', $coupon);

        $coupon->delete();

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    private function validateCoupon(Request $request, ?Coupon $coupon = null): array
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($coupon?->id),
            ],
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'required|boolean',
        ]);

        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            throw ValidationException::withMessages([
                'value' => 'Percentage coupons cannot be greater than 100.',
            ]);
        }

        return $validated;
    }
}
