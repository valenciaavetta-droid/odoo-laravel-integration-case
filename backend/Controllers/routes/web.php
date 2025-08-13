<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OdooController;

Route::get('/', [OdooController::class, 'dashboard']);
Route::get('/sales', [OdooController::class, 'sales']);
Route::get('/sales/create', [OdooController::class, 'createQuotation']);
Route::get('/sales/orders', [OdooController::class, 'orderList']);
Route::get('/sales/customers', [OdooController::class, 'customers']);
Route::get('/sales/report', [OdooController::class, 'salesReport']);
Route::get('/purchase', [OdooController::class, 'purchaseOrders']);
Route::get('/purchase/vendors', [OdooController::class, 'vendors']);
Route::get('/purchase/products-to-buy', [OdooController::class, 'productsToBuy'])->name('to_buy');
Route::get('/accounting', [OdooController::class, 'accountingOverview']);
Route::get('/manufacturing', [OdooController::class, 'manufacturing']);
Route::get('/manufacturing/bom', [OdooController::class, 'billOfMaterials']);
Route::get('/inventory', [OdooController::class, 'inventory']);
Route::get('/inventory/moves', [OdooController::class, 'inventoryMoves'])->name('inventory-moves');
