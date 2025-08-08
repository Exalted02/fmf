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
						<!-- /Page Header -->
					
						<!-- Input Rows -->
						<div class="addMoreformContainer" id="formContainer">
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Client's Name</label>
										<input class="form-control" type="text" placeholder="Client's Name">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="input-block">
										<label for="" class="col-form-label">Income</label>
										<input class="form-control" type="text" placeholder="Income">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="">
										<label for="" class="col-form-label">Type</label>
										<select class="select">
											<option>Type</option>
											<option value="0">Income</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="">
										<label for="" class="col-form-label">Frequency</label>
										<select class="select">
											<option>Frequency</option>
											<option value="0">Monthly</option>
											<option value="1">Yearly</option>
										</select>
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="input-block">
										<label for="" class="col-form-label">COLA</label>
										<input class="form-control" type="text" placeholder="COLA">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="input-block">
										<label for="" class="col-form-label">Start Age</label>
										<input class="form-control" type="text" placeholder="Start Age">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="input-block">
										<label for="" class="col-form-label">End Age</label>
										<input class="form-control" type="text" placeholder="End Age">
									</div>
								</div>
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
								<a href="{{route('roth-calculator')}}"><button class="btn btn-primary common-button">Next <i class="fa fa-arrow-right"></i></button></a>
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
    row.className = 'row add-more-seperator';
    row.innerHTML = `
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Client's Name</label>
				<input class="form-control" type="text" placeholder="Client's Name">
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="input-block">
				<label for="" class="col-form-label">Income</label>
				<input class="form-control" type="text" placeholder="Income">
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="">
				<label for="" class="col-form-label">Type</label>
				<select class="select">
					<option>Type</option>
					<option value="0">Income</option>
				</select>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<div class="">
				<label for="" class="col-form-label">Frequency</label>
				<select class="select">
					<option>Frequency</option>
					<option value="0">Monthly</option>
					<option value="1">Yearly</option>
				</select>
			</div>
		</div>
		<div class="col-lg-2 col-md-2">
			<div class="input-block">
				<label for="" class="col-form-label">COLA</label>
				<input class="form-control" type="text" placeholder="COLA">
			</div>
		</div>
		<div class="col-lg-2 col-md-2">
			<div class="input-block">
				<label for="" class="col-form-label">Start Age</label>
				<input class="form-control" type="text" placeholder="Start Age">
			</div>
		</div>
		<div class="col-lg-2 col-md-2">
			<div class="input-block">
				<label for="" class="col-form-label">End Age</label>
				<input class="form-control" type="text" placeholder="End Age">
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
@endsection

