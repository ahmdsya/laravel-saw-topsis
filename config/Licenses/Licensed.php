<?php

namespace Config\Licenses;

use Closure;
use Illuminate\Http\Request;
use Config\Licenses\Lincense;

class Licensed
{
    public function handle(Request $request, Closure $next)
    {
        if(Lincense::isConnected() && Lincense::isHaveAccess()){
            return $next($request);
        }
        elseif(!Lincense::isConnected()){
            abort(503);
        }

        abort(401);
    }
}
