<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Mengimpor jsPDF dan html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    
    <style>
        /* Style yang sudah ada, tetap sama */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }

        .container {
            width: 100%;
            max-width: 700px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1rem;
            margin: 0;
            color: #888;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h5 {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info-section p {
            font-size: 0.9rem;
            margin: 5px 0;
        }

        /* Posisi gambar di kanan atas */
        .stamp {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .table {
            width: 100%;
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        .table th, .table td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .status {
            text-align: center;
            font-size: 1.25rem;
            font-weight: bold;
            color: green;
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            font-size: 0.8rem;
            color: #777;
            margin-top: 30px;
        }

        .btn-pdf {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-pdf:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container" id="invoice">
    <!-- Header Section -->
    <div class="header">
        <h1>Bukti Pembayaran</h1>
        <p><strong>Nomor Bukti:</strong> BP-2025-0010</p>
        <p><strong>Tanggal:</strong> 2025-02-09</p>
    </div>

    <!-- Gambar Stempel di kanan atas -->
    <div class="stamp">
        <!-- Menetapkan ukuran gambar langsung -->
        <img src="assets/img/approve.png" alt="Stamp Approved" width="120" height="auto">
    </div>

    <!-- Company Information -->
    <div class="info-section">
        <h5>Perusahaan:</h5>
        <p>Nama Perusahaan</p>
        <p>Alamat: Jl. Contoh No. 123, Jakarta</p>
        <p>Email: perusahaan@example.com</p>
        <p>Telepon: +1234567890</p>
    </div>

    <!-- Customer Information -->
    <div class="info-section">
        <h5>Pelanggan:</h5>
        <p>Nama Pelanggan</p>
        <p>Alamat: Jl. Pelanggan No. 5, Bandung</p>
        <p>Email: pelanggan@example.com</p>
        <p>Telepon: +0987654321</p>
    </div>

    <div style="max-width: 700px; margin: 0 auto; padding: 30px; font-family: Arial, sans-serif; border: 1px solid #ddd; background-color: #fff; border-radius: 8px;">

        <!-- Tabel Produk -->
        <table style="width: 100%; border-spacing: 0; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f7f7f7;">
                    <th style="padding: 10px; text-align: left; font-weight: bold; border-bottom: 1px solid #ddd;">Produk</th>
                    <th style="padding: 10px; text-align: center; font-weight: bold; border-bottom: 1px solid #ddd;">Jumlah</th>
                    <th style="padding: 10px; text-align: right; font-weight: bold; border-bottom: 1px solid #ddd;">Harga</th>
                    <th style="padding: 10px; text-align: right; font-weight: bold; border-bottom: 1px solid #ddd;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">Produk A</td>
                    <td style="padding: 8px; text-align: center; border-bottom: 1px solid #ddd;">1</td>
                    <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">$100.00</td>
                    <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">$100.00</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">Produk B</td>
                    <td style="padding: 8px; text-align: center; border-bottom: 1px solid #ddd;">1</td>
                    <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">$80.00</td>
                    <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">$80.00</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">Produk C</td>
                    <td style="padding: 8px; text-align: center; border-bottom: 1px solid #ddd;">1</td>
                    <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">$120.00</td>
                    <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">$120.00</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">Produk D</td>
                    <td style="padding: 8px; text-align: center; border-bottom: 1px solid #ddd;">1</td>
                    <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">$100.00</td>
                    <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">$100.00</td>
                </tr>
            </tbody>
        </table>

        <!-- Total Pembayaran -->
        <div style="margin-top: 20px; display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem;">
            <span>Total Pembayaran</span>
            <span>$400.00</span>
        </div>

    </div>

    <!-- Payment Status -->
    <div class="status">
        Pembayaran Telah Lunas
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih atas pembayaran Anda!</p>
        <p>Hubungi kami di contact@perusahaan.com jika ada pertanyaan.</p>
    </div>

</div>

</body>
</html>
