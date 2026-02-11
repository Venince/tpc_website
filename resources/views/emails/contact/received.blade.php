<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2 style="margin:0 0 10px;">New Message from TPC Website</h2>

    <p style="margin:0 0 8px;">
        <strong>From:</strong> {{ $msg->name }} ({{ $msg->email }})
    </p>

    <p style="margin:0 0 8px;">
        <strong>Subject:</strong> {{ $msg->subject }}
    </p>

    <hr style="margin:16px 0;">

    <p style="white-space: pre-wrap; margin:0;">
        {{ $msg->message }}
    </p>

    <hr style="margin:16px 0;">

    <p style="font-size:12px; color:#666; margin:0;">
        Sent from TPC Website • {{ $msg->created_at?->format('M d, Y h:i A') }}
        @if($msg->ip) • IP: {{ $msg->ip }} @endif
    </p>
</body>
</html>
