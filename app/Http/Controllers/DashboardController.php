<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client_portfolio_Desires;
use App\Models\Guaranteed_income_sources;
use App\Models\Roth_conversion_calculators;
use App\Models\Roth_conversion_calculator_yearly_rule;
use Illuminate\Support\Facades\Session;

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
	public function income_sources_save(Request $request)
	{
		//echo "<pre>";print_r($request->all());die;
		$client_name = $request->input('client_arr', []);
		$income_amount = $request->input('income_amount_arr', []);
		$type = $request->input('type_amount_arr', []);
		$frequency = $request->input('frequency_amount_arr', []);
		$cola = $request->input('cola_arr', []);
		$start_age = $request->input('start_age_arr', []);
		$end_age = $request->input('end_age_arr', []);
		
		$sl_no = Session::get('sl_no');
		$countrecord = count($client_name);
		for($index = 0; $index < $countrecord; $index++)
		{
			$model = new Guaranteed_income_sources();
			$model->sl_no = $sl_no ?? null;
			$model->user_id = auth()->user()->id;
			$model->client_name = $client_name[$index] ?? null;
			$model->income_amount = $income_amount[$index] ?? null;
			$model->type = $type[$index] ?? null;
			$model->frequency = $frequency[$index] ?? null;
			$model->cola = $cola[$index] ?? null;
			$model->start_age = $start_age[$index] ?? null;
			$model->end_age = $end_age[$index] ?? null;
			$model->status = 1;
			$model->save();	
		}
		
		return response()->json(['message'=>'success']);
	}
	public function roth_calculator_save(Request $request)
	{
		$sl_no = Session::get('sl_no');
		$model = new Roth_conversion_calculators();
		$model->user_id  = auth()->user()->id;
		$model->sl_no  = $sl_no;
		$model->conversion_start_age  = $request->conversion_start_age;
		$model->conversion_finish_age  = $request->conversion_finish_age;
		$model->conversion_annual_fee  = $request->conversion_annual_fee;
		$model->rmd_start_age  = $request->rmd_start_age;
		$model->rmd_finish_age  = $request->rmd_finish_age;
		$model->rmd_tax_free_income  = $request->rmd_tax_free_income;
		$model->save();
		$roth_id = $model->id;
		
		$investment_amount = $request->input('investment_amount_arr', []);
		$income_amount = $request->input('income_amount_arr', []);
		$bonus = $request->input('bonus_arr', []);
		$assumed_return = $request->input('assumed_return_arr', []);
		
		$countrecord = count($investment_amount);
		if($countrecord > 0)
		{
			for($index = 0; $index < $countrecord; $index++)
			{
				$rothmodel = new Roth_conversion_calculator_yearly_rule();
				$rothmodel->roth_id = $roth_id ?? null;
				
				$rothmodel->investment_amount = $investment_amount[$index] ?? null;
				$rothmodel->bonus = $bonus[$index] ?? null;
				$rothmodel->assumed_return = $assumed_return[$index] ?? null;
				$rothmodel->status = 1;
				$rothmodel->save();	
			}
		}
		Session::destroy('sl_no');
		return response()->json(['message'=>'success']);
	}
}
