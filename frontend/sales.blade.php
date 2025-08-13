<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sales Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar-custom {
      background-color: #f8c8dc;
    }
      .btn-pink {
    background-color: #f8c8dc;
    border: none;
  }
  .btn-pink:hover {
    background-color: #f5b2cd;
  }
  </style>
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
      <div class="container">

    <a class="navbar-brand fw-bold text-dark" href="#">  <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">   Sales</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ms-auto"> <!-- Menu sekarang pindah ke kanan -->
        <li class="nav-item">
    <a class="nav-link text-dark fw-semibold" href="/sales/orders">Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold" href="/sales/customers">Customers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold" href="/sales/report">Reports</a>
        </li>
      </ul>
      <div class="ms-3">
        <a href="/" class="btn btn-outline-dark btn-sm">Dashboard</a>
      </div>
    </div>
  </div>
</nav>

 <div class="container py-4">

<div class="d-flex justify-content-between align-items-center mb-3">
<h3 class="mb-4">Daftar Quotation</h3>
</div>

  @if(count($orders) === 0)
    <div class="alert alert-warning">Tidak ada data quotation.</div>
  @else
    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Nomor Quotation</th>
          <th>Customer</th>
          <th>Tanggal</th>
          <th>Total</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $i => $order)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $order['name'] }}</td>
          <td>{{ $order['partner_id'][1] ?? '-' }}</td>
          <td>{{ \Carbon\Carbon::parse($order['date_order'])->format('d M Y') }}</td>
          <td>Rp{{ number_format($order['amount_total'], 0, ',', '.') }}</td>
          <td>
  @if($order['state'] === 'draft')
    <span class="badge bg-warning text-dark">Draft</span>
  @elseif($order['state'] === 'sent')
    <span class="badge text-white" style="background-color:rgb(249, 167, 201);">Quotation Sent</span>
  @elseif($order['state'] === 'sale')
    <span class="badge bg-success">Sales Order</span>
  @elseif($order['state'] === 'cancel')
    <span class="badge bg-danger">Cancelled</span>
  @else
    <span class="badge bg-secondary">{{ $order['state'] }}</span>
  @endif

</td>
        </tr>
        @endforeach
      </tbody>
            {{-- Total Keseluruhan --}}
      @if(count($orders) > 0)
        <tr class="fw bg-light">
          <th colspan="4">Total Keseluruhan</th>
          <td colspan="">Rp{{ number_format($totalAmount, 0, ',', '.') }}</td>
        </tr>
      @endif
    </table>

  @endif
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
