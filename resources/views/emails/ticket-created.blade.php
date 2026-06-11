<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Support Ticket</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
    <div
        style="max-width: 600px; margin: auto; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">

        <div style="background: #0d6efd; color: white; padding: 15px;">
            <h2 style="margin: 0;">New Support Ticket</h2>
        </div>

        <div style="padding: 20px;">
            <p>Hello,</p>

            <p>A new support ticket has been created.</p>

            <div style="background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 15px; margin-bottom: 20px;">
                <p style="margin: 0 0 10px;">
                    <strong>Ticket:</strong><br>
                    #TKT-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                </p>

                <p style="margin: 0 0 10px;">
                    <strong>Subject:</strong><br>
                    {{ $ticket->subject }}
                </p>

                <p style="margin: 0 0 10px;">
                    <strong>Priority:</strong><br>
                    {{ ucfirst($ticket->priority) }}
                </p>

                <p style="margin: 0;">
                    <strong>Message:</strong><br>
                    {{ $ticket->message }}
                </p>
            </div>

            <a href="{{ route('tickets.show', $ticket) }}"
                style="display: inline-block; padding: 10px 20px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px;">
                View Ticket
            </a>
        </div>

        <div style="background: #f8f9fa; padding: 15px; text-align: center; color: #6c757d; font-size: 14px;">
            &copy; {{ date('Y') }} Ecommerce. All rights reserved.
        </div>
    </div>
</body>

</html>
