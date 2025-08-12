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
									<h3 class="page-title SofiaPro-SemiBold">Roth Conversion Calculator</h3>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">IRS 5-Year Rule</h3>
							</div>
						</div>
						<!-- /Page Header -->
					
						<!-- Input Rows -->
						<div class="addMoreformContainer" id="formContainer">
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Investment Amount $</label>
										<div class="input-dollar">
										<span class="currency-symbol">$</span>
										<input class="form-control" name="investment_amount[]" type="text" placeholder="Investment Amount">
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Bonus %</label>
										<div class="input-percent">
										<input class="form-control" name="bonus[]" type="text" placeholder="Bonus">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Assumed Return</label>
										<div class="input-percent">
										<input class="form-control" name="assumed_return[]" type="text" placeholder="Assumed Return">
										</div>
									</div>
								</div>
								<div class="col-lg-1 col-md-1">
									<div class="add-more-row-icon">
										<a href="javascript:void(0)" class="" onclick="deleteRow(this)"><i class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						</div>
						<!-- Add Button -->
						<div class="row">
							<div class="col-sm-12">
								<button class="btn btn-primary add-btn mt-2" onclick="addRow()">+ ADD</button>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">Conversion Timeline</h3>
							</div>
						</div>
						<div class="addMoreformContainer">
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Start Age</label>
										<input class="form-control" name="conversion_start_age" id="conversion_start_age" type="text" placeholder="Start Age">
										<div class="conversion_start_age_error error-text"></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Finish Age</label>
										<input class="form-control" name="conversion_finish_age" id="conversion_finish_age" type="text" placeholder="Finish Age">
										<div class="conversion_finish_age_error error-text"></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Annual Fee</label>
										<div class="input-percent">
										<input class="form-control" name="conversion_annual_fee" id="conversion_annual_fee" type="text" placeholder="Annual Fee">
										</div>
										<div class="conversion_annual_fee_error error-text"></div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row mt-3">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">RMD: Required Minimum Distribution</h3>
							</div>
						</div>
						<div class="addMoreformContainer">
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Start Age</label>
										<input class="form-control" name="rmd_start_age" id="rmd_start_age" type="text" placeholder="Start Age">
										<div class="rmd_start_age_error error-text"></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Finish Age</label>
										<input class="form-control" name="rmd_finish_age" id="rmd_finish_age" type="text" placeholder="Finish Age">
										<div class="rmd_finish_age_error error-text"></div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Age to Begin TAX FREE INCOME</label>
										<input class="form-control" name="rmd_tax_free_income" id="rmd_tax_free_income" type="text" placeholder="Age to Begin TAX FREE INCOME">
										<div class="rmd_tax_free_income_error error-text"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<button class="btn btn-primary add-btn mt-2">Calculate</button>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<a href="{{route('income-sources')}}"><button class="btn btn-primary common-button"><i class="fa fa-arrow-left"></i> Previous</button></a>
								<a href="javascript:void(0)"><button class="btn btn-primary common-button">Next <i class="fa fa-arrow-right"></i></button></a>
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
							<div class="triangle-up active">
								<span class="triangle-number">3</span>
							</div>
							<div class="triangle-up">
								<span class="triangle-number">4</span>
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
  function addRow() {
    const container = document.getElementById('formContainer');
    const row = document.createElement('div');
    row.className = 'row add-more-seperator';
    row.innerHTML = `
		<div class="col-lg-4 col-md-4">
			<div class="input-block">
				<label for="" class="col-form-label">Investment Amount $</label>
				<div class="input-dollar">
				<span class="currency-symbol">$</span>
				<input class="form-control" type="text" name="investment_amount[]" placeholder="Investment Amount">
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4">
			<div class="input-block">
				<label for="" class="col-form-label">Bonus %</label>
				<div class="input-percent">
				<input class="form-control" type="text" name="bonus[]" placeholder="Bonus">
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Assumed Return</label>
				<div class="input-percent">
				<input class="form-control" type="text" name="assumed_return[]" placeholder="Assumed Return">
				</div>
			</div>
		</div>
		<div class="col-lg-1 col-md-1">
			<div class="add-more-row-icon">
				<a href="javascript:void(0)" class="" onclick="deleteRow(this)"><i class="fa fa-trash"></i></a>
			</div>
		</div>
    `;
	setTimeout(function () {
		$('.select');
		setTimeout(function () {
			$('.select').select2({
				minimumResultsForSearch: 0,
				width: '100%'
			});
		}, 100);
	}, 100);
    container.appendChild(row);
  }

  function deleteRow(button) {
    const row = button.closest('.row');
    row.remove();
  }
</script>
<script>
$(document).ready(function(){
	$('.common-button').on('click', function(e){
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
						window.location.href= "{{ route('portfolio-desires') }}";
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

