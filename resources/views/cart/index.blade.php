@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div id="cart-content">
        @include('cart.partials.content', ['cart' => $cart, 'summary' => $summary])
    </div>
</div>
@endsection
