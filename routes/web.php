<?php

use Illuminate\Support\Facades\Route;

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

Route::view('/', 'home', ['title' => 'Home'])->name('home');
Route::view('projects', 'projects', ['title' => 'Projects'])->name('projects');
Route::view('domains', 'domains', ['title' => 'Domains'])->name('domains');
