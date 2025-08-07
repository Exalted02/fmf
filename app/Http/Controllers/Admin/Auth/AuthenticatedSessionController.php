<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
		

        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    }
	/*public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];
		
		if(Auth::guard('admin')->attempt($data)) {
			dd(Auth::user()->name);
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME)->with('success','Login Successfull');
        } else {
            return redirect()->route('admin.login')->with('error','Invalid Credentials');
        }
    }*/

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
	public function loginWithToken(Request $request)
    {
        // Retrieve the token from the query parameters
        $token = $request->query('token');

        try {
            // Decrypt the token to get the user ID
            $userId = Crypt::decryptString($token);

            // Find the user by their ID
            $user = User::find($userId);

            if ($user) {
                // Log the user in on Site 2
                Auth::login($user);

                // Redirect to the dashboard or any desired page
                return redirect('/admin/dashboard');
            }
        } catch (\Exception $e) {
            // Handle invalid token
            return redirect('/admin/login')->withErrors('Invalid login token.');
        }
    }
}
