<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pergerakan Stok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .navbar-custom { background-color: #f8c8dc; }
    .badge-status { font-size: 0.85rem; padding: 4px 10px; border-radius: 10px; }
    .badge-done { background-color: #d4edda; color: #155724; }
    .badge-waiting { background-color: #fef3c7; color: #856404; }
      .btn-pink {
    background-color: #f8c8dc;
    border: none;
  }
  .btn-pink:hover {
    background-color: #f5b2cd;
  }
  #clearSearch {
    background-color: white;
    border-color: #ced4da;
  }
  #clearSearch:hover {
    background-color: #f8d7da;
  }
  #clearSearch {
    background-color: white;
    border-color: #ced4da;
  }
  </style>
</head>

<body class="bg-light">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-dark" href="/">
      <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">
      Inventory
    </a>
        <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/inventory/moves">Moves History</a></li>
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
      <a href="/inventory" class="me-2 fs-4 fw-bold text-dark" title="Kembali ke Inventory">
        <i class="bi bi-chevron-left"></i>
      </a>
      <h3 class="fw-bold mb-0">Moves History</h3>
    </div>
  </div>

<form action="{{ route('inventory-moves') }}" method="GET" class="mb-4">
  <div class="input-group">
    <input type="text" id="searchInput" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">

    <!-- Tombol Clear -->
    <button type="button" class="btn btn-outline-secondary" id="clearSearch" style="border-left: none;">
      <i class="bi bi-x-lg"></i>
    </button>

    <!-- Tombol Search -->
    <button class="btn btn-pink" type="submit">
      <i class="bi bi-search text-white"></i>
    </button>
  </div>
</form>


  @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
  @elseif(count($moves) === 0)
    <div class="alert alert-warning">Tidak ada data pergerakan stok.</div>
  @else
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
  <tr>
    <th>Tanggal</th>
    <th>Referensi</th>
    <th>Produk</th>
    <th>Dari</th>
    <th>Tujuan</th>
    <th>Qty</th>
    <th>Unit</th>
    <th>Status</th>
  </tr>
</thead>
<tbody>
  @foreach($moves as $move)
    <tr>
      <td>{{ \Carbon\Carbon::parse($move['date'])->format('d-m-Y H:i') }}</td>
      <td>{{ $move['reference'] ?? '-' }}</td>
      <td>{{ $move['product_id'][1] ?? '-' }}</td>
      <td>{{ $move['location_id'][1] ?? '-' }}</td>
      <td>{{ $move['location_dest_id'][1] ?? '-' }}</td>
      <td>{{ $move['product_uom_qty'] ?? 0 }}</td>
      <td>{{ $move['product_uom'][1] ?? '-' }}</td>
      <td>
        @php
          $state = $move['state'];
          $badge = match($state) {
            'done' => 'badge-done',
            default => 'badge-waiting'
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
<script>
  document.getElementById('clearSearch').addEventListener('click', function () {
    document.getElementById('searchInput').value = '';
  });
</script>

</body>
</html>

