<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Navbar */
    .navbar-custom {
      background-color: #f8c8dc;
    }

    /* Modal */
    .modal-header {
      background-color: #f8c8dc;
    }
    .modal-content {
      border-radius: 20px;
      border: none;
    }
    .modal-body {
      padding: 2rem;
    }

    /* Card */
    .card-title {
      font-size: 1rem;
      font-weight: 600;
    }
    .card-text {
      font-size: 0.9rem;
    }
    .card-summary {
      background-color: #f8c8dc;
      border: none;
      border-radius: 16px;
      color: #000;
    }
    .card-summary .card-body {
      padding: 1.5rem 1rem;
    }
    .card-summary h6 {
      font-size: 0.85rem;
      color: #6c757d;
    }
    .card-summary h4 {
      font-size: 1.5rem;
      font-weight: 700;
    }

    /* List group */
    .list-group-item {
      padding: 0.75rem 1rem;
      background-color: #fffdfd;
    }

    /* Badge stock */
    .badge-stock {
      background-color: #f8c8dc;
      color: #000;
      padding: 4px 10px;
      border-radius: 12px;
      font-weight: 500;
      min-width: 50px;
      text-align: center;
      display: inline-block;
    }
  </style>
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold text-dark" href="#">
        <img src="https://omah-rynata-homemade2.odoo.com/web/image/res.company/1/logo" alt="Logo" height="40" class="rounded-circle">
        Dashboard Omah Rynata Homemade
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/sales">Sales</a></li>
          <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/purchase">Purchase</a></li>
          <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/inventory">Inventory</a></li>
          <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="/manufacturing">Manufacturing</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <!-- Summary Cards -->
    <div class="row row-cols-1 row-cols-md-4 g-3 mb-4">
      <div class="col">
        <div class="card card-summary text-center shadow-sm">
          <div class="card-body">
            <h5>Total Produk</h5>
            <h4>{{ count($products) }}</h4>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card card-summary text-center shadow-sm">
          <div class="card-body">
            <h5>Total Stok Produk</h5>
            <h4>{{ number_format($total_stock, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card card-summary text-center shadow-sm">
          <div class="card-body">
            <h5>Total Stok Bahan Baku</h5>
            <h4>{{ number_format($total_bahan_baku, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card card-summary text-center shadow-sm">
          <div class="card-body">
            <h5>Current Income</h5>
            <h4>Rp{{ number_format($current_income, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Produk List -->
    <div class="container py-6">
      <h3 class="mb-4">Daftar Produk</h3>

      @if(count($products) === 0)
        <div class="alert alert-warning">Tidak ada produk atau gagal login ke Odoo.</div>
      @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
          @foreach($products as $product)
            <div class="col">
              <div class="card h-100 shadow-sm bg-white rounded"
                   data-bs-toggle="modal"
                   data-bs-target="#productModal"
                   data-name="{{ $product['name'] }}"
                   data-price="{{ number_format($product['list_price'], 0, ',', '.') }}"
                   data-image="{{ $product['image_1024'] ?? '' }}"
                   data-uom="{{ $product['uom_id'][1] ?? '-' }}"
                   data-type="{{ $product['type'] ?? 'product' }}"
                   data-stock="{{ $product['qty_available'] ?? 0 }}">
                
                @if(!empty($product['image_1024']))
                  <img src="data:image/png;base64,{{ $product['image_1024'] }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                @else
                  <div class="card-img-top bg-secondary text-white d-flex justify-content-center align-items-center" style="height:180px;">
                    Tidak ada gambar
                  </div>
                @endif

                <div class="card-body">
                  <h6 class="card-title text-dark">{{ $product['name'] }}</h6>
                  <p class="card-text text-muted">Rp{{ number_format($product['list_price'], 0, ',', '.') }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  <!-- Modal Detail Produk -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 800px;">
      <div class="modal-content rounded-4">
        <div class="modal-header py-3" style="background-color: #f8c8dc;">
          <h5 class="modal-title fw-bold text-dark" id="productModalLabel">Detail Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body px-4 py-4">
          <div class="row align-items-center">
            <div class="col-md-5 text-center mb-3 mb-md-0">
              <img id="modalImage" src="" class="img-fluid rounded shadow" style="max-height: 250px; object-fit: cover;">
            </div>
            <div class="col-md-7">
              <h4 id="modalName" class="fw-bold mb-3 text-dark"></h4>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Harga:</strong> Rp <span id="modalPrice"></span></li>
                <li class="list-group-item"><strong>Satuan:</strong> <span id="modalUom"></span></li>
                <li class="list-group-item"><strong>Tipe Produk:</strong> <span id="modalType"></span></li>
                <li class="list-group-item">
                  <span><strong>Stok Tersedia:</strong></span>
                  <span id="modalStock" class="badge-stock ms-2"></span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS Bootstrap + Modal Logic -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const productModal = document.getElementById('productModal');
    productModal.addEventListener('show.bs.modal', function (event) {
      const card = event.relatedTarget;
      document.getElementById('modalName').textContent = card.getAttribute('data-name');
      document.getElementById('modalPrice').textContent = card.getAttribute('data-price');
      document.getElementById('modalUom').textContent = card.getAttribute('data-uom');
      document.getElementById('modalType').textContent = card.getAttribute('data-type');
      document.getElementById('modalStock').textContent = card.getAttribute('data-stock');

      const image = card.getAttribute('data-image');
      document.getElementById('modalImage').src = image
        ? "data:image/png;base64," + image
        : "https://via.placeholder.com/300x200?text=No+Image";
    });
  </script>

</body>
</html>
