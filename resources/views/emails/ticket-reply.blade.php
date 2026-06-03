<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Support Ticket Reply</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">

        <div style="background: #0d6efd; color: white; padding: 15px;">
            <h2 style="margin: 0;">Support Ticket Reply</h2>
        </div>

        <div style="padding: 20px;">

            <p>Hello,</p>

            <p>Your support ticket has received a new reply.</p>

            <div style="background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 15px; margin-bottom: 20px;">
                <p style="margin: 0;">
                    <strong>Ticket Subject:</strong><br>
                    {{ $ticket->subject }}
                </p>
            </div>

            <h4 style="margin-bottom: 10px;">Latest Reply</h4>

            <div style="background: #eef5ff; padding: 15px; border-radius: 5px; border: 1px solid #cfe2ff;">
                {{ $reply->message }}
            </div>

            <p style="margin-top: 20px;">
                If you have any further questions, please log in to your account and continue the conversation.
            </p>

            <a href="{{ url('/tickets/' . $ticket->id) }}"
               style="display: inline-block; padding: 10px 20px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px;">
                View Ticket
            </a>

        </div>

        <div style="background: #f8f9fa; padding: 15px; text-align: center; color: #6c757d; font-size: 14px;">
            © {{ date('Y') }} Your Company. All rights reserved.
        </div>

    </div>

</body>
</html>
