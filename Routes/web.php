<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\RetailObjects\Http\Controllers\RetailObjectsController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], static function () {
    /* Team */
    Route::group(['prefix' => 'retail-objects'], static function () {
        Route::get('/', [RetailObjectsController::class, 'index'])->name('admin.team.index');
        Route::get('/create', [RetailObjectsController::class, 'create'])->name('admin.team.create');
        Route::post('/store', [RetailObjectsController::class, 'store'])->name('admin.team.store');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [RetailObjectsController::class, 'activeMultiple'])->name('admin.team.active-multiple');
            Route::get('delete', [RetailObjectsController::class, 'deleteMultiple'])->name('admin.team.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [RetailObjectsController::class, 'edit'])->name('admin.team.edit');
            Route::post('update', [RetailObjectsController::class, 'update'])->name('admin.team.update');
            Route::get('delete', [RetailObjectsController::class, 'delete'])->name('admin.team.delete');
            Route::get('show', [RetailObjectsController::class, 'show'])->name('admin.team.show');
            Route::get('/active/{active}', [RetailObjectsController::class, 'active'])->name('admin.team.changeStatus');
            Route::get('position/up', [RetailObjectsController::class, 'positionUp'])->name('admin.team.position-up');
            Route::get('position/down', [RetailObjectsController::class, 'positionDown'])->name('admin.team.position-down');
            Route::get('image/delete', [RetailObjectsController::class, 'deleteImage'])->name('admin.team.delete-image');
        });
    });
});
