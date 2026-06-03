<form action="{{ $action }}" method="POST">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $coupon?->code) }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="fixed" @selected(old('type', $coupon?->type) === 'fixed')>Fixed</option>
                <option value="percentage" @selected(old('type', $coupon?->type) === 'percentage')>Percentage</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Value</label>
            <input type="number" step="0.01" min="0.01" name="value" class="form-control" value="{{ old('value', $coupon?->value) }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Minimum Order Amount</label>
            <input type="number" step="0.01" min="0" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $coupon?->min_order_amount ?? 0) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Usage Limit</label>
            <input type="number" min="1" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon?->usage_limit) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select">
                <option value="1" @selected((string) old('is_active', $coupon?->is_active ?? '1') === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', $coupon?->is_active) === '0')>Inactive</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Starts At</label>
            <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d\\TH:i')) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Expires At</label>
            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d\\TH:i')) }}">
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button class="btn btn-dark">{{ $coupon ? 'Update Coupon' : 'Create Coupon' }}</button>
        <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
