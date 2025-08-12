@extends('admin.layouts.app')
@section('content')
@php 
//echo "<pre>";print_r($yearly_plan_arr);die;
@endphp
<!-- Page Wrapper -->
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-12 offset-md-0">
			<form id="frmPricingPlans" action="{{ route('admin.pricing-plans-edit-save') }}" method="post">
			@csrf
				<h4 class="page-title">Pricing plans</h4>
				<div class="row col-md-12 offset-md-4">
					<h3><span id="plans_error" class="error-title" style="display:none"></span></h3>
				</div>
				<div class="row mt-4">
					<!-- Column 1 -->
					<div class="col-md-6">
					<h4 class="">Monthly billing</h4>
						@for($i=0;$i<4;$i++)
						<div class="input-block mb-3">
							<label class="col-form-label"></label>
							<input class="form-control monthly_billing_input" @error("monthly_billing.$i") is-invalid @enderror" type="text" name="monthly_billing[]" value="{{ old('monthly_billing.' . $i, $monthly_plan_arr[$i] ?? '') }}">
						</div>
						@error("monthly_billing.$i")
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
						@endfor
					</div>

					<!-- Column 2 -->
					<div class="col-md-6">
					<h4 class="">Annual billing</h4>
						@for($j=0;$j<5;$j++)
						<div class="input-block mb-3">
							<label class="col-form-label"></label>
							<input class="form-control annual_billing_input" @error("annual_billing.$j") is-invalid @enderror" type="text" name="annual_billing[]" value="{{ old('annual_billing.' . $j, $yearly_plan_arr[$j] ?? '') }}">
						</div>
						@error("annual_billing.$j")
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
						@endfor
					</div>
				</div>

				
				<div class="submit-section mt-3">
					<button class="btn btn-primary save-update" type="button">Save & Update</button>
				</div> 
			
			</form>
			</div>

        </div>
    </div>
    <!-- /Page Content -->
</div>
	<!-- /Page Content -->
<!-- update Success message -->
<div class="modal custom-modal fade" id="updt_success_msg" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="success-message text-center">
					<div class="success-popup-icon">
						<i class="la la-pencil"></i>
					</div>
					<h3>Data updated successfully!!!</h3>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection 
@section('scripts')
<script>
//var csrfToken = "{{ csrf_token() }}";
$( document ).ready(function() {
	$(document).on('click','.save-update', function(e){
		 e.preventDefault();
		let allMBlank = true;
		let allYBlank = true;
		$('.monthly_billing_input').each(function() {
            if ($(this).val().trim() !== '') {
                allMBlank = false;
            }
        });
		
		$('.annual_billing_input').each(function() {
            if ($(this).val().trim() !== '') {
                allYBlank = false;
            }
        });
		
		if (allMBlank) {
            $('#plans_error').text('Please enter monthly billing plan').fadeIn().delay(2000).fadeOut();
			return false;
        }
		else if (allYBlank) {
			$('#plans_error').text('Please enter yearly billing plan').fadeIn().delay(2000).fadeOut();
			return false;
		}
		else {
             $('#frmPricingPlans').submit();
        }
	});
});
</script>
@endsection
