@extends('layouts.app')
@section('content')
@php
$portfolio_data = App\Models\Client_portfolio_Desires::where('id', $sl_no)->first();
$partner_name = $portfolio_data ? $portfolio_data->partner_name : '';
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
									<h3 class="page-title SofiaPro-SemiBold">Current Financial Accounts</h3>
								</div>
							</div>
						</div>
						<!-- /Page Header -->
					
						<!-- Input Rows -->
						<div class="addMoreformContainer" id="formContainer">
						@if($records->isEmpty())
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="">
										<label for="" class="col-form-label">Account owner</label>
										@if(!empty($partner_name))
										<select class="form-control select" name="account_owner[]">
											<option value="">Select</option>
											<option value="1">Husband</option>
											<option value="2">Wife</option>
											<option value="3">Joint</option>
										</select>
										@else
										<select class="form-control select" name="account_owner[]">
											<option value="1">Husband</option>
										</select>
										@endif
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Owner name</label>
										<div class="input-dollar-s">
										<span class="currency-symbol"></span>
										<input class="form-control" name="owner_name[]" type="text" placeholder="Owner name">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Account title</label>
										<div class="input-dollar-s">
										<span class="currency-symbol"></span>
										<input class="form-control" name="account_title[]" type="text" placeholder="Account title">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">RMD start age</label>
										<input class="form-control" name="rmd_start_age[]" type="text" placeholder="RMD start age" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
										
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="">
										<label for="" class="col-form-label">Tax qualification</label>
										<select class="select" name="tax_qualification[]">
											<option value="">Select</option>
											<option value="1">IRA</option>
											<option value="2">Non-Qualified</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Amount value</label>
										<input class="form-control" name="account_value[]" type="text" placeholder="Amount value" onkeypress="return isNumberKey(event,this)">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Age income start</label>
										<input class="form-control" name="age_income_start[]" type="text" placeholder="Age income start" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
										
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Annual income value</label>
										<input class="form-control" name="annual_income_value[]" type="text" placeholder="Annual income value" onkeypress="return isNumberKey(event,this)">
									</div>
								</div>
								
								<div class="col-lg-1 col-md-1">
									<div class="add-more-row-icon">
										<a href="javascript:void(0)" class="" onclick="deleteRow(this, '')"><i class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						@endif
						@foreach($records as $record)
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="">
										<label for="" class="col-form-label">Account owner</label>
										<select class="form-control select" name="account_owner[]">
											<option value="">Select</option>
											<option value="1" {{ !empty($record->account_owner) && $record->account_owner == 1 ? 'selected' : ''}}>Husband</option>
											<option value="2" {{ !empty($record->account_owner) && $record->account_owner == 2 ? 'selected' : ''}}>Wife</option>
											<option value="3" {{ !empty($record->account_owner) && $record->account_owner == 3 ? 'selected' : ''}}>Joint</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Owner name</label>
										<div class="input-dollar-s">
										<span class="currency-symbol"></span>
										<input class="form-control" name="owner_name[]" value="{{ $record->owner_name ?? ''}}" type="text" placeholder="Owner name">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Account title</label>
										<div class="input-dollar-s">
										<span class="currency-symbol"></span>
										<input class="form-control" name="account_title[]" type="text" placeholder="Account title" value="{{ $record->account_title ?? ''}}">
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">RMD start age</label>
										<input class="form-control" name="rmd_start_age[]" type="text" placeholder="RMD start age"  value="{{ $record->rmd_start_age ?? ''}}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
										
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="">
										<label for="" class="col-form-label">Tax qualification</label>
										<select class="select" name="tax_qualification[]">
											<option value="">Select</option>
											<option value="1" {{ !empty($record->tax_qualification) && $record->tax_qualification == 1 ? 'selected' : ''}}>IRA</option>
											<option value="2" {{ !empty($record->tax_qualification) && $record->tax_qualification == 2 ? 'selected' : ''}}>Non-Qualified</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Amount value</label>
										<input class="form-control" name="account_value[]" type="text" placeholder="Amount value" value="{{ $record->account_value ?? ''}}" onkeypress="return isNumberKey(event,this)">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Age income start</label>
										<input class="form-control" name="age_income_start[]" type="text" placeholder="Age income start" value="{{ $record->age_income_start ?? ''}}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
										
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Annual income value</label>
										<input class="form-control" name="annual_income_value[]" type="text" placeholder="Annual income value" onkeypress="return isNumberKey(event,this)" value="{{ $record->annual_income_value ?? ''}}">
									</div>
								</div>
								<div class="col-lg-1 col-md-1">
									<div class="add-more-row-icon">
										<a href="javascript:void(0)" class="" onclick="deleteRow(this , '{{ $record->id ?? '' }}')"><i class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						@endforeach
						</div>

						<!-- Add Button -->
						<button class="btn btn-primary add-btn" onclick="addRow()">+ ADD</button>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<button class="btn btn-primary common-button"  onclick="goBackAndReload()"><i class="fa fa-arrow-left"></i> Previous</button>
								<button class="btn btn-primary common-button save-account">Next <i class="fa fa-arrow-right"></i></button>
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
							<div class="triangle-up">
								<span class="triangle-number">5</span>
							</div>
						</div>

						<div class="step-content mt-3">
							<h5 class="step-title">Current Financial Account</h5>
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
				@if(!empty($partner_name))
				<select class="form-control select" name="account_owner[]">
					<option>Select</option>
					<option value="1">Husband</option>
					<option value="2">Wife</option>
					<option value="3">Joint</option>
				</select>
				@else
				<select class="form-control select" name="account_owner[]">
					<option value="1">Husband</option>
				</select>	
				@endif
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Owner name</label>
				<div class="input-dollar-s">
				<span class="currency-symbol"></span>
				<input class="form-control" name="owner_name[]" type="text" placeholder="Owner name">
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Account title</label>
				<div class="input-dollar-s">
				<span class="currency-symbol"></span>
				<input class="form-control" name="account_title[]" type="text" placeholder="Account title">
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">RMD start age</label>
				<input class="form-control" name="rmd_start_age[]" type="text" placeholder="RMD start age" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
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
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Amount value</label>
				<input class="form-control" name="account_value[]" type="text" placeholder="Amount value" onkeypress="return isNumberKey(event,this)">
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Age income start</label>
				<input class="form-control" name="age_income_start[]" type="text" placeholder="Age income start" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
				
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Annual income value</label>
				<input class="form-control" name="annual_income_value[]" type="text" placeholder="Annual income value" onkeypress="return isNumberKey(event,this)">
			</div>
		</div>
		<div class="col-lg-1 col-md-1">
			<div class="add-more-row-icon">
				<a href="javascript:void(0)" class="" onclick="deleteRow(this, '')"><i class="fa fa-trash"></i></a>
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
	
	
	/*$(row).find('.select2').select2({
        minimumResultsForSearch: 0,
        width: '100%'
    });*/
  }

  function deleteRow(button, id) {
    const row = button.closest('.row');
    row.remove();
	if(id != '')
	{
		$.ajax({
			url: "{{ route('delete-current-financial-account') }}",
			type: "POST",
			data: {id:id,_token:csrfToken},
			dataType: 'json',
			success: function(response) {
			
			},
			error: function(xhr) {
				// Handle validation errors
				
			}
		});
	}
  }
  
</script>
<script>
$(document).ready(function(){
	$('.save-account').on('click', function(e){
		e.preventDefault();
		var account_owner_arr = [];
		var owner_name_arr = [];
		var account_title_arr = [];
		var rmd_start_age_arr = [];
		var tax_qualification_arr = [];
		var age_income_start_arr = [];
		var account_value_arr = [];
		var annual_income_value_arr = [];
		
		
		let isValid = true;
		$('select[name="account_owner[]"]').each(function() {
			const $sel = $(this);
			//let $container = $(this).data('select2').$container;
			//let $container = $(this).siblings('.select2-container');
			const $container =($sel.data('select2') && $sel.data('select2').$container) || $sel.siblings('.select2-container') || $sel.next('.select2');  
			
            if ($(this).val() === "" || $(this).val() === null) {
				//$(this).next('.select2').addClass('is-invalid');
				//$(this).siblings('.select2').addClass('is-invalid');
				//$container.addClass('is-invalid');
				if ($container && $container.length) $container.addClass('is-invalid');
				isValid = false;
			} else {
				//$(this).next('.select2').removeClass('is-invalid');
				//$container.removeClass('is-invalid');
				 if ($container && $container.length) $container.removeClass('is-invalid');
				account_owner_arr.push($(this).val().trim());
			}
       });
	   
	   //alert(account_owner_arr);
	   $('input[name="owner_name[]"]').each(function() {
            if ($(this).val() === "" || $(this).val() === null) {
				$(this).addClass('is-invalid');
                isValid = false;
            } else {
				$(this).removeClass('is-invalid');
				owner_name_arr.push($(this).val().trim());
			}
			
        });
		
		$('input[name="account_title[]"]').each(function() {
            if ($(this).val() === "" || $(this).val() === null) {
				$(this).addClass('is-invalid');
                isValid = false;
            } else {
				$(this).removeClass('is-invalid');
				account_title_arr.push($(this).val().trim());
			}
			
        });
		
		$('input[name="rmd_start_age[]"]').each(function() {
            if ($(this).val() === "" || $(this).val() === null) {
				$(this).addClass('is-invalid');
                isValid = false;
            } else {
				$(this).removeClass('is-invalid');
				rmd_start_age_arr.push($(this).val().trim());
			}
			
        });
		
		$('select[name="tax_qualification[]"]').each(function() {
            if ($(this).val() === "" || $(this).val() === null) {
				//$(this).addClass('is-invalid');
				$(this).next('.select2').addClass('is-invalid');
                isValid = false;
            } else {
				//$(this).removeClass('is-invalid');
				$(this).next('.select2').removeClass('is-invalid');
				tax_qualification_arr.push($(this).val().trim());
			}
			
        });
		
		$('input[name="age_income_start[]"]').each(function() {
            if ($(this).val() === "" || $(this).val() === null) {
				$(this).addClass('is-invalid');
                isValid = false;
            } else {
				$(this).removeClass('is-invalid');
				age_income_start_arr.push($(this).val().trim());
			}
			
        });
		
		$('input[name="account_value[]"]').each(function() {
            if ($(this).val() === "" || $(this).val() === null) {
				$(this).addClass('is-invalid');
                isValid = false;
            } else {
				$(this).removeClass('is-invalid');
				account_value_arr.push($(this).val().trim());
			}
			
        });
		
		$('input[name="annual_income_value[]"]').each(function() {
            if ($(this).val() === "" || $(this).val() === null) {
				$(this).addClass('is-invalid');
                isValid = false;
            } else {
				$(this).removeClass('is-invalid');
				annual_income_value_arr.push($(this).val().trim());
			}
			
        });
		if (!isValid) {
			return false;
		}
		
		var URL = "{{ route('current-financial-account') }}";
		
		$.ajax({
				url: URL,
				type: "POST",
				data: {account_owner_arr:account_owner_arr,owner_name_arr:owner_name_arr,account_title_arr:account_title_arr,rmd_start_age_arr:rmd_start_age_arr,tax_qualification_arr:tax_qualification_arr,age_income_start_arr:age_income_start_arr,account_value_arr:account_value_arr,annual_income_value_arr:annual_income_value_arr,_token:csrfToken},
				dataType: 'json',
				success: function(response) {
					if(response.message == 'success')
					{
						window.location.href= "{{ route('income-sources') }}";
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

