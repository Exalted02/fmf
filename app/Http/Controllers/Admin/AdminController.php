<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Models\Pricing_plan;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
	public function pricing_plans()
	{
		$monthly_plan_arr = [];
		$yearly_plan_arr = [];
		$pricing_monthly_plan = Pricing_plan::where('plan_type', 1)->get();
		$pricing_yearly_plan = Pricing_plan::where('plan_type', 2)->get();
		foreach($pricing_monthly_plan as $m)
		{
			$monthly_plan_arr[] = $m->plan_name;
		}
		
		foreach($pricing_yearly_plan as $y)
		{
			$yearly_plan_arr[] = $y->plan_name;
		}
		$data['monthly_plan_arr'] = $monthly_plan_arr;
		$data['yearly_plan_arr'] = $yearly_plan_arr;
		
		return view('admin.pricing-plans.index', $data);
	}
	public function pricing_plans_edit_save(Request $request)
	{
		//echo "<pre>";print_r($request->all());die
		
		$monthly_billing = $request->input('monthly_billing', []);
		$annual_billing = $request->input('annual_billing', []);
		Pricing_plan::query()->truncate();
		
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
		
		return redirect('/admin/pricing-plans');
	}
	public function users()
	{
		$data = [];
		$users = User::where('user_type', 1)->where('status', '!=', 2)->get();
		//echo "<pre>";print_r($users);die;
		$data['users'] = $users;
		return view('admin.users.index', $data);
	}
	public function user_view($id='')
	{
		$data = [];
		$user = User::where('id', $id)->first();
		$data['users'] = $user;
		return view('admin.users.view', $data);
		echo "<pre>";print_r($user);die;
	}
}
