<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\OperatorRegistration;
use App\Livewire\MonitorDashboard;
use App\Livewire\ReportsPage;
use App\Livewire\ManageIndirectos;
use App\Models\WindowsAlert;

Route::get('/', function () {
    return redirect()->route('registro');
});

Route::get('/registro', OperatorRegistration::class)->name('registro');
Route::get('/monitor', MonitorDashboard::class)->name('monitor');
Route::get('/reportes', ReportsPage::class)->name('reportes');
Route::get('/catalogo', ManageIndirectos::class)->name('catalogo');

// ── Windows alert polling endpoints ──────────────────────────────
// GET /alerta              → returns next undelivered alert as JSON (or 204)
// GET /alerta/{id}/ok      → marks alert as delivered (GET avoids CSRF)
Route::get('/alerta', function () {
    $alert = WindowsAlert::where('delivered', false)->orderBy('id')->first();
    if (! $alert) {
        return response()->noContent(); // 204 — nothing pending
    }
    return response()->json(['id' => $alert->id, 'message' => $alert->message]);
});

Route::get('/alerta/{id}/ok', function (int $id) {
    WindowsAlert::where('id', $id)->update(['delivered' => true]);
    return response()->json(['ok' => true]);
});
