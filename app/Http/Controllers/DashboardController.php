<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscriptions;
use App\Models\Client_portfolio_Desires;
use App\Models\Guaranteed_income_sources;
use App\Models\Roth_conversion_calculators;
use App\Models\Roth_conversion_calculator_yearly_rule;
use Illuminate\Support\Facades\Session;
use App\Models\Current_financial_account;
use App\Models\Roth_conversion_year;
use Stripe\Price;

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
		//echo auth()->user()->id; die;
		/*
		// Set your secret key
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
		
        $user = User::with(['get_subscription','get_subscription.subscription_items'])->where('id', auth()->user()->id)->orderBy('id', 'DESC')->first();
        $last_subscription = Subscriptions::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
		// dd($user);
		// Retrieve the price from Stripe
    //    $price = Price::retrieve($plan->stripe_plan);
        // Get the unit amount of the price
    //    $amount = $price->unit_amount/100;
		//dd($amount);
        $intent = auth()->user()->createSetupIntent();*/
		
		// return view("pricing-plans", compact("data", "intent", "user", "last_subscription"));
        return view('pricing-plans', $data);
    }
    public function portfolio_desires()
    {
		$exists_record = Client_portfolio_Desires::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->first();
		if($exists_record){
			Session::put('sl_no', $exists_record->id);
		}		
		
		$data = [];
		$id = Session::get('sl_no');
		$data['record'] = '';
		if(!empty($id))
		{
			$record = Client_portfolio_Desires::where('id', $id)->first();
			$data['record'] = $record;
		}
		//Session::forget('has_roth');
		return view('portfolio-desires', $data);
    }
	public function current_financial_account()
    {
		$data = [];
		$sl_no = Session::get('sl_no');
		if(!empty($sl_no))
		{
			$record = Current_financial_account::where('sl_no', $sl_no)->get();
			$data['records'] = $record;
			
		}
		else
		{
			return redirect('portfolio-desires');
		}
        return view('current-financial-account', $data);
    }
    public function income_sources()
    {
		$data = [];
		$sl_no = Session::get('sl_no');
		
		$has_current_income = Session::get('has_current_income');
		$has_income_source = Session::get('has_income_source');
		
		if(!empty($sl_no))
		{
			$record = Guaranteed_income_sources::where('sl_no', $sl_no)->get();
			$data['records'] = $record;
			
			if(empty($has_current_income) && empty($has_income_source))
			{
				return redirect('current-financial-account');
			}
		}
		else
		{
			return redirect('portfolio-desires');
		}
		
        return view('income-sources', $data);
    }
    public function roth_calculator()
    {
		$data = [];
		$sl_no = Session::get('sl_no');
		
		$has_current_income = Session::get('has_current_income');
		$has_income_source = Session::get('has_income_source');
		$has_roth = Session::get('has_roth');
		
		if(!empty($sl_no))
		{
			
			$result = Roth_conversion_calculators::where('sl_no', $sl_no)->first();
			$data['results'] = $result;
			$roth_id = $result ? $result->id : '';
			
			$record = Roth_conversion_calculator_yearly_rule::where('roth_id', $roth_id)->get();
			//echo "<pre>";print_r($record);die;
			$data['records'] = $record;
			
			if(empty($has_current_income) && empty($has_income_source) && empty($has_roth))
			{
				return redirect('current-financial-account');
			}
			else if(!empty($has_current_income) && empty($has_income_source) && empty($has_roth))
			{
				return redirect('income-sources');
			}
		}
		else
		{
			return redirect('portfolio-desires');
		}
		
        return view('roth-calculator', $data);
    }
	public function portfolio_desires_save(Request $request)
	{
		//echo "<pre>";print_r($request->all());die;
		$id = Session::get('sl_no');
		$RIPG = null;
		if(!empty($request->RIPG))
		{
			$RIPG = implode(',', $request->RIPG);
		}
		
		
		if($id !='')
		{
			$model  = Client_portfolio_Desires::find($id);
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
			$model->RIPG  = $RIPG;
			$model->save();
		}
		else
		{
		
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
			$model->RIPG  = $RIPG;
			$model->status  = 1;
			$model->save();
			$id = $model->id;
			Session::put('sl_no', $id);
		}
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
		if($sl_no != '')
		{
			Guaranteed_income_sources::where('sl_no', $sl_no)->delete();
		}
		
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
		Session::put('has_income_source', 1);
		return response()->json([
			'status' => true,
			'message' => 'success',
		]);
	}
	public function roth_calculator_save(Request $request)
	{
		$sl_no = Session::get('sl_no');
		$res = Roth_conversion_calculators::where('sl_no', $sl_no)->first();
		$id = $res ? $res->id : '';
		if($id != '')
		{
			$model = Roth_conversion_calculators::find($id);
			$model->user_id  = auth()->user()->id;
			$model->sl_no  = $sl_no;
			$model->conversion_start_age  = $request->conversion_start_age;
			$model->conversion_finish_age  = $request->conversion_finish_age;
			$model->conversion_annual_fee  = $request->conversion_annual_fee;
			$model->rmd_start_age  = $request->rmd_start_age;
			$model->rmd_finish_age  = $request->rmd_finish_age;
			$model->rmd_tax_free_income  = $request->rmd_tax_free_income;
			$model->save();
			$roth_id = $sl_no;
		}
		else 
		{
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
		}
		
		$investment_amount = $request->input('investment_amount_arr', []);
		$bonus = $request->input('bonus_arr', []);
		$assumed_return = $request->input('assumed_return_arr', []);
		
		
		if($sl_no != '')
		{
			Roth_conversion_calculator_yearly_rule::where('roth_id', $roth_id)->delete();
		}
		
		$countrecord = count($investment_amount);
		
		for($index = 0; $index < $countrecord; $index++)
		{
			if(!empty($investment_amount[$index]) || !empty($bonus[$index]) || !empty($assumed_return[$index]))
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
		
		Session::put('has_roth', 1);
		return response()->json(['message'=>'success']);
	}
	public function current_financial_account_save(Request $request)
	{
		//echo "<pre>";print_r($request->all());die;
		
		$account_owner = $request->input('account_owner_arr', []);
		$account_title = $request->input('account_title_arr', []);
		$tax_qualification = $request->input('tax_qualification_arr', []);
		$account_value = $request->input('account_value_arr', []);
		$age_income_start = $request->input('age_income_start_arr', []);
		$annual_income_value = $request->input('annual_income_value_arr', []);
		
		$sl_no = Session::get('sl_no');
		if($sl_no != '')
		{
			Current_financial_account::where('sl_no', $sl_no)->delete();
		}
		
		$countrecord = count($account_owner);
		for($index = 0; $index < $countrecord; $index++)
		{
			$model = new Current_financial_account();
			$model->sl_no = $sl_no ?? null;
			$model->user_id = auth()->user()->id;
			$model->account_owner = $account_owner[$index] ?? null;
			$model->account_title = $account_title[$index] ?? null;
			$model->tax_qualification = $tax_qualification[$index] ?? null;
			$model->age_income_start = $age_income_start[$index] ?? null;
			$model->account_value = $account_value[$index] ?? null;
			$model->annual_income_value = $annual_income_value[$index] ?? null;
			$model->status = 1;
			$model->save();	
		}
		Session::put('has_current_income', 1);
		return response()->json(['message'=>'success']);
	}
	public function delete_current_financial_account(Request $request)
	{
		Current_financial_account::where('id', $request->id)->delete();
	}
	public function delete_income_source(Request $request)
	{
		Guaranteed_income_sources::where('id', $request->id)->delete();
	}
	public function delete_roth_calculator(Request $request)
	{
		Roth_conversion_calculator_yearly_rule::where('id', $request->id)->delete();
	}
	public function roth_calculator_year()
	{
		$data = [];
		$sl_no = Session::get('sl_no');
		
		$has_current_income = Session::get('has_current_income');
		$has_income_source = Session::get('has_income_source');
		$has_roth = Session::get('has_roth');
		$has_roth_year = Session::get('has_roth_year');
		
		if(!empty($sl_no))
		{
			$record = Roth_conversion_year::where('sl_no', $sl_no)->first();
			$data['records'] = $record;
			
			if(empty($has_current_income) && empty($has_income_source) && empty($has_roth) && empty($has_roth_year))
			{
				return redirect('current-financial-account');
			}
			else if(!empty($has_current_income) && empty($has_income_source) && empty($has_roth) && empty($has_roth_year))
			{
				return redirect('income-sources');
			}
			else if(!empty($has_current_income) && !empty($has_income_source) && empty($has_roth) && empty($has_roth_year))
			{
				return redirect('roth-calculator');
			}
		}
		else
		{
			return redirect('portfolio-desires');
		}
		return view('roth-calculator-year', $data);
	}
	public function roth_calculator_year_save(Request $request)
	{
		//echo "<pre>";print_r($request->all());die;
		$model = new Roth_conversion_year();
		$model->sl_no = Session::get('sl_no');
		$model->user_id = auth()->user()->id;
		$model->year = $request->year ?? null;
		$model->rmd_age = $request->rmd_age == true ? 1: 0;
		$model->save();
		Session::put('has_roth_year', 1);
		//Session::forget('sl_no');
		//Session::forget('has_current_income');
		//Session::forget('has_income_source');
		//Session::forget('has_roth_year');
		return response()->json(['message'=>'success']);
	}
}
