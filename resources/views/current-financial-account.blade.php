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
									<h3 class="page-title SofiaPro-SemiBold">Current Financial Accounts</h3>
								</div>
							</div>
						</div>
						<!-- /Page Header -->
					
						<!-- Input Rows -->
						<div class="addMoreformContainer" id="formContainer">
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="">
										<label for="" class="col-form-label">Account owner</label>
										<select class="select" name="account_owner[]">
											<option value="">Select</option>
											<option value="1">Husband</option>
											<option value="2">Wife</option>
											<option value="2">Joint</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Account title</label>
										<div class="input-dollar">
										<span class="currency-symbol"></span>
										<input class="form-control" name="account_title[]" type="text" placeholder="Account title">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="">
										<label for="" class="col-form-label">Tax qualification</label>
										<select class="select" name="tax_qualification[]">
											<option value="">Select</option>
											<option value="1">IRA</option>
											<option value="2">Non-Qualified</option>
										</select>
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="input-block">
										<label for="" class="col-form-label">Amount value</label>
										<input class="form-control" name="account_value[]" type="text" placeholder="Amount value">
									</div>
								</div>
								{{--<div class="col-lg-2 col-md-2">
									<div class="input-block">
										<label for="" class="col-form-label">Start Age</label>
										<input class="form-control" name="start_age[]" type="text" placeholder="Start Age">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="input-block">
										<label for="" class="col-form-label">End Age</label>
										<input class="form-control" name="end_age[]" type="text" placeholder="End Age">
									</div>
								</div>--}}
								<div class="col-lg-2 col-md-2">
									<div class="add-more-row-icon">
										<a href="javascript:void(0)" class="" onclick="deleteRow(this)"><i class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						</div>

						<!-- Add Button -->
						<button class="btn btn-primary add-btn" onclick="addRow()">+ ADD</button>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<a href="{{route('portfolio-desires')}}"><button class="btn btn-primary common-button"><i class="fa fa-arrow-left"></i> Previous</button></a>
								<button class="btn btn-primary common-button">Next <i class="fa fa-arrow-right"></i></button>
									{{--<a href="{{route('roth-calculator')}}"><button class="btn btn-primary common-button">Next <i class="fa fa-arrow-right"></i></button></a>--}}
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
							<div class="triangle-up active">
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
							<h5 class="step-title">Income Sources</h5>
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
  function addRow() {
    const container = document.getElementById('formContainer');
    const row = document.createElement('div');
    // check if container has content (ignoring whitespace)
	if (container.innerHTML.trim() !== "") {
		row.className = 'row add-more-seperator';
	} else {
		row.className = 'row';
	}
    row.innerHTML = `
		
		<div class="col-lg-3 col-md-3">
			<div class="">
				<label for="" class="col-form-label">Account owner</label>
				<select class="select" name="account_owner[]">
					<option>Select</option>
					<option value="1">Husband</option>
					<option value="2">Wife</option>
					<option value="2">Joint</option>
				</select>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Account title</label>
				<div class="input-dollar">
				<span class="currency-symbol"></span>
				<input class="form-control" name="account_title[]" type="text" placeholder="Account title">
				</div>
			</div>
		</div>
		<div class="col-lg-2 col-md-2">
			<div class="">
				<label for="" class="col-form-label">Tax qualification</label>
				<select class="select" name="tax_qualification[]">
					<option>Select</option>
					<option value="1">IRA</option>
					<option value="2">Non-Qualified</option>
				</select>
			</div>
		</div>
		<div class="col-lg-2 col-md-2">
			<div class="input-block">
				<label for="" class="col-form-label">Acount value</label>
				<input class="form-control" name="account_value[]" type="text" placeholder="Amount value">
			</div>
		</div>
		<div class="col-lg-2 col-md-2">
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
		var account_owner_arr = [];
		var account_title_arr = [];
		var tax_qualification_arr = [];
		var account_value_arr = [];
		
		
		var allClientBlank = true;
		$('select[name="account_owner[]"]').each(function() {
            if ($(this).val().trim() !== '') {
				account_owner_arr.push($(this).val().trim());
                allClientBlank = false;
            }
			
        });
		
		$('input[name="account_title[]"]').each(function() {
            //if ($(this).val().trim() !== '') {
				account_title_arr.push($(this).val().trim());
                allClientBlank = false;
            //}
			
        });
		
		$('select[name="tax_qualification[]"]').each(function() {
            //if ($(this).val().trim() !== '') {
				tax_qualification_arr.push($(this).val().trim());
                allClientBlank = false;
            //}
			
        });
		
		$('input[name="account_value[]"]').each(function() {
            //if ($(this).val().trim() !== '') {
				account_value_arr.push($(this).val().trim());
                allClientBlank = false;
            //}
			
        });
		
		//alert(account_owner_arr);
		//alert(account_value_arr);
		
		var URL = "{{ route('current-financial-account') }}";
		
		$.ajax({
				url: URL,
				type: "POST",
				data: {account_owner_arr:account_owner_arr,account_title_arr:account_title_arr,tax_qualification_arr:tax_qualification_arr,account_value_arr:account_value_arr,_token:csrfToken},
				dataType: 'json',
				success: function(response) {
					if(response.message == 'success')
					{
						//window.location.href= "{{ route('income-sources') }}";
						//window.location.href= "{{ route('roth-calculator') }}";
					}
				},
				error: function(xhr) {
					// Handle validation errors
					
				}
			});
	});
});
</script>
@endsection

