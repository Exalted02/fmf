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
				
				@php 
				$primary_goal = explode(",", $val->RIPG);
				
				$primary1 = '';
				$primary2 = '';
				$primary3 = '';
				
				if(!empty($primary_goal))
				{
					if (isset($primary_goal[0])) {
						$primary1 = $primary_goal[0] == '1' 
							? 'Income' 
							: ($primary_goal[0] == '2' ? 'Tax Reduction' : 'Legacy');
					} else {
						$primary1 = null;
					}
					
					if (isset($primary_goal[1])) {
						$primary2 = $primary_goal[1] == '1' 
							? ', Income' 
							: ($primary_goal[1] == '2' ? ', Tax Reduction' : ', Legacy');
					} else {
						$primary2 = null;
					}
					
					if (isset($primary_goal[2])) {
						$primary3 = $primary_goal[2] == '1' 
							? ', Income' 
							: ($primary_goal[2] == '2' ? ', Tax Reduction' : ', Legacy');
					} else {
						$primary3 = null;
					}
				}
				@endphp
					<div class="card mb-1">
						<div class="card-header" id="heading-{{ $key }}">
							<h5 class="accordion-faq m-0 position-relative">
								<a class="custom-accordion-title text-reset d-block"
									data-bs-toggle="collapse" href="#collapse-{{ $key }}"
									aria-expanded="true" aria-controls="collapseNine">
									#. <strong>Client name</strong>:- {{$val->client_name}} &nbsp; <strong>Client age</strong>:- {{ $val->client_age}}&nbsp; <strong>Partner name</strong>:- {{ $val->partner_name}}<i
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
											<a href="#" data-bs-toggle="tab" data-bs-target="#portfolio-{{$key}}" class="active">
												<i class="fa-solid fa-chart-line"></i>{{ __('Client Portfolio Desire') }}
											</a>
										</li>
										<li>
											<a href="#" data-bs-toggle="tab" data-bs-target="#financial_account-{{$key}}">
												<i class="fa-solid fa-chart-line"></i>{{ __('Current Financial Account') }}
											</a>
										</li>
										<li>
											<a href="#" data-bs-toggle="tab" data-bs-target="#income_source-{{$key}}">
												<i class="fa-solid fa-hand-holding-dollar"></i>{{ __('Guaranteed Income Sources') }}
											</a>
										</li>
										<li>
											<a href="#" data-bs-toggle="tab" data-bs-target="#roth-calulator-{{$key}}">
												<i class="fa-solid fa-calculator"></i>{{ __('Roth Conversion Calculators') }}
											</a>
										</li>
										
									</ul>
								</div>
								
								<div class="contact-tab-view">
									<div class="tab-content pt-0">
									
										<!-- Portfolio Information -->
										<div class="tab-pane active show" id="portfolio-{{$key}}">
											<div class="multiadd d-flex flex-wrap">
											<div class="row">
												<div class="col-md-4 mt-3">
													<strong>{{ __('Client name') }}</strong>
													<div>{{ $val->client_name ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Client age') }}</strong>
													<div>{{ $val->client_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Partner name') }}</strong>
													<div>{{ $val->partner_name ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Partner age') }}</strong>
													<div>{{ $val->partner_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Current portfolio value') }}</strong>
													<div>{{ $val->current_portfolio_value ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Desired gross income retirement') }}</strong>
													<div>{{ $val->desired_gross_income_retirement ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Desired retirement age') }}</strong>
													<div>{{ $val->desired_retirement_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('COLA') }}</strong>
													<div>{{ $val->COLA ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Cola age') }}</strong>
													<div>{{ $val->cola_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Assumed return') }}</strong>
													<div>{{ $val->assumed_return ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('RIPG') }}</strong>
													<div> {{ $primary1 }}{{ $primary2 }}{{ $primary3 }}</div>
												</div>
											  </div>
                                            </div>
                                            {{--<div>{{ __('no_record_found') }}</div>--}}
										</div>
										<!-- /Portfolio Information -->
										
										<!-- Current financial account -->
										<div class="tab-pane fade" id="financial_account-{{$key}}">
											@php
												$financialAccountHusband = App\Models\Current_financial_account::where('sl_no', $val->id)->where('account_owner',  1)->get();
												//echo"<pre>";print_r($$financialAccountHusband);
											@endphp
										@if($financialAccountHusband->isNotEmpty())
											<div class="row">
											<h4 class="section-title">Husband Asset</h4>
											</div>
											
                                            @foreach($financialAccountHusband as $account)
											@php
												$tax_quali = $account->tax_qualification == 1 ? 'IRA ' : 'non-qualified';
											@endphp
											<div class="multiadd">
											<div class="row">
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Account title') }}</strong>
													  <div>{{ $account->account_title ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Tax qualification') }}</strong>
													  <div>{{ $tax_quali ?? 'NA'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Account value') }}</strong>
													  <div>{{ $account->account_value ?? 'NA'}}</div>
												</div>
												
											</div>
										</div>
										@endforeach
										@endif
										
										@php
											$financialAccountWife = App\Models\Current_financial_account::where('sl_no', $val->id)->where('account_owner',  2)->get();
											//echo"<pre>";print_r($financialAccountWife);
										@endphp
										
										@if($financialAccountWife->isNotEmpty())
										<div class="row mt-3">
											<h4 class="section-title">Wife Asset</h4>
										</div>
											@foreach($financialAccountWife as $account)
											@php
												$tax_quali = $account->tax_qualification == 1 ? 'IRA ' : 'non-qualified';
											@endphp
											<div class="multiadd">
											<div class="row">
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Account title') }}</strong>
													  <div>{{ $account->account_title ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Tax qualification') }}</strong>
													  <div>{{ $tax_quali ?? 'NA'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Account value') }}</strong>
													  <div>{{ $account->account_value ?? 'NA'}}</div>
												</div>
												
											</div>
										</div>
										@endforeach
										@endif
										
										@php
											$financialAccountJoint = App\Models\Current_financial_account::where('sl_no', $val->id)->where('account_owner',  3)->get();
											//echo"<pre>";print_r($financialAccountJoint);
										@endphp
										
										@if($financialAccountJoint->isNotEmpty())
										<div class="row mt-3">
											<h4 class="section-title">Joint Asset</h4>
										</div>
										
                                            @foreach($financialAccountJoint as $account)
											@php
												$tax_quali = $account->tax_qualification == 1 ? 'IRA ' : 'non-qualified';
											@endphp
											<div class="multiadd">
											<div class="row">
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Account title') }}</strong>
													  <div>{{ $account->account_title ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Tax qualification') }}</strong>
													  <div>{{ $tax_quali ?? 'NA'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Account value') }}</strong>
													  <div>{{ $account->account_value ?? 'NA'}}</div>
												</div>
												
											</div>
										</div>
										@endforeach
										@endif
										
										@if($financialAccountHusband->isEmpty() && $financialAccountWife->isEmpty() && $financialAccountJoint->isEmpty())
											<div class="col-md-12 mb-6 ms-3">{{ __('No record found') }}</div>
										@endif
										</div>
										<!-- /Current financial account-->
										<!-- Income source Information -->
										<div class="tab-pane fade" id="income_source-{{ $key }}">
										
											@php 
											$ifExists = App\Models\Guaranteed_income_sources::where('sl_no', $val->id)->exists();
											@endphp
											@if($ifExists)
											@php
												$guaranteedIncome = App\Models\Guaranteed_income_sources::where('sl_no', $val->id)->get();
												//echo"<pre>";print_r($guaranteedIncome);
											@endphp
                                            @foreach($guaranteedIncome as $incomes)
											@php 
												$type = !empty($incomes->type) && $incomes->type == 1 ? 'Income ' : 'N/A';
												$frequency = !empty($incomes->frequency) && $incomes->frequency == 1 ? 'Monthly' : 'Yearly';
											@endphp
                                        <div class="multiadd">
											<div class="row">
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Client name') }}</strong>
													  <div>{{ $incomes->client_name ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Income amount') }}</strong>
													  <div>{{ $incomes->income_amount ?? 'NA'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Type') }}</strong>
													  <div>{{ $type ?? 'NA'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Frequency') }}</strong>
													  <div>{{ $frequency ?? 'NA' }}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Cola') }}</strong>
													  <div>{{ $incomes->cola ?? 'NA' }}</div>
												</div>
												
												<div class="col-md-4 mt-3">
													  <strong>{{ __('Start age') }}</strong>
													  <div>{{ $incomes->start_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													  <strong>{{ __('End age') }}</strong>
													  <div>{{ $incomes->end_age ?? 'N/A'}}</div>
												</div>
											</div>
										</div>
										@endforeach
										@else
											<div class="col-md-12 mb-6 ms-3">{{ __('No record found') }}</div>
										@endif
										</div>
										<!-- Income source Information -->
										
										<!-- roth calulator -->
										@php 
										$roth_data = App\Models\Roth_conversion_calculators::where('sl_no', $val->id)->first();
										@endphp
										<div class="tab-pane fade" id="roth-calulator-{{$key}}">
										
										 <div class="">
											<div class="row">
											<h4 class="section-title">Conversion Timeline</h4>
											</div>
											<div class="row">
												<div class="col-md-4 mt-3">
													<strong>{{ __('Start age') }}</strong>
													<div>{{ $roth_data->conversion_start_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Finish age') }}</strong>
													<div>{{ $roth_data->conversion_finish_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Aannual fee') }}</strong>
													<div>{{ $roth_data->conversion_annual_fee ?? 'N/A'}}</div>
												</div>
											</div>
											<div class="row mt-4">
											<h4 class="section-title">RMD: Required Minimum Distribution</h4>
											</div>
											<div class="row">	
												<div class="col-md-4 mt-3">
													<strong>{{ __('Rmd start age') }}</strong>
													<div>{{ $roth_data->rmd_start_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Rmd finish age') }}</strong>
													<div>{{ $roth_data->rmd_finish_age ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Tax free income') }}</strong>
													<div>{{ $roth_data->rmd_tax_free_income ?? 'N/A'}}</div>
												</div>
											</div>
                                          </div>
										  
										  <!--IRS 5-Year Rule -->
										@php
										$roth = App\Models\Roth_conversion_calculators::where('sl_no', $val->id)->first();
										$r_id = ($roth->id ?? '');
										$roth_yearly_exists = App\Models\Roth_conversion_calculator_yearly_rule::where('roth_id', $r_id)->exists();
										@endphp
									@if($roth_yearly_exists)
										<h4 class="irs-section-title">IRS 5-Year Rule</h4>
										@php 
										$roth_yearly_data = App\Models\Roth_conversion_calculator_yearly_rule::where('roth_id', $r_id)->get();
										@endphp
										@foreach($roth_yearly_data as $yearlyVal)
										<div class="multiadd">
											<div class="row">
												<div class="col-md-4 mt-3">
													<strong>{{ __('Investment amount') }}</strong>
													 <div>{{ $yearlyVal->investment_amount ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Bonus') }}</strong>
													 <div>{{ $yearlyVal->bonus ?? 'N/A'}}</div>
												</div>
												<div class="col-md-4 mt-3">
													<strong>{{ __('Assumed return') }}</strong>
													 <div>{{ $yearlyVal->assumed_return ?? 'N/A'}}</div>
												</div>
											</div>
										</div>
										@endforeach
									@endif
										  <!--/IRS 5-Year Rule -->
											
										</div>
										<!-- /roth calulator -->
									
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
