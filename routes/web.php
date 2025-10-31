<?php

use App\Http\Controllers\AddressesController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\PeopleController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/people');

Route::resource('people', PeopleController::class);

Route::resource('people.addresses', AddressesController::class)->except(['show']);
Route::resource('people.contacts', ContactsController::class)->except(['show']);
Route::post('people/{person}/contacts/{contact}/primary', [ContactsController::class, 'setPrimary'])
    ->name('people.contacts.primary');
