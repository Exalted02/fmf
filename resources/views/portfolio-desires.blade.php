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
									<h3 class="page-title SofiaPro-SemiBold">Client Portfolio and Desires</h3>
								</div>
							</div>
						</div>
						<!-- /Page Header -->
					
						<div class="row">
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Client's Name</label>
									<input type="text" class="form-control" placeholder="Enter Client's Name">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Client's Age</label>
									<input type="text" class="form-control" placeholder="Enter Client's Age">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Partner's Name</label>
									<input type="text" class="form-control" placeholder="Enter Partner's Name">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Partner's Age</label>
									<input type="text" class="form-control" placeholder="Enter Partner's Age">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">Financial Information</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Current Portfolio Value</label>
									<input type="text" class="form-control" placeholder="Enter Current Portfolio Value">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Desired Gross Income During Retirement</label>
									<input type="text" class="form-control" placeholder="Enter Desired Gross Income">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Desired Retirement Age</label>
									<input type="text" class="form-control" placeholder="Enter Desired Retirement Age">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">Assumptions</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Cost of Living Adjustment (COLA)</label>
									<input type="text" class="form-control" placeholder="Enter Cost of Living Adjustment (COLA)">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Age to Begin COLA Adjustment</label>
									<input type="text" class="form-control" placeholder="Enter Age to Begin COLA Adjustment">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-block mb-3">
									<label class="col-form-label">Assumed Return</label>
									<input type="text" class="form-control" placeholder="Enter Assumed Return">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-box">
									<h3 class="content-heading SofiaPro-SemiBold">Retirement Income Planning Goal</h3>
									<div class="d-flex justify-between">												
										<label class="checkbox-inline"><input type="checkbox" name="income_plan[]" value="0" class="form-check-input"><span class="checkmark SofiaPro-SemiBold">Income</span></label>

										<label class="checkbox-inline"><input type="checkbox" name="income_plan[]" value="1" class="form-check-input"><span class="checkmark SofiaPro-SemiBold">Tax Reduction</span></label>
									
										<label class="checkbox-inline"><input type="checkbox" name="income_plan[]" value="2" class="form-check-input"><span class="checkmark SofiaPro-SemiBold">Legacy</span></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<a href="{{route('income-sources')}}"><button class="btn btn-primary common-button">Next <i class="fa fa-arrow-right"></i></button></a>
							</div>
						</div>
					</div>
				</div>			
				<div class="col-md-2 order-1 order-sm-2">
					<div class="step-section">
						<div class="triangle-container">
							<div class="triangle-up active">
								<span class="triangle-number">1</span>
							</div>
							<div class="triangle-up">
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
							<h5 class="step-title">Client Portfolio</h5>
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

@endsection

