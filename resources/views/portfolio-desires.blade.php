@extends('layouts.app')
@section('content')
@php 
    $primary_goal = '';
	if(isset($record->RIPG))
	{
		$primary_goal = explode(",", $record->RIPG);
	}
	//echo "<pre>";print_r($primary_goal);die;
	
	$primary1 = '';
	$primary2 = '';
	$primary3 = '';
	
	if(!empty($primary_goal))
	{
		if(in_array(1, $primary_goal))
		{
			$primary1 = 1;
		}
		
		if(in_array(2, $primary_goal))
		{
			$primary2 = 2;
		}
		
		if(in_array(3, $primary_goal))
		{
			$primary3 = 3;
		}
	}
@endphp
    <!-- Page Wrapper -->
    <div class="container">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
			<div class="row">
				<div class="col-md-10 order-2 order-sm-1">
					<div class="scroll-section">
						<!-- Page Header -->
						<div class="page-header">
							<div class="row">
								<div class="col-md-12">
									<h3 class="page-title SofiaPro-SemiBold">Client Portfolio and Desires</h3>
								</div>
							</div>
						</div>
						<!-- /Page Header -->
					<form id="frmPortfolioDesires" name="frmPortfolioDesires" method="post" action="{{ route('portfolio-desires') }}">
						<div class="row">
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Client's Name</label>
									<input type="text" class="form-control" placeholder="Enter Client's Name" name="client_name" id="client_name" value="{{ $record->client_name ?? '' }}">
									<div class="client_name_error error-text"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Client's Age</label>
									<input type="text" class="form-control"  name="client_age" id="client_age" placeholder="Enter Client's Age"  value="{{ $record->client_age ?? '' }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									<div class="client_age_error error-text"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Partner's Name</label>
									<input type="text" class="form-control"   name="partner_name" id="partner_name" placeholder="Enter Partner's Name" value="{{ $record->partner_name ?? '' }}">
									<div class="partner_name_error error-text"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Partner's Age</label>
									<input type="text" class="form-control"   name="partner_age" id="partner_age" placeholder="Enter Partner's Age" value="{{ $record->partner_age ?? '' }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									<div class="partner_age_error error-text"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">Financial Information</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Current Portfolio Value</label>
									<div class="input-dollar">
									<span class="currency-symbol">$</span>
									<input type="text" class="form-control"   name="current_portfolio_value" id="current_portfolio_value" placeholder="Enter Current Portfolio Value"  value="{{ $record->current_portfolio_value ?? '' }}" onkeypress="return isNumberKey(event,this)">
									</div>
									<div class="current_portfolio_error error-text"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Desired Gross Income During Retirement</label>
									<div class="input-dollar">
									<span class="currency-symbol">$</span>
									<input type="text" class="form-control"   name="desired_gross_income_retirement" id="desired_gross_income_retirement" placeholder="Enter Desired Gross Income" value="{{ $record->desired_gross_income_retirement ?? '' }}" onkeypress="return isNumberKey(event,this)">
									</div>
									<div class="current_gross_portfolio_error error-text"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Desired Retirement Age</label>
									<input type="text" class="form-control"   name="desired_retirement_age" id="desired_retirement_age" placeholder="Enter Desired Retirement Age" value="{{ $record->desired_retirement_age ?? '' }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									<div class="desired_retirement_age_error error-text"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">Assumptions</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Cost of Living Adjustment (COLA)</label>
									<div class="input-percent">
									<input type="text" class="form-control"   name="COLA" id="COLA" placeholder="Enter Cost of Living Adjustment (COLA)" value="{{ $record->COLA ?? '' }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									</div>
									<div class="COLA_error error-text"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Age to Begin COLA Adjustment</label>
									<div class="input-percent-s">
									<input type="text" class="form-control"  name= "cola_age" id="cola_age" placeholder="Enter Age to Begin COLA Adjustment" value="{{ $record->cola_age ?? '' }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									</div>
									<div class="cola_age_error error-text"></div>
									
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Assumed Return</label>
									<div class="input-percent">
									<input type="text" class="form-control"   name="assumed_return" id="assumed_return" placeholder="Enter Assumed Return" value="{{ $record->assumed_return ?? '' }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
									</div>
									<div class="assumed_return_error error-text"></div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-box">
									<h3 class="content-heading SofiaPro-SemiBold">Retirement Income Planning Goal</h3>
									<div class="d-flex justify-between">	
									    <label class="checkbox-inline"><input type="checkbox" name="RIPG[]" value="1" class="form-check-input" {{ !empty($primary1) && $primary1 == 1 ? 'checked' : '' }} ><span class="checkmark SofiaPro-SemiBold">Income</span></label>

										<label class="checkbox-inline"><input type="checkbox" name="RIPG[]" value="2" class="form-check-input" {{ !empty($primary2) && $primary2 == 2 ? 'checked' : '' }}><span class="checkmark SofiaPro-SemiBold">Tax Reduction</span></label>
									
										<label class="checkbox-inline"><input type="checkbox" name="RIPG[]" value="3" class="form-check-input" {{ !empty($primary3) && $primary3 == 3 ? 'checked' : '' }}><span class="checkmark SofiaPro-SemiBold">Legacy</span></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<a href="{{ route('pricing-plans') }}"><button type="button" class="btn btn-primary common-button back-page"> <i class="fa fa-arrow-left"></i> Back</button></a>
								
								<button class="btn btn-primary common-button save-portfolio-desire">Next <i class="fa fa-arrow-right"></i></button>
								
							</div>
						</div>
					</div>
					</form>
				</div>			
				<div class="col-md-2 order-1 order-sm-2">
					<div class="step-section">
						<div class="triangle-container">
							<div class="triangle-up active">
								<span class="triangle-number">1</span>
							</div>
							<div class="triangle-up">
								<span class="triangle-number">2</span>
							</div>
							<div class="triangle-up">
								<span class="triangle-number">3</span>
							</div>
							<div class="triangle-up">
								<span class="triangle-number">4</span>
							</div>
							<div class="triangle-up">
								<span class="triangle-number">5</span>
							</div>
						</div>

						<div class="step-content mt-3">
							<h5 class="step-title">Client Portfolio</h5>
							<p class="step-description">
								Use the Income Allocation Tool to examine a client’s current financial situation and income sources in retirement to help effectively put together a financial plan.
							</p>
						</div>
					</div>
				</div>			
			</div>	
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

@endsection 
@section('scripts')
<script>
 $(document).ready(function(){
	$('.save-portfolio-desire').on('click', function(e){
		e.preventDefault();
		var client_name = $('#client_name').val().trim();
		var client_age = $('#client_age').val();
		var partner_name = $('#partner_name').val();
		var partner_age = $('#partner_age').val();
		var current_portfolio_value = $('#current_portfolio_value').val();
		var desired_gross_income_retirement = $('#desired_gross_income_retirement').val();
		var desired_retirement_age = $('#desired_retirement_age').val();
		var COLA = $('#COLA').val();
		var cola_age = $('#cola_age').val();
		var assumed_return = $('#assumed_return').val();
		//var RIPG = $('#RIPG').val();
		let RIPG = $('input[name="RIPG[]"]:checked')
			.map(function () {
				return $(this).val();
			})
			.get();
		//alert(RIPG);
		$('.client_name_error').text('');
		$('.client_age_error').text('');
		$('.partner_name_error').text('');
		$('.partner_age_error').text('');
		//$('.').text('');
		
		$('.current_portfolio_error').text('');
		$('.current_gross_portfolio_error').text('');
		$('.desired_retirement_age_error').text('');
		$('.COLA_error').text('');
		$('.cola_age_error').text('');
		$('.assumed_return_error').text('');
		
		
		let isValid = true;
		$('.invalid-feedback').hide();
		$('.form-control').removeClass('is-invalid');
		if (client_name === '')
		{
			$('.client_name_error').text('Enter client name');
			isValid = false;
		}
		if (client_age === '')
		{
			$('.client_age_error').text('Enter client age');
			isValid = false;
		}
		if (partner_name === '')
		{
			$('.partner_name_error').text('Enter partner name');
			isValid = false;
		}
		if (partner_age === '')
		{
			$('.partner_age_error').text('Enter partner age');
			isValid = false;
		}
		if (current_portfolio_value === '')
		{
			$('.current_portfolio_error').text('Enter current portfolio value');
			isValid = false;
		}
		if (desired_gross_income_retirement === '')
		{
			$('.current_gross_portfolio_error').text('Enter gross income retirement');
			isValid = false;
		}
		if (desired_retirement_age === '')
		{
			$('.desired_retirement_age_error').text('Enter desired retirement age');
			isValid = false;
		}
		if (COLA === '')
		{
			$('.COLA_error').text('Enter COLA');
			isValid = false;
		}
		if (cola_age === '')
		{
			$('.cola_age_error').text('Enter COLA  age adjustment');
			isValid = false;
		}
		if (assumed_return === '')
		{
			$('.assumed_return_error').text('Enter assumed Return');
			isValid = false;
		}
		
		if (isValid) {
			var URL = $('#frmPortfolioDesires').attr('action');
			
			$.ajax({
				url: URL,
				type: "POST",
				data: {client_name:client_name,client_age:client_age,partner_name:partner_name,partner_age:partner_age,current_portfolio_value:current_portfolio_value,desired_gross_income_retirement:desired_gross_income_retirement,desired_retirement_age:desired_retirement_age,COLA:COLA,cola_age:cola_age,assumed_return:assumed_return,RIPG:RIPG,_token:csrfToken},
				dataType: 'json',
				success: function(response) {
					if(response.message == 'success')
					{
						//window.location.href= "{{ route('income-sources') }}";
						window.location.href= "{{ route('current-financial-account') }}";
					}
				},
				error: function(xhr) {
					// Handle validation errors
					
				}
			});
		}
	});
 });
 $(document).on('click','.back-page', function(){
	
	var pricingPlansUrl = "{{ route('pricing-plans') }}";
	window.location.href = pricingPlansUrl;
 });
 
</script>
@endsection

