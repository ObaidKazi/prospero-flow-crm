<?php

declare(strict_types=1);

use App\Http\Controllers\Tag\TagCreateController;
use App\Http\Controllers\Tag\TagDeleteController;
use App\Http\Controllers\Tag\TagIndexController;
use App\Http\Controllers\Tag\TagSaveController;
use App\Http\Controllers\Tag\TagUpdateController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/tag',
    [TagIndexController::class, 'index'])
    ->can('read tag');

Route::get('/tag/create',
    [TagCreateController::class, 'create'])
    ->can('create tag');

Route::get('/tag/update/{id}',
    [TagUpdateController::class, 'update'])
    ->can('update tag');

Route::post('/tag/save',
    [TagSaveController::class, 'save'])
    ->can('create tag')->can('update tag');

Route::get('/tag/delete/{id}',
    [TagDeleteController::class, 'delete'])
    ->can('delete tag');