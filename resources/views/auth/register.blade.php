@extends('layouts.app')
@section('content')
    <!-- Page Wrapper -->
    <div class="container">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
					<h3 class="page-title text-center SofiaPro-SemiBold">Create a Account</h3>
				</div>
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="content-heading SofiaPro-SemiBold">Personal Information</h3>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
        
            <div class="row">
                <div class="col-md-6">
					<div class="input-block mb-3">
						<label class="col-form-label">First Name <span class="text-danger"> *</span></label>
						<input type="text" class="form-control" placeholder="Enter your first name">
					</div>
                </div>
                <div class="col-md-6">
					<div class="input-block mb-3">
						<label class="col-form-label">Last Name <span class="text-danger"> *</span></label>
						<input type="text" class="form-control" placeholder="Enter your last name">
					</div>
                </div>
                <div class="col-md-6">
					<div class="input-block mb-3">
						<label class="col-form-label">Email/Username <span class="text-danger"> *</span></label>
						<input type="text" class="form-control" placeholder="Enter your email">
					</div>
                </div>
                <div class="col-md-6">
					<div class="input-block mb-3">
						<label class="col-form-label">Phone Number <span class="text-danger"> *</span></label>
						<input type="text" class="form-control" placeholder="Enter your phone number">
					</div>
                </div>
                <div class="col-md-6">
					<div class="input-block mb-3 position-relative">
						<label class="col-form-label">Password <span class="text-danger"> *</span></label>
						<input type="password" class="form-control password-input" id="password" placeholder="Enter password">
						<span class="toggle-password" toggle="#password">
							<i class="fas fa-eye"></i>
						</span>
					</div>
                </div>
                <div class="col-md-6">
					<div class="input-block mb-3 position-relative">
						<label class="col-form-label">Confirm Password <span class="text-danger"> *</span></label>
						<input type="password" class="form-control password-input" id="confirm_password" placeholder="Enter confirm password">
						<span class="toggle-password" toggle="#confirm_password">
							<i class="fas fa-eye"></i>
						</span>
					</div>
                </div>
                <div class="col-md-12">
					<div class="input-block mb-3">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="dropdownCheck">
							<label class="form-check-label" for="dropdownCheck">
								The following disclaimer in place of what you have:</br>The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide. The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings. Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences".
							</label>
						</div>
					</div>
                </div>
				<div class="col-md-12">
					<div class="submit-section mt-2 mb-5">
						<button class="btn common-cancel-button mr-10px">Cancel</button>
						<button class="btn btn-primary common-button">Submit</button>
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
  document.querySelectorAll('.toggle-password').forEach(function (element) {
    element.addEventListener('click', function () {
      const input = document.querySelector(this.getAttribute('toggle'));
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });
</script>
@endsection

