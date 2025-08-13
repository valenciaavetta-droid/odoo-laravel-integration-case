<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bill of Materials</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .navbar-custom { background-color: #f8c8dc; }
    .card-custom {
      background-color: #ffe0ef;
      border: none;
      border-radius: 16px;
    }
    .btn-detail {
      background-color: #ff91af;
      color: white;
    }
    .btn-detail:hover {
      background-color: #e67a98;
    }
    .modal-header { background-color: #f8c8dc; }
    .bg-pink { background-color: #f8c8dc !important; }
  </style>
</head>
<body class="bg-light">

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
  <div class="d-flex align-items-center mb-4">
    <a href="/manufacturing" class="me-2 fs-4 fw-bold text-dark text-decoration-none" title="Kembali ke Manufacturing">
      <i class="bi bi-chevron-left"></i>
    </a>
    <h3 class="fw-bold mb-0">Bill of Materials</h3>
  </div>

  @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
  @elseif(count($boms) === 0)
    <div class="alert alert-warning">Tidak ada data BoM.</div>
  @else
    <div class="row row-cols-1 row-cols-md-2 g-4">
      @foreach($boms as $index => $bom)
        <div class="col">
          <div class="card card-custom shadow-sm h-100">
            <div class="card-body text-dark">
              <h5 class="card-title fw-bold">{{ $bom['product_tmpl_id'][1] ?? 'Produk' }}</h5>
              <p class="card-text">Total Komponen: {{ count($bom['lines']) }}</p>
              <button class="btn btn-detail btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $index }}">
                Lihat Detail
              </button>
            </div>
          </div>
        </div>

        <!-- Modal Detail -->
        <div class="modal fade" id="modalDetail{{ $index }}" tabindex="-1" aria-labelledby="modalLabel{{ $index }}" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <div>
                  <h5 class="modal-title fw-bold" id="modalLabel{{ $index }}">{{ $bom['product_tmpl_id'][1] ?? 'Produk' }}</h5>
                  <span class="badge rounded-pill bg-pink text-dark mt-1" style="font-size: 0.85rem;">
                    {{ $bom['product_qty'] ?? '-' }} {{ $bom['product_uom_id'][1] ?? '' }}
                  </span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                @if(count($bom['lines']) > 0)
                  <ul class="list-group list-group-flush">
                    @foreach($bom['lines'] as $comp)
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $comp['name'] }}
                        <span class="badge rounded-pill bg-light text-dark" style="border: 1px solid #f8c8dc;">
                          {{ $comp['qty'] }} {{ $comp['uom'] }}
                        </span>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <p class="text-muted">Belum ada data komponen.</p>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

</body>
</html>
