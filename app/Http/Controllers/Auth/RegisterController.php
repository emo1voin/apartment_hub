<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        private ApiService $api,
        private AuthService $auth
    ) {}

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = $this->api->post('auth/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if (!empty($result['success']) && $result['success']) {
            $this->auth->setToken($result['data']['token']);
            $userData = $result['data']['user'];
            if (isset($userData['data'])) {
                $userData = $userData['data'];
            }
            $this->auth->setUser($userData);
            return redirect()->route('hotels.index')->with('success', 'Регистрация успешна!');
        }

        return back()->withErrors(['email' => $result['message'] ?? 'Ошибка регистрации'])->withInput();
    }
}
