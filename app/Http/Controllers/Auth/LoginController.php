<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private ApiService $api,
        private AuthService $auth
    ) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->api->post('auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!empty($result['success']) && $result['success']) {
            $this->auth->setToken($result['data']['token']);
            $userData = $result['data']['user'];
            // UserResource может вернуть данные в разных форматах
            if (isset($userData['data'])) {
                $userData = $userData['data'];
            }
            $this->auth->setUser($userData);
            return redirect()->intended(route('hotels.index'))->with('success', 'Добро пожаловать!');
        }

        return back()->withErrors(['email' => $result['message'] ?? 'Неверные учетные данные'])->withInput();
    }

    public function logout()
    {
        $this->api->post('auth/logout');
        $this->auth->logout();
        return redirect()->route('hotels.index')->with('success', 'Вы вышли из системы');
    }
}
