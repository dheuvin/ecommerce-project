<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ticket Status Updated</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f9; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9; padding:30px 0;">
        <tr>
            <td align="center">

                <!-- Main Card -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#0d6efd; color:#ffffff; padding:20px; text-align:center;">
                            <h2 style="margin:0; font-size:20px;">Ticket Status Updated</h2>
                            <p style="margin:5px 0 0; font-size:13px; opacity:0.9;">
                                Your support request has been updated
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:25px;">

                            <p style="margin:0 0 10px;">Hello 👋</p>

                            <p style="margin:0 0 20px; color:#555;">
                                Your ticket status has been updated successfully. Below are the details:
                            </p>

                            <!-- Info Box -->
                            <div style="border-left:4px solid #0d6efd; background:#f8f9ff; padding:15px; border-radius:6px;">

                                <p style="margin:0 0 10px;">
                                    <strong>Ticket ID:</strong><br>
                                    #{{ $ticket->id }}
                                </p>

                                <p style="margin:0 0 10px;">
                                    <strong>Subject:</strong><br>
                                    {{ $ticket->subject }}
                                </p>

                                <p style="margin:0;">
                                    <strong>New Status:</strong><br>

                                    @php
                                        $statusColor = match($ticket->status) {
                                            'open' => '#0d6efd',
                                            'pending' => '#ffc107',
                                            'closed' => '#6c757d',
                                            'resolved' => '#198754',
                                            default => '#212529'
                                        };
                                    @endphp

                                    <span style="
                                        display:inline-block;
                                        padding:6px 12px;
                                        margin-top:5px;
                                        border-radius:20px;
                                        background:{{ $statusColor }};
                                        color:#ffffff;
                                        font-size:13px;
                                    ">
                                        {{ ucfirst($ticket->status) }}
                                    </span>

                                </p>

                            </div>

                            <!-- Button -->
                            <div style="text-align:center; margin-top:25px;">
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    style="background:#0d6efd; color:#ffffff; text-decoration:none; padding:10px 20px; border-radius:5px; display:inline-block;">
                                    View Ticket
                                </a>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="text-align:center; padding:15px; font-size:12px; color:#888; background:#f1f3f6;">
                            © {{ date('Y') }} Ecommerce Support System. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
