<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Purchase Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .navbar-custom { background-color: #f8c8dc; }
    .btn-pink {
      background-color: #f8c8dc;
      border: none;
      color: black;
    }
    .btn-pink:hover { background-color: #f5b2cd; }

    .status-label {
      padding: 2px 10px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: bold;
    }
    .status-draft { background-color: #fff0f4; color: #d63384; }
    .status-purchase { background-color: #e0f7fa; color: #007b8a; }
    .status-done { background-color:rgb(135, 255, 163); color: #155724; }
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-dark" href="#">
    <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">
    Purchase</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/purchase/vendors">Vendors</a></li>
        <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/purchase/products-to-buy">Product To Buy</a></li>
      </ul>
      <div class="ms-3">
        <a href="/" class="btn btn-outline-dark btn-sm">Dashboard</a>
      </div>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Purchase Orders</h3>
    <a href="#" class="btn btn-pink text-dark fw-bold">
      Buat Order Baru
    </a>
  </div>

  @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
  @elseif(count($orders) === 0)
    <div class="alert alert-warning">Tidak ada data purchase order.</div>
  @else
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Nomor Order</th>
            <th>Tanggal Konfirmasi</th>
            <th>Vendor</th>
            <th>Total</th>
            <th>Billing Status</th>
            <th>Tanggal Estimasi</th>
          </tr>
        </thead>
<tbody>
  @foreach($orders as $order)
@php
  $billingStatus = $order['invoice_status'] ?? 'unknown';
  $statusMap = [
    'invoiced' => ['label' => 'Fully Billed', 'color' => 'success'],
    'to invoice' => ['label' => 'To Bill', 'color' => 'warning'],
    'no' => ['label' => 'Not Billed', 'color' => 'secondary'],
  ];

  $statusLabel = $statusMap[$billingStatus]['label'] ?? ucfirst($billingStatus);
  $statusColor = $statusMap[$billingStatus]['color'] ?? 'dark';
@endphp


    <tr>
      <td>{{ $order['name'] }}</td>
      <td>{{ \Carbon\Carbon::parse($order['date_approve'])->format('d M Y') ?? '-' }}</td>
      <td>{{ $order['partner_id'][1] ?? '-' }}</td>
      <td>Rp{{ number_format($order['amount_total'], 0, ',', '.') }}</td>
      <td>
        <span class="badge bg-{{ $statusColor }}">
          {{ $statusLabel }}
        </span>
      </td>
      <td>{{ \Carbon\Carbon::parse($order['date_order'])->format('d M Y') ?? '-' }}</td>
    </tr>
  @endforeach
</tbody>
@if(count($orders) > 0)
  <tr class="bg-light">
    <th colspan="3">Total Keseluruhan</h>
    <td colspan="2">Rp{{ number_format($totalAmount, 0, ',', '.') }}</td>
  </tr>
@endif

      </table>
    </div>
  @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
