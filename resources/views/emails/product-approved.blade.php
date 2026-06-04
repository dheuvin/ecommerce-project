<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Product Approved</title>

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #198754;
            color: white;
            text-align: center;
            padding: 25px;
        }

        .content {
            padding: 30px;
        }

        .product-box {
            background: #f8f9fa;
            border-left: 4px solid #198754;
            padding: 15px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            padding: 20px;
            border-top: 1px solid #dee2e6;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #198754;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <h2>🎉 Product Approved</h2>
        </div>

        <div class="content">

            <h3>Hello Seller,</h3>

            <p>
                We are pleased to inform you that your product has been reviewed and approved by our admin team.
            </p>

            <div class="product-box">
                <strong>Product Name:</strong><br>
                {{ $product->name }}
            </div>

            <p>
                Your product is now live and available for customers to view and purchase.
            </p>

            <center>
                <a href="{{ url('/products/' . $product->id) }}" class="btn">
                    View Product
                </a>
            </center>

        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}<br>
            Thank you for selling with us.
        </div>

    </div>

</body>

</html>
