<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-weather', function () {
    $apiKey = config('services.openWeather.key');
    $lat = request('lat');
    $lng = request('lon');
    $endpoint = request('endpoint');
    $response = Http::get("https://api.openweathermap.org/data/2.5/$endpoint?lat=$lat&lon=$lng&units=metric&appid=$apiKey");
    return $response->json();
});

Route::get('/get-address', function () {
    $apiKey = config('services.geoapify.key');
    $address = request('address');
    $response = Http::get("https://api.geoapify.com/v1/geocode/autocomplete?text=$address&apiKey=$apiKey");
    return $response->json();
});
