@extends('layouts.user')

@section('title', 'Shopping Bag')

@section('content')
<div class="container section-pad">
    <div id="cart-content">
        @include('cart.partials.content', ['cart' => $cart, 'summary' => $summary])
    </div>
</div>
@endsection
