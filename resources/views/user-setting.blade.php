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
					
					@if($message = Session::get('success'))
                           <div class="text-success">
                             <p>{{$message}}</p>
                          </div>
					@endif
					<form name="frmsettings" action="{{ route('user-settings')}}" method="post" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id" value="{{ isset($setting) ? $setting->id : ''}}">
					<input type="hidden" name="hid_logo" value="{{ isset($setting) ? $setting->advisor_logo : ''}}">
						<div class="row">
							<div class="col-lg-8 col-md-8">
								<div class="input-block">
									<label for="year" class="col-form-label">Advosor text</label>
									<input type="text" class="form-control" name="advisor_text"  value="{{ isset($setting) ? $setting->advisor_text : old('advisor_text')}}">
									@error('advisor_text')
									 <div class="text-danger">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							<div class="col-lg-8 col-md-8">
								<div class="input-block mb-3">
									<label for="advisor_logo" class="col-form-label fw-bold">Advisor Logo</label>
									<div class="custom-file-upload position-relative text-center p-3 border rounded">
										<input type="file" class="form-control d-none" name="advisor_logo" id="advisor_logo" accept="image/*">
										<label for="advisor_logo" class="btn btn-outline-primary">
											<i class="fa-solid fa-upload me-2"></i> Choose Logo
										</label>
									</div>
									@error('advisor_logo')
										<div class="text-danger mt-1">{{ $message }}</div>
									@enderror
								</div>
							</div>
							
							<div class="col-lg-8 col-md-8">
								<div class="input-block  position-relative">
									<img 
										id="preview-image" 
										src="{{ !empty($setting->advisor_logo) ? url('uploads/advisor_logo/' . $setting->advisor_logo) : '' }}" 
										alt="Advisor Logo" 
										class="img-thumbnail {{ empty($setting->advisor_logo) ? 'd-none' : '' }}" 
										style="max-height: 200px; max-width: 200px;"
									>
									 <button 
										type="button" 
										id="remove-preview" 
										class="btn btn-danger btn-sm position-absolute top-0 end-0 translate-middle {{ empty($setting->advisor_logo) ? 'd-none' : '' }}"
										style="border-radius: 50%; margin-right: 349px; padding: 0px 7px; display: {{ isset($setting->advisor_logo) ? 'none' : 'block'}}"
									 data-id="{{ isset($setting) ? $setting->id : ''}}">
										<i class="fa-solid fa-xmark"></i>
									</button>
								</div>
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<a href="javascript:void(0)"><button type="submit" class="btn btn-primary common-button">Save</button></a>
							</div>
						</div>
					</div>
					</form>
				</div>			
				{{--<div class="col-md-2 order-1 order-sm-2">
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
				</div>--}}			
			</div>			
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

@endsection 
@section('scripts')

<script>
$(document).ready(function(){
	
	document.getElementById('advisor_logo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewImage = document.getElementById('preview-image');
    const removeButton = document.getElementById('remove-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.classList.remove('d-none');
            removeButton.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
	
	document.getElementById('remove-preview').addEventListener('click', function() {
		const previewImage = document.getElementById('preview-image');
		const input = document.getElementById('advisor_logo');
		
		input.value = '';
		previewImage.src = '';
		previewImage.classList.add('d-none');
		this.classList.add('d-none');
		
		let id = $(this).data('id');
		//alert(id);
		/*if(id != '')
		{
			$.ajax({
				url: "",
				type: "POST",
				data: {id:id,_token:csrfToken},
				dataType: 'json',
				success: function(response) {
					
				},
				error: function(xhr) {
					// Handle validation errors
					
				}
			});
			
		}*/
	});
	
});

</script>
@endsection

