@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">Create Coupon</div>
            <div class="card-body">
                @include('coupons.partials.form', [
                    'action' => route('coupons.store'),
                    'method' => 'POST',
                    'coupon' => null,
                ])
            </div>
        </div>
    </div>
</div>
@endsection
