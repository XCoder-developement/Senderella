<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{

    protected function redirectTo(Request $request)
    {

        if ($request->is("api/*")) {
            return route('token_invalid');
        }
        if ($request->is("ciadmin/*") || $request->is("*/ciadmin/*") || $request->is('/ciadmin')
                                      || $request->is('*/ciadmin') || $request->is('ciadmin/*')) {
            return route('admin_loginpage');
        }
        
    }
}
