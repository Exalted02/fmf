<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client_portfolio_Desires;
use App\Models\Guaranteed_income_sources;

class DashboardController extends Controller
{
    public function index()
    {
		$data = [];
		return view('dashboard', $data);
    }
    public function pricing_plans()
    {
		$data = [];
        return view('pricing-plans', $data);
    }
    public function portfolio_desires()
    {
		$data = [];
		
        return view('portfolio-desires', $data);
    }
    public function income_sources()
    {
		$data = [];
		
        return view('income-sources', $data);
    }
    public function roth_calculator()
    {
		$data = [];
		
        return view('roth-calculator', $data);
    }
	public function portfolio_desires_save(Request $request)
	{
		//echo "<pre>";print_r($request->all());die;
		$model = new Client_portfolio_Desires();
		$model->user_id  = auth()->user()->id;
		$model->client_name  = $request->client_name;
		$model->client_age  = $request->client_age;
		$model->partner_name  = $request->partner_name;
		$model->partner_age  = $request->partner_age;
		$model->current_portfolio_value  = $request->current_portfolio_value;
		$model->desired_gross_income_retirement  = $request->desired_gross_income_retirement;
		$model->desired_retirement_age  = $request->desired_retirement_age;
		$model->COLA  = $request->COLA;
		$model->cola_age  = $request->cola_age;
		$model->assumed_return  = $request->assumed_return;
		$model->RIPG  = implode(',', $request->RIPG) ?? null;
		$model->status  = 1;
		$model->save();
		$id = $model->id;
		Session::put('sl_no', $id);
		return response()->json(['message'=>'success']);
	}
}
