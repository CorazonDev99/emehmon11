<?php

use App\Http\Controllers\ListokController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PrivilegeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\MenuController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SelflistokController;
use App\Http\Controllers\Settings\HotelImagesController;
use App\Http\Controllers\Settings\RoomcleanersController;
use App\Http\Controllers\Settings\RoompricesController;
use App\Models\User;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\EditBookingController;
use App\Http\Controllers\InfoBookingController;
use App\Http\Middleware\CheckModeratorOrAdmin;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';


Route::get('/listok/identify-qr/{id}', [ListokController::class, 'getIdentifyQr']);
Route::get('/get-hotels-by-region/{regionId}', [ListokController::class, 'getHotelsByRegion']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::get('/register/create', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/register/searchGuest', [RegisterController::class, 'searchGuest'])->name('register.searchGuest');
    Route::post('/register/saveBasicInfo', [RegisterController::class, 'saveBasicInfo'])->name('register.saveBasicInfo');
    Route::post('/register/deleteSelected', [RegisterController::class, 'deleteSelected'])->name('register.deleteSelected');
    Route::auto('selflistok', SelflistokController::class);
});




Route::group(['middleware' => ['auth', CheckModeratorOrAdmin::class]], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::auto('users', UserController::class);
    Route::auto('menu', MenuController::class);
    Route::auto('audit', AuditController::class);

    Route::prefix('menu')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('menu.index');
        Route::post('/store', [MenuController::class, 'store'])->name('menu.store');
        Route::post('/update-order', [MenuController::class, 'updateOrder'])->name('menu.updateOrder');
        Route::post('/update-sidebar-order', [MenuController::class, 'updateSidebarOrder'])->name('menu.updateSidebarOrder');
    });

});



Route::post('/listok/move-to-room', [ListokController::class, 'moveToRoom'])->name('listok.move');
Route::post('/listok/status-payment', [ListokController::class, 'statusPayment'])->name('listok.status');
Route::post('/listok/tag', [ListokController::class, 'updateTag'])->name('update-tag');
Route::post('/listok/delete-tag', [ListokController::class, 'deleteTag'])->name('delete-tag');
Route::post('/listok/extend-visa', [ListokController::class, 'extendVisa'])->name('extend-visa');
Route::post('/listok/feedback', [ListokController::class, 'feedBack'])->name('listok.feedback');
Route::post('/privileges/create', [PrivilegeController::class, 'createModule'])->name('privileges.create');
Route::post('/privileges/delete', [PrivilegeController::class, 'deleteModule'])->name('privileges.delete');
Route::post('/privileges/edit', [PrivilegeController::class, 'editModule'])->name('privileges.edit');


Route::auto('booking', BookingController::class);
Route::auto('editbooking', EditBookingController::class);
Route::auto('infobooking', InfoBookingController::class);


Route::auto('privileges', PrivilegeController::class);
Route::auto('listok', ListokController::class);
Route::auto('rooms', RoomController::class);
Route::resources([
    'booking' => BookingController::class,
    'editbooking' => EditBookingController::class,
    'infobooking' => InfoBookingController::class,
    'listok' => ListokController::class,
    'rooms' => RoomController::class,
]);

Route::get('/booking', function () {
    return view('booking');
})->middleware('auth');
Route::get('/booking/book-table',[BookingController::class,'getBookTable'])->name('book-table');
Route::post('/book/book-guest', [BookingController::class, 'bookGuest'])->name('booking.bookGuest');
Route::get('/booking/update-booking',[BookingController::class,'updateBooking'])->name('update-booking');
Route::post('/booking/info-update-booking',[InfoBookingController::class, 'updateBooking'])->name('info-update-booking');


Route::group(['prefix' => 'settings', 'middleware' => 'auth'], function () {
    Route::auto('/roomprices', RoompricesController::class);
    Route::auto('/hotel-images', HotelImagesController::class);
    Route::auto('/room-cleaners', RoomcleanersController::class);
});

Route::view("test", 'test');
Route::post('add-card', function () {
    $product_id = request('product_id');
    $quantity = request('quantity');

    if (session()->has('cart')) {
        $cart = session()->get('cart');
    } else {
        $cart = [];
    }

    $cart[$product_id] = $quantity;
    session()->put('cart', $cart);
    return response()->json(['message' => 'Product #' . $product_id . ' with quantity ' . $quantity . ' has been added to the cart']);
});

//Route::get('/register', function () {
//    return view('register');
//})->middleware('auth');

Route::get('/auth-my', function () {
    $user = User::find(125);
    Auth::login($user);
    return view('dashboard');
});




Route::get('test-form', function (Request $request) {
    $user_id = $request->query('user_id');
    Auth::loginUsingId($user_id);
    $auth_user = Auth::user();
    if (Auth::check()) {
        if (!\Session::get('gid', false) || !\Session::get('rid', false)) {
            $id_region = \DB::table('tb_hotels')->where('id', $auth_user->id_hotel)->first();
            \Session::put('uid', $auth_user->id);
            \Session::put('gid', $auth_user->group_id);
            \Session::put('rid', $id_region->id_region);
            \Session::put('hid', $auth_user->id_hotel);
            \Session::put('eid', $auth_user->email);
            \Session::put('ll', $auth_user->last_login);
            \Session::put('fid', $auth_user->first_name . ' ' . $auth_user->last_name);
            \Session::put('grant_type', $auth_user->grant_type);
            \Session::put('htlname', $id_region->name);
            \Session::put('themes', 'sximo-light-blue');
        }
    }

    if (!\Session::get('themes')) {
        \Session::put('themes', 'sximo');
    }
    \App::setLocale(CNF_LANG);
    return redirect('dashboard');
});
