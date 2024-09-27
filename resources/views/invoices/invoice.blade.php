<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-header {
            background-color: #00a2ff;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .company-info p {
            margin: 0;
        }
        .table-invoice {
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .table-invoice th, .table-invoice td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .footer {
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Invoice Header -->
    <div class="invoice-header text-center">
        <h1>INVOICE</h1>
        <div class="company-info">
            <p>Podgorica 81000</p>
            <p>(000) 000-000 | project@project.com</p>
        </div>
    </div>

    <!-- Invoice and Customer Details -->
    <div class="row invoice-details">
        <div class="col-md-6">
            <h5>Invoice Number: <strong>{{ ($invoiceData["reservation"])->id  }}</strong></h5>
            <h5>Invoice Date: <strong>{{ \Carbon\Carbon::now() }}</strong></h5>
        </div>
        <div class="col-md-6 text-end">
            <h5>Name: <strong>{{ ($invoiceData["user"])->name }}</strong></h5>
            <h5>Email: <strong>{{ ($invoiceData["user"])->email  }}</strong></h5>
        </div>
    </div>

    <!-- Table with Rental Details -->
    <table class="table table-invoice">
        <thead>
        <tr>
            <th>Car</th>
            <th>Rental Period</th>
            <th>Rate Per Day</th>
            <th>Number of hours</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ ($invoiceData["car"])->brand }}</td>
            <td>{{ ($invoiceData["reservation"])->start_date }} - {{ ($invoiceData["reservation"])->end_date }}</td>
            <td>${{ number_format(($invoiceData["car"])->price , 2) }}</td>
            <td>{{  $invoiceData["hours"] }}</td>
            <td>${{ number_format($invoiceData["price"], 2) }}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3"></td>
            <td><strong>Total</strong></td>
            <td><strong>${{ number_format($invoiceData["price"], 2)  }}</strong></td>
        </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>
