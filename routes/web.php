<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ContactController;

Route::get('/', [ContactController::class, 'index'])->name('contacts.index');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/');
    }
    return back()->withErrors(['email' => 'Dados inválidos'])->onlyInput('email');
})->name('login.attempt');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    return redirect('/');
})->name('logout');

Route::get('/contacts/create', [ContactController::class, 'create'])->middleware('auth')->name('contacts.create');
Route::post('/contacts', [ContactController::class, 'store'])->middleware('auth')->name('contacts.store');
Route::get('/contacts/{contact}', [ContactController::class, 'show'])->middleware('auth')->name('contacts.show');
Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->middleware('auth')->name('contacts.edit');
Route::put('/contacts/{contact}', [ContactController::class, 'update'])->middleware('auth')->name('contacts.update');
Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->middleware('auth')->name('contacts.destroy');