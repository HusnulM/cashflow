<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {

    $configCache = Artisan::call('config:cache');
    $clearCache = Artisan::call('cache:clear');
    // return what you want
});

Route::group(['middleware' => 'guest'], function(){
    Route::group(['middleware' => 'revalidate'], function () {
        Route::get('/',              'HomeController@index')->name('login');
        Route::post('authenticate',  'HomeController@login');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'revalidate'], function () {
        Route::get('/dashboard', 'HomeController@dashboard');

        Route::post('logout',  'HomeController@logout')->name('logout');

        Route::get('logout2',  'HomeController@logout')->name('logout');

        
        Route::group(['prefix' => '/setting'], function () {
            Route::get('/users',             'Setting\UserController@index')->middleware('checkAuth:setting/users');
            Route::get('/users/create',      'Setting\UserController@create')->middleware('checkAuth:setting/users');
            Route::get('/users/edit/{id}',   'Setting\UserController@edit')->middleware('checkAuth:setting/users');
            Route::post('/users/save',       'Setting\UserController@save')->middleware('checkAuth:setting/users');  
            Route::post('/users/update',     'Setting\UserController@update')->middleware('checkAuth:setting/users');  
            Route::get('/users/delete/{id}', 'Setting\UserController@delete')->middleware('checkAuth:setting/users');  
            Route::get('/users/list',        'Setting\UserController@list')->middleware('checkAuth:setting/users');  
    
            Route::get('/roles',             'Setting\RoleController@index')->middleware('checkAuth:setting/roles');
            Route::get('/roles/create',      'Setting\RoleController@create')->middleware('checkAuth:setting/roles');
            Route::post('/roles/save',       'Setting\RoleController@save')->middleware('checkAuth:setting/roles');
            Route::get('/roles/delete/{id}', 'Setting\RoleController@delete')->middleware('checkAuth:setting/roles');  
            Route::get('/roles/list',        'Setting\RoleController@list')->middleware('checkAuth:setting/roles');
    
            Route::get('/menugroups',             'Setting\MenuGroupController@index')->middleware('checkAuth:setting/menugroups');
            Route::get('/menugroups/create',      'Setting\MenuGroupController@create')->middleware('checkAuth:setting/menugroups');
            Route::get('/menugroups/edit/{id}',   'Setting\MenuGroupController@edit')->middleware('checkAuth:setting/menugroups');
            Route::get('/menugroups/delete/{id}', 'Setting\MenuGroupController@delete')->middleware('checkAuth:setting/menugroups');
            Route::post('/menugroups/save',       'Setting\MenuGroupController@save')->middleware('checkAuth:setting/menugroups');  
            Route::post('/menugroups/update',     'Setting\MenuGroupController@update');
    
            Route::get('/menus',        'Setting\MenuController@index')->middleware('checkAuth:setting/menus');
            Route::get('/menus/create', 'Setting\MenuController@create')->middleware('checkAuth:setting/menus');
            Route::post('/menus/save',  'Setting\MenuController@save')->middleware('checkAuth:setting/menus');  
            Route::get('/menus/list',   'Setting\MenuController@list')->middleware('checkAuth:setting/menus');
    
            Route::get('/menuroles',        'Setting\MenuRoleController@index')->middleware('checkAuth:setting/menuroles');
            Route::get('/menuroles/create', 'Setting\MenuRoleController@create')->middleware('checkAuth:setting/menuroles');
            Route::post('/menuroles/save',  'Setting\MenuRoleController@save')->middleware('checkAuth:setting/menuroles');
            Route::get('/menuroles/delete/{p1}/{p2}', 'Setting\MenuRoleController@delete')->middleware('checkAuth:setting/menuroles');
            
            Route::get('/userroles',        'Setting\UserRoleController@index')->middleware('checkAuth:setting/userroles');
            Route::get('/userroles/create', 'Setting\UserRoleController@create')->middleware('checkAuth:setting/userroles');
            Route::post('/userroles/save',  'Setting\UserRoleController@save')->middleware('checkAuth:setting/userroles');
            Route::get('/userroles/delete/{user}/{role}', 'Setting\UserRoleController@delete')->middleware('checkAuth:setting/userroles');
        });

        Route::group(['prefix' => '/master/bank'], function () {
            Route::get('/',           'Master\BankController@index')->middleware('checkAuth:master/bank');
            Route::get('/create',     'Master\BankController@create')->middleware('checkAuth:master/bank');
            Route::get('/edit/{id}',  'Master\BankController@edit')->middleware('checkAuth:master/bank');
            Route::post('/save',      'Master\BankController@save')->middleware('checkAuth:master/bank');
            Route::post('/update',    'Master\BankController@update')->middleware('checkAuth:master/bank');
            Route::get('/delete/{id}','Master\BankController@delete')->middleware('checkAuth:master/bank');
            Route::get('/detail/{id}','Master\BankController@detail');//->middleware('checkAuth:master/bank');
            Route::get('/biayaadm/{p1}/{p2}','Master\BankController@getbiayaadm');//->middleware('checkAuth:master/bank');
        });

        Route::group(['prefix' => '/master/banklist'], function () {
            Route::get('/',           'Master\BankListController@index')->middleware('checkAuth:master/banklist');
            Route::get('/create',     'Master\BankListController@create')->middleware('checkAuth:master/banklist');
            Route::get('/edit/{id}',  'Master\BankListController@edit')->middleware('checkAuth:master/banklist');
            Route::post('/save',      'Master\BankListController@save')->middleware('checkAuth:master/banklist');
            Route::post('/update',    'Master\BankListController@update')->middleware('checkAuth:master/banklist');
            Route::get('/delete/{id}','Master\BankListController@delete')->middleware('checkAuth:master/banklist');
        });

        Route::group(['prefix' => '/master/biayaadmin'], function () {
            Route::get('/',                'Master\BiayaTransferBank@index')->middleware('checkAuth:master/biayaadmin');
            Route::get('/create',          'Master\BiayaTransferBank@create')->middleware('checkAuth:master/biayaadmin');
            Route::get('/edit/{p1}/{p2}',  'Master\BiayaTransferBank@edit')->middleware('checkAuth:master/biayaadmin');
            Route::post('/save',           'Master\BiayaTransferBank@save')->middleware('checkAuth:master/biayaadmin');
            Route::post('/update',         'Master\BiayaTransferBank@update')->middleware('checkAuth:master/biayaadmin');
            Route::get('/delete/{p1}/{p2}','Master\BiayaTransferBank@delete')->middleware('checkAuth:master/biayaadmin');
        });

        Route::group(['prefix' => '/master/coa'], function () {
            Route::get('/',           'Master\CoaController@index')->middleware('checkAuth:master/coa');
            Route::get('/create',     'Master\CoaController@create')->middleware('checkAuth:master/coa');
            Route::get('/edit/{id}',  'Master\CoaController@edit')->middleware('checkAuth:master/coa');
            Route::post('/save',      'Master\CoaController@save')->middleware('checkAuth:master/coa');
            Route::post('/update',    'Master\CoaController@update')->middleware('checkAuth:master/coa');
            Route::get('/delete/{id}','Master\CoaController@delete')->middleware('checkAuth:master/coa');
        });

        Route::group(['prefix' => '/master/player'], function () {
            Route::get('/',            'Master\PlayerController@index')->middleware('checkAuth:master/player');
            Route::get('/create',      'Master\PlayerController@create')->middleware('checkAuth:master/player');
            Route::get('/upload',      'Master\PlayerController@upload')->middleware('checkAuth:master/player');
            Route::get('/edit/{id}',   'Master\PlayerController@edit')->middleware('checkAuth:master/player');
            Route::post('/save',       'Master\PlayerController@save')->middleware('checkAuth:master/player');
            Route::post('/update',     'Master\PlayerController@update')->middleware('checkAuth:master/player');
            Route::get('/delete/{id}', 'Master\PlayerController@delete')->middleware('checkAuth:master/player');
            Route::post('/upload/save','Master\PlayerController@importPlayer')->middleware('checkAuth:master/player');
            Route::get('/searchbyname','Master\PlayerController@searchByname');//->middleware('checkAuth:master/player');
            // master/player/searchbyname

            Route::get('/playerlist',   'Master\PlayerController@playerlist')->middleware('checkAuth:master/player');
        });

        Route::group(['prefix' => '/transaksi/deposit'], function () {
            Route::get('/',            'Transaksi\DepositController@index')->middleware('checkAuth:transaksi/deposit');
            Route::post('/save',       'Transaksi\DepositController@save')->middleware('checkAuth:transaksi/deposit');

            Route::get('/upload',      'Transaksi\DepositController@upload')->middleware('checkAuth:transaksi/deposit');
            Route::post('/upload/save','Transaksi\DepositController@importDeposit')->middleware('checkAuth:transaksi/deposit');

            Route::get('/verify',      'Transaksi\DepositController@verify')->middleware('checkAuth:transaksi/deposit/verify');
            Route::get('/close/{id}',  'Transaksi\DepositController@close')->middleware('checkAuth:transaksi/deposit/verify');
        });

        Route::group(['prefix' => '/transaksi/topup'], function () {
            Route::get('/',            'Transaksi\TopupController@index')->middleware('checkAuth:transaksi/topup');
            Route::post('/save',       'Transaksi\TopupController@save')->middleware('checkAuth:transaksi/topup');
        });

        Route::group(['prefix' => '/transaksi/withdraw'], function () {
            Route::get('/',            'Transaksi\WithdrawController@index')->middleware('checkAuth:transaksi/withdraw');
            Route::post('/save',       'Transaksi\WithdrawController@save')->middleware('checkAuth:transaksi/withdraw');

            Route::get('/verify',      'Transaksi\WithdrawController@verify')->middleware('checkAuth:transaksi/withdraw/verify');
            Route::get('/close/{id}',  'Transaksi\WithdrawController@close')->middleware('checkAuth:transaksi/withdraw/verify');

            Route::get('/upload',      'Transaksi\WithdrawController@upload')->middleware('checkAuth:transaksi/withdraw');
            Route::post('/upload/save','Transaksi\WithdrawController@importWithdraw')->middleware('checkAuth:transaksi/withdraw');
        });

        Route::group(['prefix' => '/transaksi/transfer'], function () {
            Route::get('/',            'Transaksi\PindahDanaController@index')->middleware('checkAuth:transaksi/transfer');
            Route::post('/save',       'Transaksi\PindahDanaController@save')->middleware('checkAuth:transaksi/transfer');
        });

        Route::group(['prefix' => '/transaksi/pemasukan'], function () {
            Route::get('/',            'Transaksi\PemasukanController@index')->middleware('checkAuth:transaksi/pemasukan');
            Route::post('/save',       'Transaksi\PemasukanController@save')->middleware('checkAuth:transaksi/pemasukan');
        });

        Route::group(['prefix' => '/transaksi/pengeluaran'], function () {
            Route::get('/',            'Transaksi\PengeluaranController@index')->middleware('checkAuth:transaksi/pengeluaran');
            Route::post('/save',       'Transaksi\PengeluaranController@save')->middleware('checkAuth:transaksi/pengeluaran');
        });

        Route::group(['prefix' => '/laporan'], function () {
            Route::get('/topup',                     'Reports\ReportController@reportTopup')->middleware('checkAuth:laporan/topup');
            Route::get('/topupview/{p1}/{p2}',       'Reports\ReportController@reportTopupView')->middleware('checkAuth:laporan/topup');

            Route::get('/withdraw',                  'Reports\ReportController@reportWithdraw')->middleware('checkAuth:laporan/withdraw');
            Route::get('/withdrawview/{p1}/{p2}',    'Reports\ReportController@reportWithdrawView')->middleware('checkAuth:laporan/withdraw');
            
            Route::get('/mutasi',                    'Reports\ReportController@reportMutasi')->middleware('checkAuth:laporan/mutasi');
            Route::get('/mutasiview/{p1}/{p2}/{p3}', 'Reports\ReportController@reportMutasiView')->middleware('checkAuth:laporan/mutasi');

            Route::get('/pemasukan',                 'Reports\ReportController@reportPemasukan')->middleware('checkAuth:laporan/pemasukan');
            Route::get('/pemasukanview/{p1}/{p2}',   'Reports\ReportController@reportPemasukanView')->middleware('checkAuth:laporan/pemasukan');

            Route::get('/pengeluaran',               'Reports\ReportController@reportPengeluaran')->middleware('checkAuth:laporan/pengeluaran');
            Route::get('/pengeluaranview/{p1}/{p2}', 'Reports\ReportController@reportPengeluaranView')->middleware('checkAuth:laporan/pengeluaran');

            Route::get('/deposit',                   'Reports\ReportController@reportDeposit')->middleware('checkAuth:laporan/deposit');
            Route::get('/depositview/{p1}/{p2}',     'Reports\ReportController@reportDepositView')->middleware('checkAuth:laporan/deposit');

            Route::get('/stockcoin',        'Reports\ReportController@reportStockcoin')->middleware('checkAuth:laporan/stockcoin');
            Route::get('/saldobank',        'Reports\ReportController@reportSaldobank')->middleware('checkAuth:laporan/saldobank');
        });

        // Route::group(['prefix' => '/laporan'], function () {
        //     Route::get('/topup',            'Reports\ReportController@reportTopup')->middleware('checkAuth:laporan/topup');
        //     Route::get('/withdraw',         'Reports\ReportController@reportWithdraw')->middleware('checkAuth:laporan/withdraw');
        //     Route::get('/mutasi',           'Reports\ReportController@reportMutasi')->middleware('checkAuth:laporan/mutasi');
        // });
    });
});