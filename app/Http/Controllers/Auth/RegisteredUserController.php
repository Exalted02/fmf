<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
		//echo "<pre>";print_r($request->all()); die;
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
			'phone_number' => ['required', 'numeric', 'min:11'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'disclaimer_text' => ['required'],
        ],[
			'disclaimer_text' => 'Please check Disclaimer checkbox.',
		]);

        $user = User::create([
            'user_type' => 1,
            'name' => $request->first_name.''.$request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
			'remember_token' =>	Str::random(60),
			'status' => 1,
        ]);

        event(new Registered($user));
		
		$client_name = $request->first_name." ".$request->last_name;
		$email_content = get_email(1);
		if(!empty($email_content))
		{
			$logo = '<img src="' . url('front-assets/img/-logo1.png') . '" alt="'.Lang::get('project_title').'" width="150">';
			$maildata = [
				'subject' => $email_content->message_subject,
				'body' => str_replace(array("[LOGO]", "[NAME]", "[SCREEN_NAME]", "[YEAR]"), array($logo, $client_name, get_app_name(), date('Y')), $email_content->message),
				'toEmails' => array($request->email),
			];
			try {
				send_email($maildata);
			} catch (\Exception $e) {
				//
			}
		}

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
	public function store_customer(Request $request)
	{
		//echo "<pre>";print_r($request->all());
		 $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirm', Rules\Password::defaults()],
        ]);
		
		$first_name  		= $request->first_name;
		$last_name  		= $request->last_name;
		$email  			= $request->email;
		$password  			= $request->password;
		$confirm_password  	= $request->confirm_password;
		$company_name  		= $request->company_name;
		$address  			= $request->address;
		$city  				= $request->city;
		$state  			= $request->state;
		$zipcode  			= $request->zipcode;
		$phone_number  		= $request->phone_number;
		$upload_tax_lisence = $request->upload_tax_lisence;
		
		$moidel = new User();
		$moidel->first_name = $request->first_name ?? null;
		$moidel->last_name = $request->last_name ?? null;
		$moidel->email = $request->email ?? null;
		$moidel->password = $request->password ?? null;
		$moidel->city = $request->city ?? null;
		$moidel->state = $request->state ?? null;
		$moidel->zipcode = $request->zipcode ?? null;
		$moidel->phone_number = $request->phone_number ?? null;
		$moidel->upload_tax_lisence = $request->upload_tax_lisenc ?? nulle;
		$moidel->save();
		
		
	}
}
