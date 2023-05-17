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
    Route::group(['prefix' => 'retail-objects'], static function () {
        Route::get('/', [RetailObjectsController::class, 'index'])->name('admin.retail-objects.index');
        Route::get('/create', [RetailObjectsController::class, 'create'])->name('admin.retail-objects.create');
        Route::post('/store', [RetailObjectsController::class, 'store'])->name('admin.retail-objects.store');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [RetailObjectsController::class, 'activeMultiple'])->name('admin.retail-objects.active-multiple');
            Route::get('delete', [RetailObjectsController::class, 'deleteMultiple'])->name('admin.retail-objects.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [RetailObjectsController::class, 'edit'])->name('admin.retail-objects.edit');
            Route::post('update', [RetailObjectsController::class, 'update'])->name('admin.retail-objects.update');
            Route::get('delete', [RetailObjectsController::class, 'delete'])->name('admin.retail-objects.delete');
            Route::get('show', [RetailObjectsController::class, 'show'])->name('admin.retail-objects.show');
            Route::get('/active/{active}', [RetailObjectsController::class, 'active'])->name('admin.retail-objects.changeStatus');
            Route::get('position/up', [RetailObjectsController::class, 'positionUp'])->name('admin.retail-objects.position-up');
            Route::get('position/down', [RetailObjectsController::class, 'positionDown'])->name('admin.retail-objects.position-down');
            Route::get('image/delete', [RetailObjectsController::class, 'deleteImage'])->name('admin.retail-objects.delete-image');
        });
    });
});
