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
				
				{{--<div class="row mt-4">
					<div class="col-md-12">
						@if(auth()->user()->subscribed())
							@if($last_subscription->ends_at > \Carbon\Carbon::now()->toDateTimeString())
								<h2 class="text-center">{{ languageTranslate('Your subscription is valid till') }} {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $last_subscription->ends_at)->format('d.m.Y') }}</h2>
							@else
								<form id="payment-form" action="{{ route('subscription.cancel') }}" method="POST">
									@csrf
									<input type="hidden" name="plan" id="plan" value="{{ $plan->id }}">
									<button class="btn btn-theme btn-lg btn-block">{{ __('subscription_cancel_subscr') }}</button>
								</form>
							@endif
						@else
							<div class="form-grid">
								<form id="payment-form" action="{{ route('subscription.create') }}" method="POST">
								@csrf
									<input type="hidden" name="plan" id="plan" value="">
									<div class="form-group">
										<label for="">{{ __('subscription_name') }}</label>
										<input type="text" name="name" id="card-holder-name" class="form-control" value="" placeholder="{{__('subscription_name') }}">
									</div>
									<div class="form-group">
										<label for="">{{__('subscription_card_details') }}</label>
										<div id="card-element"></div>
									</div>
									<hr>
									<div class="row mt-4">
										<div class="col-md-12">
											<div class="d-flex justify-between submit-section mt-2 mb-5">
												<button class="btn common-cancel-button">Back</button>
												<a href="{{route('portfolio-desires')}}"><button id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-primary common-button">Pay $159.99</button></a>
											</div>
										</div>
									</div>
								</form>
							</div>
						@endif	
					</div>
					@if(count($user->get_subscription) > 0)
						<div class="col-md-12">
							<div class="heading-panel mt-30">
								<h1 class="text-center mb-30">{{__('subscription_purchase_history') }}</h1>
								<table class="table">
									<tbody>
										<tr>
											<td>{{__('subscription_email') }}</td>
											<td>{{__('subscription_stripe_id') }}</td>
											<td>{{__('contact_doc_add_purchase_items_price') }}</td>
											<td>{{__('subscription_subscription_date') }}</td>
										</tr>
										@foreach($user->get_subscription as $subscription_val)
											<tr>
												<td>{{$user->email}}</td>
												<td>{{$subscription_val->stripe_id}}</td>
												<td>{{$subscription_val->price}}</td>
												<td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subscription_val->created_at)->format('d.m.Y') }}</td>
											</tr>
										@endforeach 
									</tbody>
								</table>
							</div>
						</div>
					@endif
				</div>--}}
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
        $("#card-button").text("Pay $" + price);
    });

});
</script>
<script src="https://js.stripe.com/v3/"></script>
<script>
	const stripe = Stripe('{{ env('STRIPE_KEY') }}')	
	const elements = stripe.elements()
	const cardElement = elements.create('card')	
	cardElement.mount('#card-element')
	
	const cardHolderName = document.getElementById('card-holder-name')
	const cardBtn = document.getElementById('card-button')
    const form = document.getElementById('payment-form')
		
	form.addEventListener('submit', async (e) => {
	    e.preventDefault()	
	    // cardBtn.disabled = true
	    const { setupIntent, error } = await stripe.confirmCardSetup(
	        cardBtn.dataset.secret, {
	            payment_method: {
	                card: cardElement,
	                billing_details: {
	                    name: cardHolderName.value
	                }   
	            }
	        }
	    )
	
	    if(error) {	
	        cardBtn.disable = false
	    } else {
	        let token = document.createElement('input')
	        token.setAttribute('type', 'hidden')
	        token.setAttribute('name', 'token')
	        token.setAttribute('value', setupIntent.payment_method)
	        form.appendChild(token)
	        form.submit();
	    }
	})	
</script>
@endsection 
