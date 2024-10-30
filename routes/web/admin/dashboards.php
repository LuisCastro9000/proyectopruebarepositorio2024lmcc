<?php

use Illuminate\Support\Facades\Route;

// RUTA DASHBOARDS
Route::middleware(['web', 'isAdmin'])->namespace('App\Http\Controllers\Admin')->prefix('admin')->group(function () {
    Route::get('/dashboards', 'PanelAdminController')->name('admin.dashboards');
});
