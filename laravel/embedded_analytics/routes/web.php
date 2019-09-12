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
use Lcobucci\JWT\Signer\Key;

Route::get('/', function () {

    $metabaseSiteUrl = 'http://localhost:3000';
    $metabaseSecretKey = 'f8a86a48501150b3561e5cd3ff07865f6b400ecceca58882cdd4adfa07f2c488';

    $signer = new Sha256();
    $token = (new Builder())
        ->withClaim('resource', [
            'dashboard' => 1
        ])
        ->withClaim('params', [
            'params' => []
        ])
        ->getToken($signer, new Key($metabaseSecretKey));

    $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true&titled=true";

    return view('welcome', compact('iframeUrl'));
});


Route::get('signed_dashboard/{userId}', function($userId) {

    $metabaseSiteUrl = 'localhost:3000';
    $metabaseSecretKey = 'f8a86a48501150b3561e5cd3ff07865f6b400ecceca58882cdd4adfa07f2c488';

    $signer = new Sha256();
    $token = (new Builder())
        ->withClaim('resource', [
            'dashboard' => 2
        ])
        ->withClaim('params', [
        ])
        ->getToken($signer, new Key($metabaseSecretKey));

    $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true";

    return view('signed_dashboard', compact('iframeUrl', 'userId'));
});

Route::get('signed_chart/{userId}', function($userId) {

    $metabaseSiteUrl = 'localhost:3000';
    $metabaseSecretKey = 'f8a86a48501150b3561e5cd3ff07865f6b400ecceca58882cdd4adfa07f2c488';

    $signer = new Sha256();
    $token = (new Builder())
        ->withClaim('resource', [
            'question' => 6
        ])
        ->withClaim('params', [
            'user_id' => $userId
        ])
        ->getToken($signer, new Key($metabaseSecretKey));

    $iframeUrl = "{$metabaseSiteUrl}/embed/question/{$token}#bordered=true&titled=true";

    return view('signed_chart', compact('iframeUrl', 'userId'));

});

Route::get('signed_public_dashboard', function() {

    $metabaseSiteUrl = 'localhost:3000';
    $metabaseSecretKey = 'f8a86a48501150b3561e5cd3ff07865f6b400ecceca58882cdd4adfa07f2c488';

    $signer = new Sha256();
    $token = (new Builder())
        ->withClaim('resource', [
            'dashboard' => 1
        ])
        ->withClaim('params', [
        ])
        ->getToken($signer, new Key($metabaseSecretKey));

    $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true&titled=true";

    return view('signed_public_dashboard', compact('iframeUrl'));

});