<?php

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

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

Route::get('/', function () {

    $metabaseSiteUrl = 'http://localhost:3000';
    $metabaseSecretKey = 'YOUR_SECRET_KEY';

    $signer = new Sha256();
    $token = (new Builder())
        ->set('resource', [
            'dashboard' => 1
        ])
        ->set('params', [
            'params' => []
        ])
        ->sign($signer, $metabaseSecretKey)
        ->getToken();

    $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true&titled=true";

    return view('welcome', compact('iframeUrl'));
});
