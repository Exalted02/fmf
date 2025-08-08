@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col-md-6 left-section" style="background: url('../../front-assets/img/login-bg.jpg') no-repeat center center/cover;">
			<div class="overlay"></div>
			<div class="logo-wrapper">
				<img src="{{ asset('front-assets/img/-logo1-bw.png') }}" alt="Fidelity Logo">
			</div>
		</div>
		<div class="col-md-6 login-form">
			<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 login-screen">
				<div class="login-logo" style="display:none;">
					<a href="/" class="logo-image">
						<x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
					</a>
				</div>

				<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white1 shadow-md-del overflow-hidden sm:rounded-lg">
					<div class="mb-4 text-sm text-gray-600">
						{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
					</div>

					<!-- Session Status -->
					<x-auth-session-status class="mb-4" :status="session('status')" />

					<form method="POST" action="{{ route('password.email') }}">
						@csrf

						<!-- Email Address -->
						<div>
							<x-input-label for="email" :value="__('Email')" />
							<x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
							<x-input-error :messages="$errors->get('email')" class="mt-2" />
						</div>

						<div class="flex items-center justify-end mt-4">
							<x-primary-button>
								{{ __('Email Password Reset Link') }}
							</x-primary-button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection 
@section('scripts')

@endsection