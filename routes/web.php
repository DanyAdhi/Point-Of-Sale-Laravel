<?php
use Illuminate\Http\Request;
use App\Customer;

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::group(['middleware'=>['role:Admin']], function(){
        Route::get('/customers', function(Request $request){
            // return 'Oke';
            $customer = Customer::where('email', $request->email)->get();
            dd($request->email);
        });

        // Route Category
        Route::resource('/categories', 'CategoryController')->except(['show', 'create']);
        
        // Route Product
        Route::resource('/products', 'ProductController')->except(['show']);

        // Route User
        Route::post('/users/permission', 'UserController@addPermission')->name('users.add_permission');
        Route::get('/users/role-permission', 'UserController@rolePermission')->name('users.roles_permission');
        Route::put('/users/permission/{role}', 'UserController@setRolePermission')->name('users.setRolePermission');
        Route::get('/users/roles/{id}', 'UserController@roles')->name('users.roles');
        Route::resource('/users', 'UserController')->except(['show']);

        // Route roles
        Route::resource('/roles', 'RoleController')->except([
            'create', 'show', 'edit', 'update'
        ]);
    });
});


    //route yang berada dalam group ini, hanya bisa diakses oleh user
    //yang memiliki permission yang telah disebutkan dibawah
    // Route::group(['middleware' => ['permission:Show Products|Create Product|Delete Product']], function() {
    Route::group(['middleware'=>'auth'], function(){
        Route::group(['middleware' => ['permission:Show Products']], function() {
            Route::get('products', 'ProductController@index')->name('products.index');
        });
        Route::group(['middleware' => ['permission:Create Product']], function() {
            Route::get('products/create', 'ProductController@create')->name('products.create');
            Route::post('products/store', 'ProductController@store')->name('products.store');
        });
        Route::group(['middleware' => ['permission:Edit Product']], function() {
            Route::get('products/{product}/edit', 'ProductController@edit')->name('products.edit');
            Route::put('products/{product}', 'ProductController@update')->name('products.update');
        });
        Route::group(['middleware' => ['permission:Show Products']], function() {
            Route::delete('products/{product}', 'ProductController@destroy')->name('products.destroy');
        });
    });
    // Route::group(['middleware'=>'auth'], function(){
    //     Route::group(['middleware' => ['permission:Create Product']], function() {
    //         Route::get('products/create', 'ProductController@create')->name('products.create');
    //         Route::post('products/store', 'ProductController@store')->name('products.store');
    //     });
    // });


    // Route::group(['middleware' => ['permission:Show Products|Create Products']], function() {
    //     Route::resource('/categories', 'CategoryController')->except([
    //         'create', 'show'
    //     ]);
    //     Route::resource('/products', 'ProductController');
    // });

    //route group untuk kasir
    Route::group(['middleware' => ['role:Kasir']], function() {
        Route::get('/transaksi', 'OrderController@addOrder')->name('order.transaksi');
        Route::get('/checkout', 'OrderController@checkout')->name('order.checkout');
        Route::post('/checkout', 'OrderController@storeOrder')->name('order.storeOrder');
        Route::get('/orders', 'OrderController@index')->name('orders.index');
        Route::get('/order/pdf/{invoice}', 'OrderController@invoicePdf')->name('orders.pdf');
        Route::get('/order/excel/{invoice}', 'OrderController@invoiceExcel')->name('orders.excel');
    });


// Route Dashboard
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
