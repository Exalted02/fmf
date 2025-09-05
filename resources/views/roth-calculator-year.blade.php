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
									<h3 class="page-title SofiaPro-SemiBold">Roth Year</h3>
								</div>
							</div>
						</div>
						{{--<div class="row mt-3">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">RMD: Required Minimum Distribution</h3>
							</div>
						</div>--}}
						
						<div class="row">
							<div class="col-lg-4 col-md-4">
								<div class="input-block">
									<label for="" class="col-form-label">Year</label>
									<select class="select" id="year" name="year">
										<option value="">Select Year</option>
										<option value="1">year 1</option>
										<option value="2">year 2</option>
										<option value="3">year 3</option>
										<option value="4">year 4</option>
										<option value="5">year 5</option>
										<option value="6">year 6</option>
										<option value="7">year 7</option>
										<option value="8">year 8</option>
										<option value="9">year 9</option>
									</select>
									<div class="rmd_start_age_error error-text"></div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4">
								<div class="input-block">
									<label for="" class="col-form-label">RMD Age's</label>
									<input type="checkbox" name="rmd_age" name="rmd_age">
									<div class="rmd-age-error-text"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<button class="btn btn-primary common-button" onclick="goBackAndReload()"><i class="fa fa-arrow-left"></i> Previous</button>
								<a href="javascript:void(0)"><button class="btn btn-primary common-button save-roth-calculator">Next <i class="fa fa-arrow-right"></i></button></a>
							</div>
						</div>
					</div>
				</div>			
				<div class="col-md-2 order-1 order-sm-2">
					<div class="step-section">
						<div class="triangle-container">
							<div class="triangle-up complete">
								<span class="triangle-number">1</span>
							</div>
							<div class="triangle-up complete">
								<span class="triangle-number">2</span>
							</div>
							<div class="triangle-up complete">
								<span class="triangle-number">3</span>
							</div>
							<div class="triangle-up complete">
								<span class="triangle-number">4</span>
							</div>
							<div class="triangle-up active">
								<span class="triangle-number">5</span>
							</div>
						</div>

						<div class="step-content mt-3">
							<h5 class="step-title">Income Allocation Solution</h5>
							<p class="step-description">
								Use the Income Allocation Tool to examine a client's current financial situation and income sources in retirement to help effectively put together a financial plan.
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
	$('.save-roth-calculator').on('click', function(e){
		e.preventDefault();
		var conversion_start_age = $('#conversion_start_age').val();
		var conversion_finish_age = $('#conversion_finish_age').val();
		var conversion_annual_fee = $('#conversion_annual_fee').val();
		var rmd_start_age = $('#rmd_start_age').val();
		var rmd_finish_age = $('#rmd_finish_age').val();
		var rmd_tax_free_income = $('#rmd_tax_free_income').val();
		
		var investment_amount_arr = [];
		var bonus_arr = [];
		var assumed_return_arr = [];
		
		$('.conversion_start_age_error').text('');
		$('.conversion_finish_age_error').text('');
		$('.conversion_annual_fee_error').text('');
		$('.rmd_start_age_error').text('');
		$('.rmd_finish_age_error').text('');
		$('.rmd_tax_free_income_error').text('');
		
		let isValid = true;
		$('.invalid-feedback').hide();
		$('.form-control').removeClass('is-invalid');
		if (conversion_start_age === '')
		{
			$('.conversion_start_age_error').text('Enter start age');
			isValid = false;
		}
		if (conversion_finish_age === '')
		{
			$('.conversion_finish_age_error').text('Enter finish age');
			isValid = false;
		}
		if (conversion_annual_fee === '')
		{
			$('.conversion_annual_fee_error').text('Enter annual fee');
			isValid = false;
		}
		if (rmd_start_age === '')
		{
			$('.rmd_start_age_error').text('Enter start age');
			isValid = false;
		}
		if (rmd_finish_age === '')
		{
			$('.rmd_finish_age_error').text('Enter finish age');
			isValid = false;
		}
		if (rmd_tax_free_income === '')
		{
			$('.rmd_tax_free_income_error').text('Enter tax free income');
			isValid = false;
		}
		
		
		
		$('input[name="investment_amount[]"]').each(function() {
            investment_amount_arr.push($(this).val().trim());
        });
		
		$('input[name="bonus[]"]').each(function() {
            bonus_arr.push($(this).val().trim());
           
        });
		
		$('input[name="assumed_return[]"]').each(function() {
            assumed_return_arr.push($(this).val().trim());
           
        });
		
		if(isValid)
		{
			var URL = "{{ route('roth-calculator') }}";
			
			$.ajax({
				url: URL,
				type: "POST",
				data: {conversion_start_age:conversion_start_age,conversion_finish_age:conversion_finish_age,conversion_annual_fee:conversion_annual_fee,rmd_start_age:rmd_start_age,rmd_finish_age:rmd_finish_age,rmd_tax_free_income:rmd_tax_free_income,investment_amount_arr:investment_amount_arr,bonus_arr:bonus_arr,assumed_return_arr:assumed_return_arr,_token:csrfToken},
				dataType: 'json',
				success: function(response) {
					if(response.message == 'success')
					{
						window.location.href= "{{ route('roth-calculator-year') }}";
						//window.location.href= "{{ route('portfolio-desires') }}";
					}
				},
				error: function(xhr) {
					// Handle validation errors
					
				}
			});
			
		}
	});
	
});
</script>
@endsection

