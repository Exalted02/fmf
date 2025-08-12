@extends('layouts.app')
@section('content')
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
					<form name="frmPortfolioDesires" method="post" action="{{ route('portfolio-desires') }}">
						<div class="row">
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Client's Name</label>
									<input type="text" class="form-control" placeholder="Enter Client's Name" name="client_name" id="client_name">
									<div class="invalid-feedback">Enter client's name</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Client's Age</label>
									<input type="text" class="form-control"  name="client_age" id="client_age" placeholder="Enter Client's Age">
									<div class="invalid-feedback">Enter client's age</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Partner's Name</label>
									<input type="text" class="form-control"   name="partner_name" id="partner_name" placeholder="Enter Partner's Name">
									<div class="invalid-feedback">Enter partner's name</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Partner's Age</label>
									<input type="text" class="form-control"   name="partner_age" id="partner_age" placeholder="Enter Partner's Age">
									<div class="invalid-feedback">Enter partner's age</div>
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
									<input type="text" class="form-control"   name="current_portfolio_value" id="current_portfolio_value" placeholder="Enter Current Portfolio Value">
									<div class="invalid-feedback">Enter current portfolio value</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Desired Gross Income During Retirement</label>
									<div class="input-dollar">
									<input type="text" class="form-control"   name="desired_gross_income_retirement" id="desired_gross_income_retirement" placeholder="Enter Desired Gross Income">
									</div>
									{{--<div class="invalid-feedback">Enter gross income retirement</div>--}}
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Desired Retirement Age</label>
									<input type="text" class="form-control"   name="desired_retirement_age" id="desired_retirement_age" placeholder="Enter Desired Retirement Age">
									<div class="invalid-feedback">Enter desired retirement age</div>
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
									<input type="text" class="form-control"   name="COLA" id="COLA" placeholder="Enter Cost of Living Adjustment (COLA)">
									</div>
									{{--<div class="invalid-feedback">Enter COLA</div>--}}
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Age to Begin COLA Adjustment</label>
									<div class="input-percent">
									<input type="text" class="form-control"  name= "cola_age" id="cola_age" placeholder="Enter Age to Begin COLA Adjustment">
									</div>
									{{--<div class="invalid-feedback">Enter COLA  age adjustment</div>--}}
									
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Assumed Return</label>
									<div class="input-percent">
									<input type="text" class="form-control"   name="assumed_return" id="assumed_return" placeholder="Enter Assumed Return">
									</div>
									{{--<div class="invalid-feedback">Assumed Return</div>--}}
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-box">
									<h3 class="content-heading SofiaPro-SemiBold">Retirement Income Planning Goal</h3>
									<div class="d-flex justify-between">												
										<label class="checkbox-inline"><input type="checkbox" name="RIPG[]" value="0" class="form-check-input"><span class="checkmark SofiaPro-SemiBold">Income</span></label>

										<label class="checkbox-inline"><input type="checkbox" name="RIPG[]" value="1" class="form-check-input"><span class="checkmark SofiaPro-SemiBold">Tax Reduction</span></label>
									
										<label class="checkbox-inline"><input type="checkbox" name="RIPG[]" value="2" class="form-check-input"><span class="checkmark SofiaPro-SemiBold">Legacy</span></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<button class="btn btn-primary common-button">Next <i class="fa fa-arrow-right"></i></button>
								{{--<a href="{{route('income-sources')}}"><button class="btn btn-primary common-button">Next <i class="fa fa-arrow-right"></i></button></a>--}}
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
	$('.common-button').on('click', function(e){
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
		var RIPG = $('#RIPG').val();
		
		let isValid = true;
		$('.invalid-feedback').hide();
		$('.form-control').removeClass('is-invalid');
		if (client_name === '')
		{
			$('#client_name').addClass('is-invalid');
			$('#client_name').next('.invalid-feedback').show();
			isValid = false;
		}
		if (client_age === '')
		{
			$('#client_age').addClass('is-invalid');
			$('#client_age').next('.invalid-feedback').show();
			isValid = false;
		}
		if (partner_name === '')
		{
			$('#partner_name').addClass('is-invalid');
			$('#partner_name').next('.invalid-feedback').show();
			isValid = false;
		}
		if (partner_age === '')
		{
			$('#partner_age').addClass('is-invalid');
			$('#partner_age').next('.invalid-feedback').show();
			isValid = false;
		}
		if (current_portfolio_value === '')
		{
			$('#current_portfolio_value').addClass('is-invalid');
			$('#current_portfolio_value').next('.invalid-feedback').show();
			isValid = false;
		}
		if (desired_gross_income_retirement === '')
		{
			$('#desired_gross_income_retirement').addClass('is-invalid');
			$('#desired_gross_income_retirement').next('.invalid-feedback').show();
			isValid = false;
		}
		if (desired_retirement_age === '')
		{
			$('#desired_retirement_age').addClass('is-invalid');
			$('#desired_retirement_age').next('.invalid-feedback').show();
			isValid = false;
		}
		if (COLA === '')
		{
			$('#COLA').addClass('is-invalid');
			$('#COLA').next('.invalid-feedback').show();
			isValid = false;
		}
		if (cola_age === '')
		{
			$('#cola_age').addClass('is-invalid');
			$('#cola_age').next('.invalid-feedback').show();
			isValid = false;
		}
		if (assumed_return === '')
		{
			$('#assumed_return').addClass('is-invalid');
			$('#assumed_return').next('.invalid-feedback').show();
			isValid = false;
		}
		if (RIPG === '')
		{
			$('#RIPG').addClass('is-invalid');
			$('#RIPG').next('.invalid-feedback').show();
			isValid = false;
		}
	});
 });
</script>
@endsection

