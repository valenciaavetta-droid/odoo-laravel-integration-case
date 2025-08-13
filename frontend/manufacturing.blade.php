<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manufacturing Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar-custom { background-color: #f8c8dc; }
    .badge-status {
      font-size: 0.85rem;
      padding: 4px 10px;
      border-radius: 10px;
    }
    .badge-done { background-color: #c8f7c5; color: #155724; }
    .badge-progress { background-color: #fef3c7; color: #b45309; }
    .badge-cancel { background-color: #fcd5ce; color: #9f1239; }
  </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-dark" href="/">
      <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">
      Manufacturing
    </a>
        <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/manufacturing/bom">Bills of Materials</a></li>
      </ul>
      <div class="ms-3">
        <a href="/" class="btn btn-outline-dark btn-sm">Dashboard</a>
      </div>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h3 class="fw-bold mb-4">Daftar Manufacturing Orders</h3>

  @if(isset($error) && $error)
    <div class="alert alert-danger">{{ $error }}</div>
  @elseif(count($orders) === 0)
    <div class="alert alert-warning">Tidak ada data Manufacturing Order.</div>
  @else
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Reference</th>
            <th>Start</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $order)
            <tr>
              <td>{{ $order['name'] }}</td>
                <td>{{ \Carbon\Carbon::parse($order['date_start'])->diffForHumans() }}</td>
              <td>{{ $order['product_id'][1] ?? '-' }}</td>
              <td>{{ $order['product_qty'] }}</td>
              <td>{{ $order['product_uom_id'][1] ?? '-' }}</td>
              <td>
                @php
                  $state = $order['state'];
                  $badge = match($state) {
                    'done' => 'badge-done',
                    'progress' => 'badge-progress',
                    'cancel' => 'badge-cancel',
                    default => 'bg-secondary text-white'
                  };
                @endphp
                <span class="badge badge-status {{ $badge }}">{{ ucfirst($state) }}</span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
