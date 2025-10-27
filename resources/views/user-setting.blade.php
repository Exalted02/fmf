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
									<h3 class="page-title SofiaPro-SemiBold">Settings</h3>
								</div>
							</div>
						</div>
					<form name="frmsettings" action="{{ route('user-settings')}}" method="post">
						<div class="row">
							<div class="col-lg-8 col-md-8">
								<div class="input-block">
									<label for="year" class="col-form-label">Advosor text</label>
									<input type="text" class="form-control" name="advisor_text">
									<div class="year_error error-text"></div>
								</div>
							</div>
							<div class="col-lg-8 col-md-8">
								<div class="input-block">
									<label for="year" class="col-form-label">Advosor logo</label>
									<input type="file" class="form-control" name="advisor_logo">
									<div class="wife_roth_year_error error-text"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<a href="javascript:void(0)"><button class="btn btn-primary common-button save-roth-calculator-year">Save</button></a>
							</div>
						</div>
					</div>
					</form>
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
		var wife_roth_year = $('#wife_roth_year').val();
		var show_specific_year = $('#show_specific_year').val();
		
		const rmd_age = $("#rmd_age").is(":checked");
		
		$('.year_error').text('');
		$('.wife_roth_year_error').text('');
		$('.rmd_age_error').text('');
		
		
		let isValid = true;
		$('.invalid-feedback').hide();
		$('.form-control').removeClass('is-invalid');
		if (year === '')
		{
			$('.year_error').text('Select husband year');
			isValid = false;
		}
		
		if (wife_roth_year === '')
		{
			$('.wife_roth_year_error').text('Select wife year');
			isValid = false;
		}
		
		/*if(show_specific_year === '')
		{
			$('.show_specific_year_error').text('This field required');
			isValid = false;
		}*/
		
		
		if(isValid)
		{
			var URL = "{{ route('roth-calculator-year') }}";
			
			$.ajax({
				url: URL,
				type: "POST",
				data: {year:year,wife_roth_year:wife_roth_year,show_specific_year:show_specific_year,rmd_age:rmd_age,_token:csrfToken},
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
document.getElementById("show_specific_year").addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9;]/g, ''); 
});
</script>
@endsection

