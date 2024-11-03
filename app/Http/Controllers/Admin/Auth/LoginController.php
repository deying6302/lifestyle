<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getLogin() // :GET
    {
        $logoIcon = Frontend::where('data_key', 'logo_icon.data')->first();
        $logoIcon = $logoIcon ? json_decode($logoIcon->data_value) : null;
        return view('admin.auth.login', compact('logoIcon'));
    }

    public function postLogin() // :POST
    {
        $credentials = [
            'email'    => $this->request->email,
            'password' => $this->request->password,
        ];

        $remember = $this->request->filled('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            toastr()->success('Chào mừng bạn đến trang quản trị viên');
            return redirect()->route('admin.dashboard');
        } else {
            toastr()->warning('Bạn nhập sai thông tin email & mật khẩu');
            return redirect()->back();
        }
    }
}
