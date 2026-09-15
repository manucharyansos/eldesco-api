<?php
use Illuminate\Support\Facades\Route;
use App\Models\Service;
Route::prefix('v1')->group(function(){
 Route::get('/health',fn()=>['ok'=>true,'service'=>'eldesco-api']);
 Route::get('/services',fn()=>Service::query()->where('is_published',true)->orderBy('sort_order')->get());
 Route::get('/services/{service:slug}',fn(Service $service)=>$service->is_published?$service:abort(404));
});
