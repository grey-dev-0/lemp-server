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
Route::get('projects/edit', [Controller::class, 'getEditProject'])->name('projects.edit');
Route::view('domains', 'domains', ['title' => 'Domains'])->name('domains');
Route::post('domains', [Controller::class, 'postDomains'])->name('domains.ajax');
Route::get('domains/add/{project?}', [Controller::class, 'getCreateDomain'])->name('domains.add');
Route::post('domains/add', [Controller::class, 'postDomain']);
Route::get('domains/edit/{domain}', [Controller::class, 'getEditDomain'])->name('domains.edit');
Route::post('domains/edit/{domain}', [Controller::class, 'postDomain']);
Route::post('stub/{project?}', [Controller::class, 'postStub']);