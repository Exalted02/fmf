<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Client_portfolio_Desires;
use App\Models\Current_financial_account;
use App\Models\Guaranteed_income_sources;

class PdfController extends Controller
{
	public function incomePlan()
	{
		if(empty(auth()->user()->id))
		{
			return redirect('login');
		}
		$lastId = Client_portfolio_Desires::where('user_id', auth()->user()->id)->latest('id')->value('id');
		
		$portfolio_Desire_data = Client_portfolio_Desires::where('user_id', auth()->user()->id)->where('id', $lastId)->first();
		
		$current_financial_account = Current_financial_account::where('sl_no', $lastId)->where('user_id', auth()->user()->id)->get();
		
		$current_income_account = Guaranteed_income_sources::where('sl_no', $lastId)->where('user_id', auth()->user()->id)->get();
		
		//echo "<pre>";print_r($portfolio_Desire_data);die;
		
		// for more excel data in pdf
		$headerAccountOwnerArray = [];
		$headerAccountTitleArray = [];
		$headerIncomeArray = [];
		$owner = [];
		
		$headerAccountOwnerValueArray = [];
		$headerIncomeValueArray = [];
		$gross_income = 0;
		$taxable_income = 0;
		$finance_account_value =0;
		
		$headerAccountOwnerArray[] = 'Year';
		foreach($current_financial_account as $key=>$acount)
		{
			
			$account_owner = $acount->account_owner == 1 ? 'Husband' : ($acount->account_owner == 2 ? 'Wife' : 'Joint');
			if(!in_array($account_owner, $owner))
			{
				$headerAccountOwnerArray[] =  $account_owner;
				$owner[] = $account_owner;
			}
			
			
			$headerAccountTitleArray[] = $acount->account_owner == 1 ? 'Husband '.$acount->account_title : ($acount->account_owner == 2 ? 'Wife '.$acount->account_title : 'Joint '.$acount->account_title);
			
			// respective values of above titles
			$ageData = Client_portfolio_Desires::where('id', $acount->sl_no)->first();
			$husbandAge = $ageData ? $ageData->client_age : '';
			$wifeAge = $ageData ? $ageData->partner_age : '';
			if($key == 0)
			{
				$headerAccountOwnerValueArray[] = $key+1;
				$headerAccountOwnerValueArray[] = $husbandAge;
				$headerAccountOwnerValueArray[] = $wifeAge;
			}
			
			$headerAccountOwnerValueArray[] = $acount->account_value;
			$finance_account_value = $finance_account_value +$acount->account_value;
		}
		
		foreach($current_income_account as $income_src)
		{
			$headerIncomeArray[] = $income_src->client_name;
			
			// respective values of above titles
			$headerIncomeValueArray[] = $income_src->income_amount;
			$gross_income = $gross_income + $income_src->income_amount;
			$taxable_income = $taxable_income + $income_src->income_amount;
		}
		
		if($current_income_account->isNotEmpty())
		{
			$headerIncomeArray[] = 'Gross Income';
			$headerIncomeArray[] = 'Taxable Income';
			$headerIncomeArray[] = 'Income Goal';
			$headerIncomeArray[] = 'Gap From Assets';
			$headerIncomeArray[] = 'IRMAA';
			$headerIncomeArray[] = 'Tax Rates';
			$headerIncomeArray[] = 'Irs Partner';
			$headerIncomeArray[] = 'Total Estate';
			
			$headerIncomeValueArray[] = $gross_income;
			$headerIncomeValueArray[] = $taxable_income;
			
			$headerIncomeValueArray[] = ''; // income goal
			$headerIncomeValueArray[] = ''; // Gap From Assets
			$headerIncomeValueArray[] = ''; // IRMAA
			$headerIncomeValueArray[] = ''; // Tax Rates
			$headerIncomeValueArray[] = ''; // Irs Partner
			$headerIncomeValueArray[] = $finance_account_value; // Total Estate
		}
		
		$headerArray = array_merge($headerAccountOwnerArray,$headerAccountTitleArray,$headerIncomeArray);
		
		$headerValueArray = array_merge($headerAccountOwnerValueArray,$headerIncomeValueArray);
		//echo "<pre>";print_r($headerArray);
		//echo "<pre>";print_r($headerValueArray);die;
		//------
		
		$data = [
			"created_at" => $portfolio_Desire_data->created_at ?? '',
			"client_nm" => $portfolio_Desire_data->client_name ?? '',
			"partner_nm" => $portfolio_Desire_data->partner_name ?? '',
            "current_position" => $portfolio_Desire_data->current_portfolio_value ?? '',
            "current_age" => $portfolio_Desire_data->client_age ?? '',
            "retirement_age" => $portfolio_Desire_data->desired_retirement_age ?? '',
            "desired_retirement_income" => $portfolio_Desire_data->desired_gross_income_retirement ?? '',
            "cola" => $portfolio_Desire_data->COLA ?? '',
            "growth_allocation" => $portfolio_Desire_data->current_portfolio_value ?? '',
            "primary_goals" => $portfolio_Desire_data->RIPG ?? '',
            "wife_annuity" => "2,377,000",
            "husband_annuity" => "803,952",
            "joint_401k" => "156,000",
            "asset_total" => "3,853,752",
            "income_total" => "61,536",
            "wife_ss" => "35,772",
            "husband_ss" => "25,764",
            "current_financial_account" => $current_financial_account,
            "current_income_account" => $current_income_account,
            "excelheaderArray" => $headerArray,
            "excelheaderValueArray" => $headerValueArray,
        ];
		
		
		
		$pdf = app('dompdf.wrapper');
		$contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);
		$pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->getDomPDF()->setHttpContext($contxt);
		$pdf->loadView('income-plan-pdf', $data);
		
		//return view('income-plan-pdf', $data);
		return $pdf->download('income-plan.pdf');
	}
}
