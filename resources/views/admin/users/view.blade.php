@extends('admin.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/summernote/summernote-bs4.min.css') }}">
@endsection 
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col-md-4">
					<h3 class="page-title">User</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
						<li class="breadcrumb-item active">View</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-xl-12">
				<div class="accordion custom-accordion" id="custom-accordion-one">
				@foreach($user->get_client_portfolio as $key=>$val)
					<div class="card mb-1">
						<div class="card-header" id="heading-{{ $key }}">
							<h5 class="accordion-faq m-0 position-relative">
								<a class="custom-accordion-title text-reset d-block"
									data-bs-toggle="collapse" href="#collapse-{{ $key }}"
									aria-expanded="true" aria-controls="collapseNine">
									#. <strong>Client name</strong>:- {{$val->client_name}} &nbsp; <strong>Client age</strong>:- {{ $val->client_age}}&nbsp; <strong>Partner name</strong>:- {{ $val->partner_name}} &nbsp; <strong>Partner age</strong>:- {{ $val->partner_age }}<i
										class="mdi mdi-chevron-down accordion-arrow"></i>
								</a>
							</h5>
						</div>

						<div id="collapse-{{ $key }}" class="collapse"
							aria-labelledby="heading-{{ $key }}"
							data-bs-parent="#custom-accordion-one">
							<div class="card-body">
								<div class="contact-tab-wrap">
									<ul class="contact-nav nav">
										<li>
											<a href="#" data-bs-toggle="tab" data-bs-target="#product" class="active">
												<i class="fa-solid fa-box"></i>{{ __('product_information') }}
											</a>
										</li>
										<li>
											<a href="#" data-bs-toggle="tab" data-bs-target="#outsource">
												<i class="fa-solid fa-person-shelter"></i>{{ __('outsource_information') }}
											</a>
										</li>
										<li>
											<a href="#" data-bs-toggle="tab" data-bs-target="#referral">
												<i class="fa-solid fa-people-pulling"></i>{{ __('referral_information') }}
											</a>
										</li>
										<li>
											<a href="#" data-bs-toggle="tab" data-bs-target="#follow">
												<i class="fa-solid fa-user-plus"></i>{{ __('follow_up_information') }}
											</a>
										</li>
									</ul>
								</div>
								
								<div class="contact-tab-view">
									<div class="tab-content pt-0">
									
										<!-- Product Information -->
                                    <div class="tab-pane active show" id="product">
                                        
                                                <div class="multiadd d-flex flex-wrap">
                                                    
                                                        <div class="col-md-4 mt-3">
                                                            <strong>{{ __('select_product_code') }}</strong>
                                                            <div>ggggg</div>
                                                        </div>
                                                  
                                                    
                                                        <div class="col-md-4 mt-3">
                                                            <strong>{{ __('select_product_group') }}</strong>
                                                            <div>kkkkkk</div>
                                                        </div>
                                                    
                                                    
                                                        <div class="col-md-4 mt-3">
                                                            <strong>{{ __('select_product_name') }}</strong>
                                                            <div>hhhhhh</div>
                                                        </div>
                                                    
                                                    
                                                        <div class="col-md-4 mt-3">
                                                            <strong>{{ __('sale_price') }}</strong>
                                                            <div>uuuuu</div>
                                                        </div>
                                                    
                                                    
                                                        <div class="col-md-4 mt-3">
                                                            <strong>{{ __('tally_serial_no') }}</strong>
                                                            <div>yyyyy</div>
                                                        </div>
                                                    
                                                        <div class="col-md-4 mt-3">
                                                            <strong>{{ __('select_resource') }}</strong>
                                                            <div>eeeee</div>
                                                        </div>
                                                   
                                                        <div class="col-md-4 mt-3">
                                                            <strong>{{ __('remarks') }}</strong>
                                                            <div>wwwww</div>
                                                        </div>
                                                    
                                                </div>
                                            
                                        
										{{--<div>{{ __('no_record_found') }}</div>--}}
                                            
                                    </div>
                                    <!-- /Product Information -->
									
									</div>
								</div>
								
							</div> <!-- cart body end -->
						</div>
					</div>
				@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- /Page Content -->

@include('modal.common')
@endsection 
@section('scripts')
<script src="{{ url('front-assets/js/page/email_management.js') }}"></script>
<script src="{{ url('front-assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
//var csrfToken = "{{ csrf_token() }}";
$( document ).ready(function() {
	if ($.fn.DataTable.isDataTable('.datatable')) {
		$('.datatable').DataTable().destroy(); // Destroy existing instance
	}

	$('.datatable').DataTable({
		//searching: false,
		language: {
			"lengthMenu": "{{ __('Show _MENU_ entries') }}",
			"zeroRecords": "{{ __('No records found') }}",
			"info": "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
			"infoEmpty": "{{ __('No entries available') }}",
			"infoFiltered": "{{ __('(filtered from _MAX_ total entries)') }}",
			"search": "{{ __('search') }}",
			"paginate": {
				"first": "{{ __('First') }}",
				"last": "{{ __('Last') }}",
				"next": "{{ __('Next') }}",
				"previous": "{{ __('Previous') }}"
			},
		}
	});
});
</script>
@endsection
