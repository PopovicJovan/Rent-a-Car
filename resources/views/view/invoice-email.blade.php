<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Invoice</title>
</head>
<body>
<p>Hello {{ ($invoiceData['user'])->name }},</p>
<p>Thank you for your purchase! Attached is your invoice.</p>
<p>Total: {{ $invoiceData['price'] }} EUR</p>
<p>Best regards,<br>Your Company</p>
</body>
</html>
