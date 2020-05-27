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
    $metabaseSecretKey = 'a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958';

    $signer = new Sha256();
    $token = (new Builder())
        ->set('resource', [
            'dashboard' => 1
        ])
        ->set('params', [
            'params' => (object)[]
        ])
        ->sign($signer, $metabaseSecretKey)
        ->getToken();

    $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true&titled=true";

    return view('welcome', compact('iframeUrl'));
});


Route::get('signed_dashboard/{userId}', function($userId) {

    $metabaseSiteUrl = 'localhost:3000';
    $metabaseSecretKey = 'a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958';

    $signer = new Sha256();
    $token = (new Builder())
        ->set('resource', [
            'dashboard' => 2
        ])
        ->set('params', [
            'id' => $userId
        ])
        ->sign($signer, $metabaseSecretKey)
        ->getToken();

    $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true";

    return view('signed_dashboard', compact('iframeUrl', 'userId'));
});

Route::get('signed_chart/{userId}', function($userId) {

    $metabaseSiteUrl = 'localhost:3000';
    $metabaseSecretKey = 'a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958';

    $signer = new Sha256();
    $token = (new Builder())
        ->set('resource', [
            'question' => 2
        ])
        ->set('params', [
            'person_id' => $userId
        ])
        ->sign($signer, $metabaseSecretKey)
        ->getToken();

    $iframeUrl = "{$metabaseSiteUrl}/embed/question/{$token}#bordered=true&titled=true";

    return view('signed_chart', compact('iframeUrl', 'userId'));

});

Route::get('signed_public_dashboard', function() {

    $metabaseSiteUrl = 'localhost:3000';
    $metabaseSecretKey = 'a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958';

    $signer = new Sha256();
    $token = (new Builder())
        ->set('resource', [
            'dashboard' => 1
        ])
        ->set('params', (object)[])
        ->sign($signer, $metabaseSecretKey)
        ->getToken();

    $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true&titled=true";

    return view('signed_public_dashboard', compact('iframeUrl'));

});