<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $order->order_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            padding: 24px;
            background: #f3f4f6;
            font-family: Arial, Helvetica, sans-serif;
        }

        .toolbar {
            max-width: 900px;
            margin: 0 auto 24px auto;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar a,
        .toolbar button {
            border: 0;
            background: #111827;
            color: #fff;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .toolbar a.secondary {
            background: #e5e7eb;
            color: #111827;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .toolbar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('orders.show', $order) }}" class="secondary">Back to Order</a>

        <div class="toolbar-actions">
            <a href="{{ route('orders.invoice.download', $order) }}" class="secondary">Download HTML</a>
            <button type="button" onclick="window.print()">Print / Save PDF</button>
        </div>
    </div>

    @include('invoices.document', ['order' => $order])
</body>
</html>
