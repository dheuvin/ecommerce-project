<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Product Rejected</title>

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
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background: #dc3545;
            color: white;
            text-align: center;
            padding: 25px;
        }

        .content {
            padding: 30px;
        }

        .reason-box {
            background: #fff3f3;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin-top: 15px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <h2>❌ Product Rejected</h2>
        </div>

        <div class="content">

            <h3>Hello Seller,</h3>

            <p>Your submitted product has been rejected by the admin.</p>

            <p>
                <strong>Product Name:</strong>
                {{ $product->name }}
            </p>

            <div class="reason-box">
                <strong>Reason:</strong><br>
                {{ $product->admin_note }}
            </div>

            <p>Please review the feedback, update your product, and submit it again.</p>

        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}
        </div>

    </div>

</body>

</html>
