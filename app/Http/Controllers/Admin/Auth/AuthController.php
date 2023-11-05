<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LoginRequest;
class AuthController extends Controller
{
    public function login(){
        return view("admin_dashboard.auth.login");
    }
    public function admin_login(LoginRequest $request){

        if (auth()->guard('admin')->attempt(['phone' => $request->phone, 'password' => $request->password]
        ,$request->remember)){
            return redirect()->route('admins.index')
            ->with(['success'=> __("messages.login successfully")]);
        }
        return redirect()->back()->with(['error'=> __("messages.name or password may be wrong")]);

    }

    public function logout(){
        auth()->guard('admin')->logout();
        return redirect()->route('admin_loginpage')->with(['success'=> __("messages.logout successfully")]);
    }
}
