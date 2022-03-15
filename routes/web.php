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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/database', function () {
    // \App\Models\Category::create([
    //     'name' => 'Gift Cards'
    // ]);

    // \App\Models\Subcategory::create([
    //     'name' => 'Netflix Gift Cards',
    //     'cat_id' => 1
    // ]);
    //return view('welcome');
});
Route::get('/greeting', function () {
    $response = Http::accept('text/plain')->get("http://66.45.237.70/api.php?username=01834920142&password=W5FB9KYS&number=01521210824&message=Test API");
    // $arr = explode('|' ,$response);
    // if($arr[0] === 1004){
    //     echo "Invalid number";
    // }
    $data = response($response,200);
    print_r($data);

});

