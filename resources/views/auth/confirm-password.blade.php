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
						{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
					</div>

					<form method="POST" action="{{ route('password.confirm') }}">
						@csrf

						<!-- Password -->
						<div>
							<x-input-label for="password" :value="__('Password')" />

							<x-text-input id="password" class="block mt-1 w-full"
											type="password"
											name="password"
											required autocomplete="current-password" />

							<x-input-error :messages="$errors->get('password')" class="mt-2" />
						</div>

						<div class="flex justify-end mt-4">
							<x-primary-button>
								{{ __('Confirm') }}
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