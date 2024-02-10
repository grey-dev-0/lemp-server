<?php

use App\Http\Controllers\Controller;
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
Route::post('projects', [Controller::class, 'postProjects'])->name('projects.ajax');
Route::view('projects/add', 'create-project', ['title' => 'Create Project'])->name('projects.add');
Route::post('projects/add', [Controller::class, 'postProject']);
Route::view('domains', 'domains', ['title' => 'Domains'])->name('domains');
Route::post('domains', [Controller::class, 'postDomains'])->name('domains.ajax');
