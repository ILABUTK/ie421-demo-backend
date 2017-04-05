<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Item;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('items', function (Request $request) {
  $name = $request->get('name');
  $desc = $request->get('desc');
  $price = $request->get('price');

  $item = new Item;
  $item->name = $name;
  $item->desc = $desc;
  $item->price = $price;
  $item->image = '';

  if($request->hasFile('image')){
    $uploadFile = $request->file('image');
    $path = $uploadFile->store('public/images');
    $url = Storage::url($path);
    $item->image = $url;
  }

  $item->save();

  return response($item->id);
});

Route::get('items', function () {
  return Item::all(['id', 'name', 'price', 'image']);
});


Route::get('items/{item}', function (Item $item) {
  return $item;
});

Route::delete('items/{item}', function (Item $item) {
  if($item->delete())
    return response('Deleted', 200);
  else
    return response('', 500);
});
