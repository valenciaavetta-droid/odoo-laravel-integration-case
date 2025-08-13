<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Produk yang Perlu Dibeli</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .navbar-custom { background-color: #f8c8dc; }
    .card-custom {
      background-color: #ffe0ef;
      border: none;
    }
    .card-img-top {
      height: 180px;
      object-fit: cover;
    }
    .card-title {
      font-size: 1rem;
      font-weight: 600;
    }
    .card-text {
      font-size: 0.9rem;
    }
    .stok-badge {
      display: inline-block;
      background-color: #f8c8dc;
      color: #6c0c38;
      font-size: 0.85rem;
      font-weight: 500;
      padding: 3px 10px;
      border-radius: 15px;
      margin-left: 6px;
    }
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
  </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-dark" href="#">
      <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">
      Purchase
    </a>
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

<!-- Content -->
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
      <a href="/purchase" class="me-2 fs-4 fw-bold text-dark" title="Kembali ke Purchase">
        <i class="bi bi-chevron-left"></i>
      </a>
      <h3 class="fw-bold mb-0">Produk yang Perlu Dibeli</h3>
    </div>
  </div>

  <!-- Search Bar -->
<form action="{{ route('to_buy') }}" method="GET" class="mb-4">
  <div class="input-group">
    <input type="text" id="searchInput" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">

    <!-- Tombol Clear (X) -->
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
  @elseif(count($products) === 0)
    <div class="alert alert-warning">Tidak ada produk yang perlu dibeli.</div>
  @else
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
      @foreach($products as $product)
        <div class="col">
          <div class="card h-100 shadow-sm card-custom">
            @if(!empty($product['image_1024']))
              <img src="data:image/png;base64,{{ $product['image_1024'] }}" class="card-img-top" alt="{{ $product['name'] }}">
            @else
              <div class="card-img-top bg-secondary text-white d-flex justify-content-center align-items-center">
                Tidak ada gambar
              </div>
            @endif
            <div class="card-body text-dark">
              <h6 class="card-title">{{ $product['name'] }}</h6>
              <p class="card-text mb-1">
                Stok Tersedia:
                <span class="stok-badge">{{ $product['qty_available'] }}</span>
              </p>
              <p class="card-text mb-1">Harga Beli: Rp{{ number_format($product['standard_price'], 0, ',', '.') }}</p>
              <p class="text-muted small mb-0">Satuan: {{ $product['uom_id'][1] ?? '-' }}</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
<script>
  document.getElementById('clearSearch').addEventListener('click', function () {
    const input = document.getElementById('searchInput');
    input.value = '';
    input.form.submit(); // setelah dikosongkan langsung submit ulang form
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
