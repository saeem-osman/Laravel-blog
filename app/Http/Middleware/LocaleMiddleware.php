<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && !Session::has('locale')){
            Session::put('locale',$request->user()->locale);
        }

        if($request->has('locale')){
            $locale = $request->get('locale');
            Session::put('locale',$locale);
        }

        $locale = Session::get('locale');
        if(null == $locale){
            $locale = config('app.fallback_locale');
            
        }

        App::setLocale($locale);

        return $next($request);
    }
}