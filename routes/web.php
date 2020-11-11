<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/keygen', function () {
    return \Illuminate\Support\Str::random(32);
});

$router->get('/berita', 'BeritaController@getBerita');

$router->post('/ota/register', 'UserController@registerOTA');
$router->post('/sekolah/register', 'UserController@registerSekolah');
$router->post('/admin/register', 'UserController@registerAdmin');

$router->group(['middleware' => 'client'], function () use ($router) {
    $router->get('/get', function () {
        return \Illuminate\Support\Facades\Auth::user();
    });

    $router->post('/post', function () {
        return 'hello post';
    });

    $router->get('/profile', 'UserController@getProfile');
    $router->get('/logout', 'UserController@logout');

    $router->get('/ota/', 'OrangTuaAsuhController@getRiwayat');
    $router->post('/ota/order', 'OrangTuaAsuhController@order');
    $router->post('/ota/bayar/{order_id}', 'OrangTuaAsuhController@confirmPayment');

    $router->post('/sekolah/pengajuan/anakasuh', 'SekolahController@pengajuanAA');

    $router->get('/admin/pembayaran/unverified', 'AdminController@getUnverifiedPembayaran');
    $router->post('/admin/pembayaran/verif/{order_id}', 'AdminController@verifPembayaran');
    $router->get('/admin/sekolah', 'AdminController@getSekolah');
    $router->post('/admin/sekolah/verif/{sekolah_id}', 'AdminController@verifSekolah');
    $router->get('/admin/pengajuan/aa', 'AdminController@getPengajuanAA');
    $router->post('/admin/pengajuan/aa/verif/{pengajuan_aa_id}', 'AdminController@verifPengajuanAA');

});

