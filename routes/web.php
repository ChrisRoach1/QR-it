<?php

use App\Http\Controllers\ProfileController;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $codes = Code::where('user_id', auth()->id())->paginate(6);

    return view('dashboard', ['codes' => $codes]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::post('create-code', function (Request $request) {

    $validated = $request->validate([
        'url' => 'required|url'
    ]);

    $newCode = Code::create([
        'url' => $request->get('url'),
        'user_id' => $request->user()->id
    ]);

    return redirect('/dashboard');

})->middleware(['auth', 'verified'])->name('code.store');

Route::delete('delete-code/{id}', function ($id) {
    Code::destroy($id);

    return redirect('/dashboard');

})->middleware(['auth', 'verified'])->name('code.destroy');


Route::get('download/{code}', function (Code $code) {
    return response()->streamDownload(
        function () use ($code) {
            echo QrCode::size(200)
                ->format('png')
                ->generate($code->url);
        },
        "qr-code.png",
        [
            'Content-Type' => 'image/png',
        ]
    );

})->name("download");

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
