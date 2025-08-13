<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OdooController extends Controller
{
    private $url;
    private $db;
    private $username;
    private $password;

    public function __construct()
    {
        $this->url = env('ODOO_URL');
        $this->db = env('ODOO_DB');
        $this->username = env('ODOO_USER');
        $this->password = env('ODOO_PASS');
    }

public function dashboard()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // 1. Login ke Odoo
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;
    if (!$uid) {
        return view('dashboard', ['products' => [], 'error' => 'Gagal login ke Odoo.']);
    }

    // 2. Ambil produk yang dijual (sale_ok = true)
    $productResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db, $uid, $password,
                'product.product', 'search_read',
                [[['sale_ok', '=', true]]],
                ['fields' => ['id', 'name', 'list_price', 'image_1024', 'qty_available', 'uom_id']]
            ]
        ],
        'id' => null
    ]);
    $products = $productResponse['result'] ?? [];

    // 3. Hitung total stok dari semua produk
    $totalStock = array_sum(array_column($products, 'qty_available'));

    // 4. Hitung total Manufacturing Order
// Ambil semua ID produk bahan baku dari BoM Lines
$bomLinesResponse = Http::post("$url/jsonrpc", [
    'jsonrpc' => "2.0",
    'method' => "call",
    'params' => [
        'service' => "object",
        'method' => "execute_kw",
        'args' => [
            $db, $uid, $password,
            'mrp.bom.line', 'search_read',
            [[]],
            ['fields' => ['product_id']]
        ]
    ],
    'id' => null
]);

$bahanIds = collect($bomLinesResponse['result'] ?? [])->pluck('product_id')->pluck(0)->unique()->values()->all();

$bahanResponse = [];

if (!empty($bahanIds)) {
    $bahanResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db, $uid, $password,
                'product.product', 'read',
                [$bahanIds],
                ['fields' => ['qty_available']]
            ]
        ],
        'id' => null
    ])['result'] ?? [];
}

$total_bahan_baku = array_sum(array_column($bahanResponse, 'qty_available'));


    // 5. Hitung Manufacturing Order yang selesai
    $incomeResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db, $uid, $password,
                'sale.order.line', 'search_read',
                [[['state', '=', 'sale']]],
                ['fields' => ['price_total']]
            ]
        ],
        'id' => null
    ]);

    $lines = $incomeResponse['result'] ?? [];
    $currentIncome = array_sum(array_column($lines, 'price_total'));

    return view('dashboard', [
        'products' => $products,
        'total_stock' => $totalStock,
        'total_bahan_baku' => $total_bahan_baku,
        'current_income' => $currentIncome,
    ]);
}


public function sales()
{
    $loginResponse = Http::post("{$this->url}/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$this->db, $this->username, $this->password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;

    if (!$uid) {
        return view('sales', ['orders' => [], 'error' => 'Gagal login ke Odoo.']);
    }

    $salesResponse = Http::post("{$this->url}/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $this->db,
                $uid,
                $this->password,
                'sale.order',
                'search_read',
                [],
                [
                    'fields' => ['name', 'partner_id', 'date_order', 'amount_total', 'state'],
                    'limit' => 20
                ]
            ]
        ],
        'id' => null
    ]);

    $orders = $salesResponse['result'] ?? [];

    // Hitung total quotation
    $totalAmount = array_sum(array_column($orders, 'amount_total'));

    return view('sales', compact('orders', 'totalAmount'));
}


public function orderList()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // 1. Login ke Odoo
    $login = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null,
    ]);

    $uid = $login['result'] ?? null;

    if (!$uid) {
        return view('orders', ['orders' => [], 'totalAmount' => 0, 'error' => 'Gagal login ke Odoo']);
    }

    // 2. Ambil daftar quotations dari Odoo
    $ordersResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db, $uid, $password,
                'sale.order',
                'search_read',
                [
                    [], // semua orders
                ],
                [
                    'fields' => ['name', 'date_order', 'partner_id', 'amount_total', 'invoice_status'],
                    'limit' => 50
                ]
            ],
        ],
        'id' => null,
    ]);

    $orders = $ordersResponse['result'] ?? [];

    // Hitung total keseluruhan
    $totalAmount = 0;
    foreach ($orders as $order) {
        $totalAmount += $order['amount_total'];
    }

    return view('orders', compact('orders', 'totalAmount'));
}


public function customers()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // Login ke Odoo
    $login = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null,
    ]);

    $uid = $login['result'] ?? null;

    if (!$uid) {
        return view('customers.index', ['customers' => [], 'error' => 'Gagal login ke Odoo']);
    }

    // Ambil data customer (tipe = contact / company)
    $response = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db,
                $uid,
                $password,
                'res.partner',
                'search_read',
                [[['customer_rank', '>', 0]]],
                [
                    'fields' => ['name', 'email', 'phone', 'city', 'country_id'],
                    'limit' => 50,
                ],
            ],
        ],
        'id' => null,
    ]);

    $customers = $response['result'] ?? [];

return view('customers', compact('customers'));


}

public function salesReport()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // Login ke Odoo
    $login = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null,
    ]);

    $uid = $login['result'] ?? null;

    if (!$uid) {
        return view('report', ['reportData' => [], 'error' => 'Login ke Odoo gagal']);
    }

    // Ambil data penjualan dari sale.report
    $saleResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db,
                $uid,
                $password,
                'sale.report',
                'search_read',
                [[]], // semua data
                [
                    'fields' => ['product_id', 'product_uom_qty', 'price_total'],
                    'limit' => 1000
                ]
            ]
        ],
        'id' => null
    ]);

    $rawData = $saleResponse['result'] ?? [];

    // Hitung total penjualan per produk
    $report = [];

    foreach ($rawData as $row) {
        $productName = $row['product_id'][1] ?? null;

        if (!$productName || str_contains(strtolower($productName), 'delivery')) {
            continue;
        }

        if (!isset($report[$productName])) {
            $report[$productName] = [
                'qty' => 0,
                'total' => 0,
            ];
        }

        $report[$productName]['qty'] += $row['product_uom_qty'];
        $report[$productName]['total'] += $row['price_total'];
    }

    return view('report', ['reportData' => $report]);
}

public function purchaseOrders()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // Login
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;
    if (!$uid) {
        return view('purchase.orders', ['orders' => [], 'error' => 'Gagal login ke Odoo']);
    }

    // Ambil purchase order
    $response = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db,
                $uid,
                $password,
                'purchase.order',
                'search_read',
                [[]],
                [
                    'fields' => ['name', 'date_approve', 'partner_id', 'amount_total', 'invoice_status', 'date_order'],
                    'limit' => 50
                ]
            ]
        ],
        'id' => null
    ]);

    $orders = $response['result'] ?? [];

    // Hitung total amount
    $totalAmount = array_sum(array_column($orders, 'amount_total'));

    return view('purchase', compact('orders', 'totalAmount'));
}

public function vendors()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // Login ke Odoo
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;

    if (!$uid) {
        return view('purchase.vendor', ['vendors' => [], 'error' => 'Gagal login ke Odoo']);
    }

    // Ambil data vendor
    $response = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db,
                $uid,
                $password,
                'res.partner', // model
                'search_read', // method
                [[['supplier_rank', '>', 0]]], // domain filter
                ['fields' => ['name', 'email']],
            ],
        ],
        'id' => null,
    ]);

    $vendors = $response['result'] ?? [];

    return view('vendors', ['vendors' => $vendors]);
}

public function productsToBuy()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    $search = request('search');

    // Login
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;
    if (!$uid) {
        return view('to_buy', ['products' => [], 'error' => 'Gagal login ke Odoo']);
    }

    try {
        // Domain awal hanya produk pembelian
        $domain = [
            ['type', 'in', ['product', 'consu']],
            ['purchase_ok', '=', true] // ubah dari sale_ok jadi purchase_ok
        ];

        // Tambahkan pencarian jika ada
        if (!empty($search)) {
            $domain[] = ['name', 'ilike', $search];
        }

        $response = Http::post("$url/jsonrpc", [
            'jsonrpc' => "2.0",
            'method' => "call",
            'params' => [
                'service' => "object",
                'method' => "execute_kw",
                'args' => [
                    $db,
                    $uid,
                    $password,
                    'product.product',
                    'search_read',
                    [$domain],
                    [
                        'fields' => ['name', 'qty_available', 'standard_price', 'uom_id', 'image_1024'],
                        'limit' => 100
                    ]
                ],
            ],
            'id' => null
        ]);

        $products = $response['result'] ?? [];

        return view('to_buy', [
            'products' => $products,
            'search' => $search
        ]);

    } catch (\Exception $e) {
        return view('to_buy', ['products' => [], 'error' => 'Gagal mengambil data produk']);
    }
}

public function accountingOverview()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // Login ke Odoo
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;

    if (!$uid) {
        return view('accounting', ['error' => 'Gagal login ke Odoo']);
    }

    try {
        // --- Profit & Loss ---
        $pnlResponse = Http::post("$url/jsonrpc", [
            'jsonrpc' => "2.0",
            'method' => "call",
            'params' => [
                'service' => "object",
                'method' => "execute_kw",
                'args' => [
                    $db,
                    $uid,
                    $password,
                    'account.move.line',
                    'read_group',
                    [[], ['balance'], ['account_type']],
                    ['lazy' => false]
                ]
            ],
            'id' => null
        ]);

        $groups = $pnlResponse['result'] ?? [];

        $income = 0;
        $expense = 0;

        foreach ($groups as $group) {
            if ($group['account_type'] === 'income') {
                $income = abs($group['balance']);
            } elseif ($group['account_type'] === 'expense') {
                $expense = abs($group['balance']);
            }
        }

        $profit = $income - $expense;

        // --- Balance Sheet ---
// Ambil Balance Sheet dari journal entries
$bsResponse = Http::post("$url/jsonrpc", [
    'jsonrpc' => "2.0",
    'method' => "call",
    'params' => [
        'service' => "object",
        'method' => "execute_kw",
        'args' => [
            $db,
            $uid,
            $password,
            'account.move.line',
            'read_group',
            [[], ['balance'], ['account_type']],
            ['lazy' => false]
        ]
    ],
    'id' => null
]);
$bs = $bsResponse['result'] ?? [];

$assets = $liabilities = $equity = 0;
foreach($bs as $g) {
    switch($g['account_type']) {
      case 'asset':     $assets     = $g['balance']; break;
      case 'liability': $liabilities = $g['balance']; break;
      case 'equity':    $equity     = $g['balance']; break;
    }
}


        // --- COA Total Accounts ---
        $coaResponse = Http::post("$url/jsonrpc", [
            'jsonrpc' => "2.0",
            'method' => "call",
            'params' => [
                'service' => "object",
                'method' => "execute_kw",
                'args' => [
                    $db,
                    $uid,
                    $password,
                    'account.account',
                    'search',
                    [[]]
                ]
            ],
            'id' => null
        ]);

        $totalAccounts = is_array($coaResponse['result']) ? count($coaResponse['result']) : 0;

        // --- Kirim ke View ---
        return view('accounting', [
            'income' => $income,
            'expense' => $expense,
            'profit' => $profit,
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'totalAccounts' => $totalAccounts,
        ]);

    } catch (\Exception $e) {
        return view('accounting', ['error' => 'Gagal ambil data dari Odoo']);
    }
}

public function manufacturing()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // LOGIN
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;
    if (!$uid) {
        return view('manufacturing', [
            'orders' => [],
            'error' => 'Gagal login ke Odoo'
        ]);
    }

    // AMBIL MANUFACTURING ORDER
    try {
        $response = Http::post("$url/jsonrpc", [
            'jsonrpc' => "2.0",
            'method' => "call",
            'params' => [
                'service' => "object",
                'method' => "execute_kw",
                'args' => [
                    $db,
                    $uid,
                    $password,
                    'mrp.production',
                    'search_read',
                    [[]],
                    [
                        'fields' => ['name', 'product_id', 'product_qty', 'product_uom_id', 'state', 'date_start'],
                        'limit' => 50
                    ]
                ]
            ],
            'id' => null
        ]);
$responseData = $response->json();

if (isset($responseData['error'])) {
    dd($responseData['error']['data']);
}


        $orders = $response['result'] ?? [];

        return view('manufacturing', compact('orders'));

    } catch (\Exception $e) {
        return view('manufacturing', [
            'orders' => [],
            'error' => 'Gagal ambil data dari Odoo: ' . $e->getMessage()
        ]);
    }
}

public function billOfMaterials()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // Login
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;
    if (!$uid) {
        return view('bom', ['boms' => [], 'error' => 'Gagal login ke Odoo']);
    }

    try {
        // Ambil daftar BoM
        $response = Http::post("$url/jsonrpc", [
            'jsonrpc' => "2.0",
            'method' => "call",
            'params' => [
                'service' => "object",
                'method' => "execute_kw",
                'args' => [
                    $db,
                    $uid,
                    $password,
                    'mrp.bom',
                    'search_read',
                    [[]],
                    [
                        'fields' => ['id', 'product_tmpl_id', 'product_qty', 'product_uom_id', 'bom_line_ids'],
                        'limit' => 10
                    ]
                ]
            ],
            'id' => null
        ]);

        $boms = $response['result'] ?? [];

        foreach ($boms as &$bom) {
            $lineIds = $bom['bom_line_ids'] ?? [];
            $bom['lines'] = []; // pastikan key 'lines' selalu ada

            if (!empty($lineIds)) {
                $lineResponse = Http::post("$url/jsonrpc", [
                    'jsonrpc' => "2.0",
                    'method' => "call",
                    'params' => [
                        'service' => "object",
                        'method' => "execute_kw",
                        'args' => [
                            $db,
                            $uid,
                            $password,
                            'mrp.bom.line',
                            'read',
                            [$lineIds],
                            ['fields' => ['product_id', 'product_qty', 'product_uom_id']]
                        ]
                    ],
                    'id' => null
                ]);

                $components = $lineResponse['result'] ?? [];

                // Format komponen jadi array nama + qty + uom
                $bom['lines'] = array_map(function ($comp) {
                    return [
                        'name' => $comp['product_id'][1] ?? 'Komponen',
                        'qty' => $comp['product_qty'],
                        'uom' => $comp['product_uom_id'][1] ?? '-'
                    ];
                }, $components);
            }
        }

        return view('bom', compact('boms'));

    } catch (\Exception $e) {
        return view('bom', ['boms' => [], 'error' => 'Gagal ambil data dari Odoo: ' . $e->getMessage()]);
    }
}

public function inventory()
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // Login
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;
    if (!$uid) {
        return view('inventory', ['stocks' => ['raw' => [], 'finished' => []], 'error' => 'Gagal login ke Odoo']);
    }

    // Ambil Bahan Baku (type: consu)
    $rawResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db, $uid, $password,
                'product.product', 'search_read',
                [[['type', '=', 'consu']]],
                ['fields' => ['name', 'qty_available', 'uom_id']]
            ]
        ],
        'id' => null
    ]);

    $rawMaterials = collect($rawResponse['result'] ?? [])->map(function ($item) {
        return [
            'name' => $item['name'],
            'qty' => $item['qty_available'] ?? 0,
            'uom' => $item['uom_id'][1] ?? '-'
        ];
    })->toArray();

    // Ambil Produk Jadi (type: product)
// Ambil Produk Jadi (type: product)
$finishedResponse = Http::post("$url/jsonrpc", [
    'jsonrpc' => "2.0",
    'method' => "call",
    'params' => [
        'service' => "object",
        'method' => "execute_kw",
        'args' => [
            $db, $uid, $password,
            'product.product', 'search_read',
            [[['sale_ok', '=', true]]], // <-- GANTI INI
            ['fields' => ['name', 'qty_available', 'uom_id']]
        ]
    ],
    'id' => null
]);


    $finishedGoods = collect($finishedResponse['result'] ?? [])->map(function ($item) {
        return [
            'name' => $item['name'],
            'qty' => $item['qty_available'] ?? 0,
            'uom' => $item['uom_id'][1] ?? '-'
        ];
    })->toArray();

    return view('inventory', [
        'stocks' => [
            'raw' => $rawMaterials,
            'finished' => $finishedGoods
        ]
    ]);
}

public function inventoryMoves(Request $request)
{
    $url = env('ODOO_URL');
    $db = env('ODOO_DB');
    $username = env('ODOO_USER');
    $password = env('ODOO_PASS');

    // Login ke Odoo
    $loginResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "common",
            'method' => "login",
            'args' => [$db, $username, $password],
        ],
        'id' => null
    ]);

    $uid = $loginResponse['result'] ?? null;
    if (!$uid) {
        return view('inventory-moves', ['moves' => [], 'error' => 'Gagal login ke Odoo.']);
    }

    // Search by product name
    $search = $request->input('search');
    $domain = [];

    if ($search) {
        $domain[] = ['product_id.name', 'ilike', $search];
    }

    // Ambil data stock move
    $moveResponse = Http::post("$url/jsonrpc", [
        'jsonrpc' => "2.0",
        'method' => "call",
        'params' => [
            'service' => "object",
            'method' => "execute_kw",
            'args' => [
                $db, $uid, $password,
                'stock.move', 'search_read',
                [$domain],
                [
                    'fields' => [
                        'date', 'reference', 'product_id',
                        'location_id', 'location_dest_id',
                        'product_uom_qty', 'product_uom', 'state'
                    ],
                    'limit' => 100,
                    'order' => 'date desc'
                ]
            ]
        ],
        'id' => null
    ]);

    $moves = $moveResponse['result'] ?? [];

    return view('inventory-moves', [
        'moves' => $moves
    ]);
}

}

