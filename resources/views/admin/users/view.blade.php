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
					{{--<div class="col-xl-6">
                            <div id="accordion" class="custom-faq">
                                <div class="card mb-1">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="accordion-faq m-0">
                                            <a class="text-dark" data-bs-toggle="collapse" href="#collapseOne" aria-expanded="true">
                                                <i class="mdi mdi-help-circle me-1 text-primary"></i> 
                                                What is Vakal text here?
                                            </a>
                                        </h5>
                                    </div>
                        
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion">
                                        <div class="card-body">
                                            Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.
                                            Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-1">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="accordion-faq m-0">
                                            <a class="text-dark" data-bs-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                                                <i class="mdi mdi-help-circle me-1 text-primary"></i> 
                                                Why use Vakal text here?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordion">
                                        <div class="card-body">
                                            Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.
                                            Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-1">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="accordion-faq m-0">
                                            <a class="text-dark" data-bs-toggle="collapse" href="#collapseThree" aria-expanded="false">
                                                <i class="mdi mdi-help-circle me-1 text-primary"></i> 
                                                How many variations exist?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordion">
                                        <div class="card-body">
                                            Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.
                                            Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.
                                        </div>
                                    </div>
                                </div>
    
                                <div class="card mb-1">
                                    <div class="card-header" id="headingFour">
                                        <h5 class="accordion-faq m-0">
                                            <a class="text-dark" data-bs-toggle="collapse" href="#collapseFour" aria-expanded="false">
                                                <i class="mdi mdi-help-circle me-1 text-primary"></i> 
                                                What is Vakal text here?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="collapseFour" data-bs-parent="#accordion">
                                        <div class="card-body">
                                            Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.
                                            Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end #accordions-->
                        </div>--}} <!-- end col -->

                        <div class="col-xl-12">
                            <div class="accordion custom-accordion" id="custom-accordion-one">
                                <div class="card mb-1">
                                    <div class="card-header" id="headingNine">
                                        <h5 class="accordion-faq m-0 position-relative">
                                            <a class="custom-accordion-title text-reset d-block"
                                                data-bs-toggle="collapse" href="#collapseNine"
                                                aria-expanded="true" aria-controls="collapseNine">
                                                Q. Can I use this template for my client? <i
                                                    class="mdi mdi-chevron-down accordion-arrow"></i>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseNine" class="collapse show"
                                        aria-labelledby="headingFour"
                                        data-bs-parent="#custom-accordion-one">
                                        <div class="card-body">
                                            Yup, the marketplace license allows you to use this theme
                                            in any end products.
                                            For more information on licenses, please refere <a
                                                href="#" target="_blank">here</a>.
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-1">
                                    <div class="card-header" id="headingFive">
                                        <h5 class="accordion-faq m-0 position-relative">
                                            <a class="custom-accordion-title text-reset collapsed d-block"
                                                data-bs-toggle="collapse" href="#collapseFive"
                                                aria-expanded="false" aria-controls="collapseFive">
                                                Q. Can this theme work with Wordpress? <i
                                                    class="mdi mdi-chevron-down accordion-arrow"></i>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseFive" class="collapse"
                                        aria-labelledby="headingFive"
                                        data-bs-parent="#custom-accordion-one">
                                        <div class="card-body">
                                            No. This is a HTML template. It won't directly with
                                            wordpress, though you can convert this into wordpress
                                            compatible theme
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-1">
                                    <div class="card-header" id="headingSix">
                                        <h5 class="accordion-faq m-0 position-relative">
                                            <a class="custom-accordion-title text-reset collapsed d-block"
                                                data-bs-toggle="collapse" href="#collapseSix"
                                                aria-expanded="false" aria-controls="collapseSix">
                                                Q. How do I get help with the theme? <i
                                                    class="mdi mdi-chevron-down accordion-arrow"></i>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                                        data-bs-parent="#custom-accordion-one">
                                        <div class="card-body">
                                            Use our dedicated support email (support@coderthemes.com) to
                                            send your issues or feedback. We are here to help anytime
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-1">
                                    <div class="card-header" id="headingSeven">
                                        <h5 class="accordion-faq m-0 position-relative">
                                            <a class="custom-accordion-title text-reset collapsed d-block"
                                                data-bs-toggle="collapse" href="#collapseSeven"
                                                aria-expanded="false" aria-controls="collapseSeven">
                                                Q. Will you regularly give updates of DGT ? <i
                                                    class="mdi mdi-chevron-down accordion-arrow"></i>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseSeven" class="collapse"
                                        aria-labelledby="headingSeven"
                                        data-bs-parent="#custom-accordion-one">
                                        <div class="card-body">
                                            Yes, We will update the DGT regularly. All the
                                            future updates would be available without any cost
                                        </div>
                                    </div>
                                </div>
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
