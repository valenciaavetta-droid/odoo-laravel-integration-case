<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Inventory</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .card-category {
      background-color: #ffe0ef;
      border: none;
      border-radius: 16px;
      cursor: pointer;
      transition: transform 0.2s;
    }
    .card-category:hover {
      transform: scale(1.02);
    }
    .modal-header {
      background-color: #f8c8dc;
    }
        .navbar-custom { background-color: #f8c8dc; }
    .badge-status {
      font-size: 0.85rem;
      padding: 4px 10px;
      border-radius: 10px;
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
  <h3 class="fw-bold mb-4">Stok Inventory</h3>

  <div class="row row-cols-1 row-cols-md-2 g-4">
    <!-- Card Bahan Baku -->
    <div class="col">
      <div class="card card-category shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRaw">
        <div class="card-body">
          <h5 class="card-title fw-bold text-dark">Bahan Baku</h5>
          <p class="card-text text-muted">Lihat daftar stok bahan baku</p>
        </div>
      </div>
    </div>

    <!-- Card Produk Jadi -->
    <div class="col">
      <div class="card card-category shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFinished">
        <div class="card-body">
          <h5 class="card-title fw-bold text-dark">Produk Jadi</h5>
          <p class="card-text text-muted">Lihat daftar stok produk siap jual</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Bahan Baku -->
<div class="modal fade" id="modalRaw" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title">Stok Bahan Baku</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if(count($stocks['raw']) > 0)
          <table class="table table-bordered">
            <thead><tr><th>Nama</th><th>Jumlah</th><th>Satuan</th></tr></thead>
            <tbody>
              @foreach($stocks['raw'] as $item)
                <tr>
                  <td>{{ $item['name'] }}</td>
                  <td>{{ $item['qty'] }}</td>
                  <td>{{ $item['uom'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <p class="text-muted">Tidak ada data bahan baku.</p>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal Produk Jadi -->
<div class="modal fade" id="modalFinished" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title">Stok Produk Jadi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if(count($stocks['finished']) > 0)
          <table class="table table-bordered">
            <thead><tr><th>Nama</th><th>Jumlah</th><th>Satuan</th></tr></thead>
            <tbody>
              @foreach($stocks['finished'] as $item)
                <tr>
                  <td>{{ $item['name'] }}</td>
                  <td>{{ $item['qty'] }}</td>
                  <td>{{ $item['uom'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <p class="text-muted">Tidak ada data produk jadi.</p>
        @endif
      </div>
    </div>
  </div>
</div>

</body>
</html>
