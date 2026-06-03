<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $order->order_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin: 0; padding: 24px; background: #f3f4f6;">
    @include('invoices.document', ['order' => $order])
</body>
</html>
