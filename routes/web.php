<?php


use lib\Route;

use app\Controllers\HomeController;
use app\Controllers\AudioController;
use app\Controllers\BookController;
use app\Controllers\ImageController;
use app\Controllers\SettingController;
use app\Controllers\VideoController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/audio', [AudioController::class, 'index']);

Route::get('/video', [VideoController::class, 'index']);

Route::get('/book', [BookController::class, 'index']);

Route::get('/image', [ImageController::class, 'index']);

Route::get('/setting', [SettingController::class, 'index']);
Route::post('/setting', [SettingController::class, 'post']);




Route::get('/video/:slug', function($peli) {
  echo "Hola desde la página de video sobre $peli";
});

Route::get('/video/:category/:slug', function($category, $peli) {
  echo "Hola desde la página de video de $category sobre $peli";
});


Route::dispatch();