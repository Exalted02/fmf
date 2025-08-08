@extends('layouts.app')
@section('content')
    <!-- Page Wrapper -->
    <div class="container">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
					<h3 class="page-title text-center SofiaPro-SemiBold">Dashboard</h3>
				</div>
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="content-heading SofiaPro-SemiBold">Coming Soon!</h3>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
        
            <div class="row">
			{{Illuminate\Support\Facades\Auth::user()->name}}
            </div>
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

@endsection 
@section('scripts')

@endsection

