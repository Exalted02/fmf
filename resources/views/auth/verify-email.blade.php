@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col-md-6 left-section" style="background: url({{ asset('front-assets/img/login-bg.jpg') }}) no-repeat center center/cover;">
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
						{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
					</div>

					@if (session('status') == 'verification-link-sent')
						<div class="mb-4 font-medium text-sm text-green-600">
							{{ __('A new verification link has been sent to the email address you provided during registration.') }}
						</div>
					@endif

					<div class="mt-4 flex items-center justify-between">
						<form method="POST" action="{{ route('verification.send') }}">
							@csrf

							<div>
								<x-primary-button>
									{{ __('Resend Verification Email') }}
								</x-primary-button>
							</div>
						</form>

						<form method="POST" action="{{ route('logout') }}">
							@csrf

							<button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
								{{ __('Log Out') }}
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection 
@section('scripts')

@endsection
