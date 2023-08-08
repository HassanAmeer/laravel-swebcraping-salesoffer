<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\scrapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('welcome', function () {
//     return view('welcome');
// });

Route::get('/', [scrapController::class , 'scrapf']);
Route::get('allbrands', [scrapController::class , 'vallbrandsf'])->name('vallbrandspage');
Route::get('allcatg', [scrapController::class , 'vallcatgf'])->name('vallcatgpage');
Route::get('categories/{id}', [scrapController::class , 'categoriespostf'])->name('vcategoriesbyid');
Route::get('detailspage/{id}', [scrapController::class , 'detailspagef'])->name('vdetailsbyid');
Route::get('allsalesofbrand/{id}', [scrapController::class , 'vallsaleofbrandsf'])->name('vallsalesbybrandid');

