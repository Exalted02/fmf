@extends('layouts.app')
@section('content')
	<div class="row">
	<div class="col-md-6 left-section" style="background: url('../../front-assets/img/pp-bg.png') no-repeat center center/cover;">
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

			<div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white1 shadow-md-del overflow-hidden sm:rounded-lg">				
				<div class="login-head">Pricing Plans</div>
				<div class="pricing-wrapper">
					<section class="pricing-section">
						<div class="row">
							<!-- Monthly Billing -->
							<div class="col-md-6 col-sm-12 mb-4">
								<div class="pricing-card">
									<div class="billing-label">MONTHLY BILLING</div>
									<ul class="features">
										<li><i class="fa fa-square"></i>$159.99/month</li>
										<li><i class="fa fa-square"></i>24/7 Priority Support</li>
										<li><i class="fa fa-square"></i>Full Platform Access</li>
										<li><i class="fa fa-square"></i>Free Updates</li>
									</ul>
									<button class="select-btn selected-btn">SELECTED</button>
								</div>
							</div>

							<!-- Annual Billing -->
							<div class="col-md-6 col-sm-12 mb-4">
								<div class="pricing-card">
									<div class="billing-label">ANNUAL BILLING</div>
									<ul class="features">
										<li><i class="fa fa-square"></i>$1,499.99 Annual</li>
										<li><i class="fa fa-square"></i>24/7 Priority Support</li>
										<li><i class="fa fa-square"></i>Full Platform Access</li>
										<li><i class="fa fa-square"></i>Free Updates</li>
									</ul>
									<button class="select-btn">SELECT</button>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="login-head">Payment method</div>
				<p class="text-center">Dynamic payment gateway content will come here</p>
				
				<div class="row mt-4">
					<div class="col-md-12">
						<div class="d-flex justify-between submit-section mt-2 mb-5">
							<button class="btn common-cancel-button">Back</button>
							<a href="{{route('portfolio-desires')}}"><button class="btn btn-primary common-button">Pay $159.99</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection 
@section('scripts')

@endsection 
