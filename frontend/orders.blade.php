<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .navbar-custom { background-color: #f8c8dc; }
    .btn-pink { background-color: #f8c8dc; border: none; }
    .btn-pink:hover { background-color: #f5b2cd; }
  </style>
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">

    <div class="container">
      <a class="navbar-brand fw-bold text-dark" href="#">
        <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">
        Sales
      </a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/sales/orders">Orders</a></li>
          <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/sales/customers">Customers</a></li>
          <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/sales/report">Reports</a></li>
        </ul>
        <div class="ms-3">
          <a href="/" class="btn btn-outline-dark btn-sm">Dashboard</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="container py-4">
<div class="d-flex justify-content-between align-items-center mb-3">
  {{-- Kiri: Icon Back + Judul --}}
  <div class="d-flex align-items-center">
    <a href="/sales" class="me-2 fs-4 fw-bold text-dark" title="Kembali ke Sales">
      <i class="bi bi-chevron-left"></i>
    </a>
    <h3 class="fw-bold mb-0">Orders</h3>
  </div>
</div>

    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

<div class="table-responsive">
  <table class="table table-bordered align-middle bg-white shadow-sm">
    <thead class="table-light">
      <tr>
        <th>Nomor Order</th>
        <th>Tanggal</th>
        <th>Nama</th>
        <th>Total</th>
        <th>Status Invoice</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
        <tr>
          <td>{{ $order['name'] }}</td>
          <td>{{ \Carbon\Carbon::parse($order['date_order'])->format('d M Y') }}</td>
          <td>{{ is_array($order['partner_id']) ? $order['partner_id'][1] : '-' }}</td>
          <td>Rp{{ number_format($order['amount_total'], 0, ',', '.') }}</td>
          <td>
            @if($order['invoice_status'] === 'invoiced')
              <span class="badge bg-success">Invoiced</span>
            @elseif($order['invoice_status'] === 'to invoice')
              <span class="badge bg-warning">To Invoice</span>
            @elseif($order['invoice_status'] === 'no')
              <span class="badge bg-secondary">Nothing to Invoice</span>
            @else
              <span class="badge bg-secondary">{{ $order['invoice_status'] }}</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted">Tidak ada order ditemukan.</td>
        </tr>
      @endforelse

      {{-- Total Keseluruhan --}}
      @if(count($orders) > 0)
        <tr class="fw bg-light">
          <th colspan="3">Total Keseluruhan</th>
          <td colspan="2">Rp{{ number_format($totalAmount, 0, ',', '.') }}</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
