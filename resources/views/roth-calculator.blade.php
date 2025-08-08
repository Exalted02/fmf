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
									<h3 class="page-title SofiaPro-SemiBold">Guaranteed Income Sources</h3>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">IRS 5-Year Rule</h3>
							</div>
						</div>
						<!-- /Page Header -->
					
						<!-- Input Rows -->
						<div class="addMoreformContainer" id="formContainer">
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Investment Amount $</label>
										<input class="form-control" type="text" placeholder="Investment Amount $">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Bonus %</label>
										<input class="form-control" type="text" placeholder="Bonus %">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Assumed Return</label>
										<input class="form-control" type="text" placeholder="Assumed Return">
									</div>
								</div>
								<div class="col-lg-1 col-md-1">
									<div class="add-more-row-icon">
										<a href="javascript:void(0)" class="" onclick="deleteRow(this)"><i class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						</div>
						<!-- Add Button -->
						<div class="row">
							<div class="col-sm-12">
								<button class="btn btn-primary add-btn mt-2" onclick="addRow()">+ ADD</button>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">Conversion Timeline</h3>
							</div>
						</div>
						<div class="addMoreformContainer">
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Start Age</label>
										<input class="form-control" type="text" placeholder="Start Age">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Finish Age</label>
										<input class="form-control" type="text" placeholder="Finish Age">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Annual Fee</label>
										<input class="form-control" type="text" placeholder="Annual Fee">
									</div>
								</div>
							</div>
						</div>
						
						<div class="row mt-3">
							<div class="col-sm-12">
								<h3 class="content-heading SofiaPro-SemiBold">RMD: Required Minimum Distribution</h3>
							</div>
						</div>
						<div class="addMoreformContainer">
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Start Age</label>
										<input class="form-control" type="text" placeholder="Start Age">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Finish Age</label>
										<input class="form-control" type="text" placeholder="Finish Age">
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="input-block">
										<label for="" class="col-form-label">Age to Begin TAX FREE INCOME</label>
										<input class="form-control" type="text" placeholder="Age to Begin TAX FREE INCOME">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<button class="btn btn-primary add-btn mt-2">Calculate</button>
							</div>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-md-12">
							<div class="d-flex justify-between submit-section mt-2 mb-5">
								<a href="{{route('income-sources')}}"><button class="btn btn-primary common-button"><i class="fa fa-arrow-left"></i> Previous</button></a>
								<a href="javascript:void(0)"><button class="btn btn-primary common-button">Next <i class="fa fa-arrow-right"></i></button></a>
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
							<div class="triangle-up active">
								<span class="triangle-number">3</span>
							</div>
							<div class="triangle-up">
								<span class="triangle-number">4</span>
							</div>
						</div>

						<div class="step-content mt-3">
							<h5 class="step-title">Income Sources</h5>
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
  function addRow() {
    const container = document.getElementById('formContainer');
    const row = document.createElement('div');
    row.className = 'row add-more-seperator';
    row.innerHTML = `
		<div class="col-lg-4 col-md-4">
			<div class="input-block">
				<label for="" class="col-form-label">Investment Amount $</label>
				<input class="form-control" type="text" placeholder="Investment Amount $">
			</div>
		</div>
		<div class="col-lg-4 col-md-4">
			<div class="input-block">
				<label for="" class="col-form-label">Bonus %</label>
				<input class="form-control" type="text" placeholder="Bonus %">
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Assumed Return</label>
				<input class="form-control" type="text" placeholder="Assumed Return">
			</div>
		</div>
		<div class="col-lg-1 col-md-1">
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
@endsection

