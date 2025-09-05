@extends('layouts.app')
@section('content')
@php 
//echo "<pre>";print_r($records);die;

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
									<label for="year" class="col-form-label">Year</label>
									<select class="select form-control" id="year" name="year">
										<option value="">Select Year</option>
										@for($i=1;$i<=9;$i++)
										<option value="{{ $i }}" {{ isset($records->year) && $records->year == $i ? 'selected' : '' }}>year {{ $i}}</option>
										@endfor
									</select>
									<div class="year_error error-text"></div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4">
								<div class="input-block">
									<label class="col-form-label">RMD Age's</label>
									<div class="form-check">
										<input type="checkbox" class="form-check-input" id="rmd_age" name="rmd_age" {{ isset($records->rmd_age ) && $records->rmd_age == 1 ? 'checked' : '' }}>
										<label class="form-check-label" for="rmd_age">73/75</label>
									</div>
									<div class="rmd_age_error error-text"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<button class="btn btn-primary common-button" onclick="goBackAndReload()"><i class="fa fa-arrow-left"></i> Previous</button>
								<a href="javascript:void(0)"><button class="btn btn-primary common-button save-roth-calculator-year">Next <i class="fa fa-arrow-right"></i></button></a>
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
	$('.save-roth-calculator-year').on('click', function(e){
		e.preventDefault();
		var year = $('#year').val();
		
		const rmd_age = $("#rmd_age").is(":checked");
		
		$('.year_error').text('');
		$('.rmd_age_error').text('');
		
		
		let isValid = true;
		$('.invalid-feedback').hide();
		$('.form-control').removeClass('is-invalid');
		if (year === '')
		{
			$('.year_error').text('Select year');
			isValid = false;
		}
		
		if(isValid)
		{
			var URL = "{{ route('roth-calculator-year') }}";
			
			$.ajax({
				url: URL,
				type: "POST",
				data: {year:year,rmd_age:rmd_age,_token:csrfToken},
				dataType: 'json',
				success: function(response) {
					if(response.message == 'success')
					{
						//window.location.href= "{{ route('roth-calculator-year') }}";
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

