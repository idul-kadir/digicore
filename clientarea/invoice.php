<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- Link Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }

        /* Using grid layout for better flexibility */
        .header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 5px;
            grid-column: 1 / span 2;
            text-align: center;
        }

        .company-info {
            font-size: 1rem;
            color: #555;
            line-height: 1.5;
        }

        .company-info p {
            margin: 0;
        }

        .table-container {
            margin-bottom: 30px;
        }

        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-top: 1px solid #ddd;
        }

        .table th {
            background-color: #f7f7f7;
        }

        .table td:last-child {
            text-align: right;
        }

        .summary {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            font-size: 0.875rem;
            color: #555;
            margin-top: 50px;
            padding-bottom: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Section with Grid Layout -->
    <div class="header">
        <div>
            <h1>Invoice</h1>
        </div>
        <div class="text-end">
            <h3>Company Name</h3>
            <p class="company-info">
                Address Line 1, Address Line 2<br>
                Email: contact@company.com<br>
                Phone: +1234567890
            </p>
        </div>
    </div>

    <!-- Customer Information -->
    <div>
        <h5>Bill To:</h5>
        <p class="company-info">
            Customer Name<br>
            Customer Address<br>
            Email: customer@example.com<br>
            Phone: +0987654321
        </p>
    </div>

    <!-- Table of Items -->
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product 1</td>
                    <td>2</td>
                    <td>$50.00</td>
                    <td>$100.00</td>
                </tr>
                <tr>
                    <td>Product 2</td>
                    <td>1</td>
                    <td>$75.00</td>
                    <td>$75.00</td>
                </tr>
                <tr>
                    <td>Product 3</td>
                    <td>3</td>
                    <td>$20.00</td>
                    <td>$60.00</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end">Subtotal</td>
                    <td>$235.00</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end">Tax (10%)</td>
                    <td>$23.50</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end summary">Total</td>
                    <td>$258.50</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>If you have any questions, feel free to contact us at contact@company.com</p>
    </div>
</div>

<!-- Link Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
