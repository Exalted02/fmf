<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Models\Pricing_plan;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
	public function pricing_plans()
	{
		return view('admin.pricing-plans.index');
	}
	public function pricing_plans_edit_save(Request $request)
	{
		//echo "<pre>";print_r($request->all());die;
		$request->validate([
			'monthly_billing.*' => 'required',
		]);
		
		$monthly_billing = $request->input('monthly_billing', []);
		$annual_billing = $request->input('annual_billing', []);
		for($index = 0; $index < count($monthly_billing); $index++)
		{
			$model = new Pricing_plan();
			$model->plan_type = 1;
			$model->plan_name = $monthly_billing[$index];
			$model->status = 1;
			$model->save();
		}
		
		for($index = 0; $index < count($annual_billing); $index++)
		{
			$model = new Pricing_plan();
			$model->plan_type = 2;
			$model->plan_name = $annual_billing[$index];
			$model->status = 1;
			$model->save();
		}
	}
}
