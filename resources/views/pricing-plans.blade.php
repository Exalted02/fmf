@extends('layouts.app')
@section('content')
@php 
$monthly_billing = App\Models\Pricing_plan::where('plan_type',1)->get();
$yearly_billing = App\Models\Pricing_plan::where('plan_type',2)->get();
@endphp
	<div class="row">
		<div class="col-md-6 left-section" style="background: url({{ asset('front-assets/img/pp-bg.png') }}) no-repeat center center/cover;">
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
							<div class="col-md-6 col-sm-12 mb-4 text-center">
								<div class="pricing-card">
									<div class="billing-label">MONTHLY BILLING</div>
									<ul class="features">
									@foreach($monthly_billing as $monthly)
										<li><i class="fa fa-square"></i>{{ $monthly->plan_name ?? ''}}</li>
									@endforeach
									</ul>
								</div>
								<button class="select-btn selected-btn" data-price="159.99" data-plan="monthly">SELECTED</button>
							</div>

							<!-- Annual Billing -->
							<div class="col-md-6 col-sm-12 mb-4 text-center">
								<div class="pricing-card">
									<div class="billing-label">ANNUAL BILLING</div>
									<ul class="features">
									@foreach($yearly_billing as $yearly)
										<li><i class="fa fa-square"></i>{{ $yearly->plan_name ?? '' }}</li>
									@endforeach
									</ul>
								</div>
								<button class="select-btn" data-price="1080.10" data-plan="annual">SELECT</button>
							</div>
						</div>
					</section>
				</div>
				<div class="login-head">Payment Method</div>
				<p class="text-center">Dynamic payment gateway content will come here</p>
				
				<div class="row mt-4">
					<div class="col-md-12">
						<div class="d-flex justify-between submit-section mt-2 mb-5">
							<button class="btn common-cancel-button">Back</button>
							<a href="{{route('portfolio-desires')}}"><button id="payBtn" class="btn btn-primary common-button">Pay $159.99</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection 
@section('scripts')
<script>
$(document).ready(function(){

    $(".select-btn").on("click", function(){

        // Reset all buttons
        $(".select-btn").removeClass("selected-btn").text("SELECT");

        // Highlight selected
        $(this).addClass("selected-btn").text("SELECTED");

        // Get price from selected button
        var price = $(this).data("price");

        // Update Pay button text
        $("#payBtn").text("Pay $" + price);
    });

});
</script>
@endsection 
