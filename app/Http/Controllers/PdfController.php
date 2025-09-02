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
		
		// $rothConversionData = $this->rothConversionPage();
		$data = $this->current_financial_account_page();
		
		$pdf = app('dompdf.wrapper');
		$contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);
		$pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        //$pdf->getDomPDF()->setHttpContext($contxt);
		//$pdf->loadView('income-plan-pdf', $data)->setPaper('a4', 'landscape');
		
		return view('income-plan-pdf', $data);
		return $pdf->download('income-plan.pdf');
	}
	public function current_financial_account_page()
	{
		
		$lastId = Client_portfolio_Desires::where('user_id', auth()->user()->id)->latest('id')->value('id');
		$lastId = 5;
		
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
		$desired_gross_income_retirement = 0;
		
		$headerAccountOwnerArray[] = 'Year';
		$v = 0;
		$vs = 0;
		foreach($current_financial_account as $key=>$acount)
		{
			$account_owner = $acount->account_owner == 1 ? 'Husband' : ($acount->account_owner == 2 ? 'Wife' : 'Joint');
			if(!in_array($account_owner, $owner))
			{
				$headerAccountOwnerArray[] =  $account_owner;
				$owner[] = $account_owner;
			}
			
			
			$headTitle = $acount->account_owner == 1 ? 'Husband '.$acount->account_title : ($acount->account_owner == 2 ? 'Wife '.$acount->account_title : 'Joint '.$acount->account_title);
			
			$headerAccountTitleArray[] = $headTitle;
			
			if (stripos($acount->account_title, 'nq') !== false)
			{
				$headerAccountTitleArray[] = 'Income';
			}
			
			// here header loop extends according to tax_qualification fields
			 //&& preg_match('/\bsavings?\b/i', $acount->account_title)
			if($acount->tax_qualification == 1 && stripos($acount->account_title, 'Annuity') === false)
			{
				$headerAccountTitleArray[] = 'RMD';
				if($v==0)
				{
					//$headerAccountTitleArray[] = 'Value';
				}
				
				$v++;
			}
			
			
			
			/*if (stripos($acount->account_title, '401K') !== false)
			{
				$headerAccountTitleArray[] = 'RMD';
				$headerAccountTitleArray[] = 'Value';
			}*/
			
			if (stripos($acount->account_title, 'Annuity') !== false)
			{
				$headerAccountTitleArray[] = 'RMD';
				if($acount->account_owner == 2)
				{
					$headerAccountTitleArray[] = 'RMD/Income';
				}
			}
			
		}
		
		$headerAccountTitleArray[] = 'Value';
		
		$j=0;
		$savings = 0;
		$nq = 0;
		$k401 = 0;
		$wife_ss = 0;
		$husband_ss = 0;
		$previous_annuity = '';
		$previous_wife_annuity = '';
		$wife_annuity = 0;
		$husband_annuity = 0;
		$COLA = 0;
		$nq_icome =0;
		$k401_rmd =0;
		$wife_annuity_rmd_inc =0;
		$husband_annuity_rmd_inc =0;
		$current_inc_value =0;
		$previous_income_arr = [];
		$previous_tax_quali_arr = [];
		$previous_tax_quali_data_arr = [];
		$sum_current_inc = 0;
		$current_tax_value = 0;
		$annual_income_value = 0;
		$previous_savings = [];
		$previous_nq = [];
		
		for($i=0; $i<=25; $i++)
		{
				$row = [];
				$finance_account_value = 0;
				
				foreach($current_financial_account as $key=>$acount)
				{
					$ageData = Client_portfolio_Desires::where('id', $acount->sl_no)->first();
					$husbandAge = $ageData ? $ageData->client_age : '';
					$wifeAge = $ageData ? $ageData->partner_age : '';
					
					
					if($key == 0)
					{
						$row[] = $i;
						$row[] = $husbandAge + $j;
						$row[] = $wifeAge + $j;
					}
					
					$new_husband_age = $husbandAge + $j;
					$new_wife_age = $wifeAge + $j;
					//----- calculation part ----------
					$account_value = number_format($acount->account_value);
					
					//if(strpos($acount->account_title, 'Savings') !== false)
					//if ($acount->tax_qualification == 2 && stripos($acount->account_title, 'Savings') !== false)

					if ($acount->tax_qualification == 2 && preg_match('/\bsavings?\b/i', $acount->account_title))
					{
						if($i==0)
						{
							$savings =  $acount->account_value;
							$finance_account_value = $finance_account_value + $savings;
							$previous_savings[$key] = $savings;
						}
						else{
							//echo $previous_savings[$key];die;
							$savings =  $previous_savings[$key] * 1.0275;
							$account_value = number_format($savings);
							$previous_savings[$key] = $savings;
							$finance_account_value = $finance_account_value + $savings;
							
							//------ 01-09-2025---
							//$savings =  $savings * 1.0275;
							//$account_value = number_format($savings);
							//$finance_account_value = $finance_account_value + $savings;
						}
					}
					
					if (stripos($acount->account_title, 'nq') !== false) 
					{
						if($i==0)
						{
							$nq =  $acount->account_value;
							$finance_account_value = $finance_account_value + $nq;
							$previous_nq[$key] =  $nq;
						}
						else
						{
							$nq =  ($previous_nq[$key] * 1.035) - 26375; // subtract from income (F)
							$account_value = number_format($nq);
							$previous_nq[$key] = $nq;
							$finance_account_value = $finance_account_value + $nq;
							//----- 01-09-2025------
							//$nq =  ($nq * 1.035) - 26375; // subtract from income (F)
							//$account_value = number_format($nq);
							//$finance_account_value = $finance_account_value + $nq;
						}
					}
					
					// here loop should extends according to tax_qualification fields
					if($acount->tax_qualification == 1)
					{
						if($i==0)
						{
							//$k401 =  $acount->account_value;
							$previous_tax_quali_arr[$key] = $acount->account_value;
							//$account_value = $acount->account_value;
						}
						else
						{
							//$k401_previous =  $k401;
							foreach ($previous_tax_quali_arr as $val) {
								$previous_tax_quali_data_arr[] = $val;
							}
							
							if($new_husband_age >=74 && $new_wife_age >= 73)
							{
								$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd);
								$account_value = number_format($current_tax_value);
								$percentRmd = percent_k401_yearly()[$i];
								$rmd = $previous_tax_quali_arr[$key] / $percentRmd;
								$previous_tax_quali_arr[$k] = $current_tax_value;
								
								//$finance_account_value = $finance_account_value + $current_tax_value;
								
								/*$k401 =  round(($k401 * 1.05) - $rmd); 
								$account_value = number_format($k401);
								
								$percentRmd = percent_k401_yearly()[$i];
								$rmd = $k401_previous / $percentRmd;*/
							}
							else
							{
								$rmd = 0;
								$current_tax_value = $previous_tax_quali_arr[$key] * 1.05;
								$account_value = number_format($current_tax_value); 
								$previous_tax_quali_arr[$key] = $current_tax_value;
								
								//$finance_account_value = $finance_account_value + $current_tax_value;
								
								/*$rmd = 0;
								$k401 =  $k401 * 1.05; 
								$account_value = number_format($k401);*/
							}
							//echo $finance_account_value;die;
						}
					}
					
					//-- 29-08-2025-----
					/*if (stripos($acount->account_title, '401k') !== false) 
					{
						if($i==0)
						{
							$k401 =  $acount->account_value;
						}
						else
						{
							$k401_previous =  $k401;
							
							if($new_husband_age >=74 && $new_wife_age >= 73)
							{
								$k401 =  round(($k401 * 1.05) - $rmd); 
								$account_value = number_format($k401);
								
								$percentRmd = percent_k401_yearly()[$i];
								$rmd = $k401_previous / $percentRmd;
							}
							else
							{
								$rmd = 0;
								$k401 =  $k401 * 1.05; 
								$account_value = number_format($k401);
							}
							
						}
					}*/
					//-----------------
					
					if (stripos($acount->account_title, 'Annuity') !== false)
					{
						if($acount->account_owner == 1)
						{
							if($i==0)
							{
								$husband_annuity = $acount->account_value;
								$finance_account_value = $finance_account_value + $husband_annuity;
							}
							else
							{
								$previous_husband_annuity =  $husband_annuity;
								if($new_husband_age >=73 && $new_wife_age >= 72)
								{
									$rmd_husband = $previous_husband_annuity * 0.055932;
									$husband_annuity = $previous_husband_annuity * 1.045 - $rmd_husband;
									$account_value = number_format($husband_annuity);
									$finance_account_value = $finance_account_value + $husband_annuity;
								}
								else
								{
									$husband_annuity = $husband_annuity * 1.045;
									$account_value = number_format($husband_annuity);
									
									$finance_account_value = $finance_account_value + $husband_annuity;
								}
							}
						}
						
						if($acount->account_owner == 2)
						{
							if($i==0)
							{
								//$previous_wife_annuity = $acount->account_value;
								$wife_annuity = $acount->account_value;
								$finance_account_value = $finance_account_value + $wife_annuity;
								
							}
							else
							{
								$previous_wife_annuity =  $wife_annuity;
								if($new_husband_age >=74 && $new_wife_age >= 73)
								{
									$percentRmd = percent_k401_yearly()[$i];
									
									$rmd_wife = $previous_wife_annuity / $percentRmd;
									
									$wife_annuity = ($wife_annuity * 1.045) - $rmd_wife;
									$account_value = number_format($wife_annuity);
									$finance_account_value = $finance_account_value + $wife_annuity;
								}
								else
								{
									//$previous_wife_annuity =  $wife_annuity;
									$wife_annuity = $wife_annuity * 1.045;
									
									$account_value = number_format($wife_annuity);
									
									$finance_account_value = $finance_account_value + $wife_annuity;
								}
							}
						}
					}
					//---------------------------------
					
					//$row[] = $acount->account_value;
					$row[] = $account_value;
					
					if (stripos($acount->account_title, 'nq') !== false)
					{
						$row[] = '$26375';
						$nq_icome = 26375;
					}
					
					// calculation for tax_qualification = 1 (IRA) RMD and Income
					if($acount->tax_qualification == 1   && stripos($acount->account_title, 'Annuity') === false)
					{
						if($new_husband_age >=74 && $new_wife_age >= 73)
						{
							$percentRmd = percent_k401_yearly()[$i];
							//echo $k401_previous; die;
							
							$row[] = number_format($previous_tax_quali_data_arr[$key] / $percentRmd);

							//$row[] = percent_k401_yearly()[$i];
							
							$k401_rmd = $previous_tax_quali_data_arr[$key] / $percentRmd;
							$vs++;
						}
						else
						{
							$k401_rmd = 0;
							$row[] = '';
							//$row[] = '';
						}
						
					}
					
					//-- 29-08-2025------
					/*if (stripos($acount->account_title, '401K') !== false)
					{
						if($new_husband_age >=74 && $new_wife_age >= 73)
						{
							$percentRmd = percent_k401_yearly()[$i];
							//echo $k401_previous; die;
							
							$row[] = number_format($k401_previous / $percentRmd);
							$row[] = percent_k401_yearly()[$i];
							
							$k401_rmd = $k401_previous / $percentRmd;
						}
						else
						{
							$k401_rmd = 0;
							$row[] = '';
							$row[] = '';
						}
					}*/
					//-----------
					
					if (stripos($acount->account_title, 'Annuity') !== false)
					{
						if($acount->account_owner == 1)
						{
							if($new_husband_age >=73 && $new_wife_age >= 72)
							{
								//$percentRmd = percent_k401_yearly()[$i];
								$row[] = number_format($previous_husband_annuity * 0.055932);
								$husband_annuity_rmd_inc = $previous_husband_annuity * 0.055932;
							}
							else 
							{
								$husband_annuity_rmd_inc = 0;
								$row[] = '';
								//$previous_annuity = $acount->account_value;
							}
						}
						
						if($acount->account_owner == 2)
						{
							if($new_husband_age >=74 && $new_wife_age >= 73)
							{
								$annual_income_value = Current_financial_account::where('sl_no', $lastId)->where('user_id', auth()->user()->id)->where('account_owner', 2)->where('account_title','LIKE', '%annuity%')->first();
								$wife_annuity_rmd_inc = $annual_income_value ? $annual_income_value->annual_income_value : 0;
								
								$percentRmd = percent_k401_yearly()[$i];
								$row[] = number_format($previous_wife_annuity / $percentRmd);
								$row[] = $wife_annuity_rmd_inc;
								//$row[] = '$134,475';
								//$wife_annuity_rmd_inc = 134475;
								
								
								
							}
							else 
							{
								$wife_annuity_rmd_inc = 0;
								$row[] = '';
								$row[] = '';
								//$previous_annuity = $acount->account_value;
							}
						}
						
					}
					
					/*if (stripos($acount->account_title, 'Savings') !== false || stripos($acount->account_title, 'nq') !== false || stripos($acount->account_title, '401k') !== false || stripos($acount->account_title, 'Annuity') !== false) 
					{
						$finance_account_value = $finance_account_value + $acount->account_value;
					}
					
					if($i > 0)
					{
						$finance_account_value = $savings + $nq + $k401 +$wife_annuity + $husband_annuity;
					}*/
					
					//------ store data to another array for tax_qualification
					
					/*foreach ($previous_tax_quali_arr as $val) {
						$previous_tax_quali_data_arr[] = $val;
					}*/
					
				}
				
				
				if($new_husband_age >=74 && $new_wife_age >= 73)
				{
					$row[] = percent_k401_yearly()[$i];	
				}
				else
				{
					$row[] = '';
				}

				
				foreach($current_income_account as $k=>$income_src)
				{
					$account_value = number_format($income_src->income_amount);
					if($i==0)
					{
						$previous_income_arr[$k] = $income_src->income_amount;
						$sum_current_inc = $sum_current_inc + $income_src->income_amount;
					}
					else
					{
						$current_inc_value =  $previous_income_arr[$k] * 1.025; 
						$account_value = number_format($current_inc_value);
						$previous_income_arr[$k] = $current_inc_value;
						$sum_current_inc = $sum_current_inc + $current_inc_value;
					}
					
					
					
					/*if (stripos($income_src->client_name, 'wife ss') !== false)
					{
						if($i==0)
						{
							$wife_ss =  $income_src->income_amount;
						}
						else{
							$wife_ss =  $wife_ss * 1.025; 
							$account_value = number_format($wife_ss);
						}
					}
					
					if (stripos($income_src->client_name, 'husband ss') !== false) 
					{
						if($i==0)
						{
							$husband_ss =  $income_src->income_amount;
							//echo $income_src->income_amount;die;
						}
						else{
							$husband_ss =  $husband_ss * 1.025; 
							$account_value = number_format($husband_ss);
						}
					}*/
					
					// calculation for income goal
					$desired_gross_income_retirement = $portfolio_Desire_data ? $portfolio_Desire_data->desired_gross_income_retirement : 0 ; 
					$COLA = $portfolio_Desire_data ? $portfolio_Desire_data->COLA : 0;
					
					
					//------------------------
					$row[] = $account_value;
					//$row[] = $income_src->income_amount;
					//$headerIncomeValueArray[] = $income_src->income_amount;
					
					
					//$gross_income = $nq_icome + $k401_rmd + $wife_annuity_rmd_inc + $husband_annuity_rmd_inc + $wife_ss + $husband_ss;
					//$taxable_income = $gross_income;
					
					$gross_income = $nq_icome + $k401_rmd + $wife_annuity_rmd_inc + $husband_annuity_rmd_inc + $sum_current_inc;
					$taxable_income = $gross_income;
				}
				
				if($i==0)
				{
					$income_goal = $desired_gross_income_retirement;
				}
				else
				{
					$income_goal = round($income_goal * (1 + $COLA / 100));
				}
				
				// calculation for IRMAA
				$irmaaVal = 0;
				if($taxable_income > 212000 && $taxable_income <= 266000)
				{
					$irmaaVal = 6216;
				}
				else if($taxable_income > 266000 && $taxable_income <= 334000)
				{
					$irmaaVal = 8880;
				}
				else if($taxable_income > 334000 && $taxable_income <= 400000)
				{
					$irmaaVal = 11541.60;
				}
				else if($taxable_income > 400000 && $taxable_income <= 750000)
				{
					$irmaaVal = 1419040;
				}
				
				//---- tax rate percent --
				$tax_rate = 0;
				if($taxable_income > 0 && $taxable_income <= 23850)
				{
					$tax_rate = 10; // %
				}
				else if($taxable_income >= 23851 && $taxable_income <= 96950)
				{
					$tax_rate = 12; // %
				}
				else if($taxable_income >= 96951 && $taxable_income <= 206700)
				{
					$tax_rate = 22; // %
				}
				else if($taxable_income >= 206701 && $taxable_income <= 394600)
				{
					$tax_rate = 24; // %
				}
				else if($taxable_income >= 394601 && $taxable_income <= 501050)
				{
					$tax_rate = 32; // %
				}
				else if($taxable_income >= 501051 && $taxable_income <= 751600)
				{
					$tax_rate = 35; // %
				}
				else if($taxable_income >=751601)
				{
					$tax_rate = 37; // %
				}
				
				
				//------- irs partner -----
				$irs_partner = 0;
				if($i==0)
				{
					$irs_partner = round(($income_goal * $tax_rate) / 100);
				}
				else 
				{
					$irs_partner = round(($taxable_income * $tax_rate) / 100);
				}
				//-------
				
				$row[] = number_format($gross_income);
				$row[] = number_format($taxable_income);
				$row[] = number_format($income_goal);
				$row[] = number_format($income_goal - $gross_income);
				$row[] = number_format($irmaaVal);
				$row[] = $tax_rate .'%';
				$row[] = number_format($irs_partner);
				$row[] = number_format($finance_account_value);
				
				$headerAccountOwnerValueArray[$i] = $row;
				
				$j++;
			
		} // end for loop
		
		//---------------x-----------------------------
		foreach($current_income_account as $income_src)
		{
			$headerIncomeArray[] = $income_src->client_name;
			
			// respective values of above titles
			/*$headerIncomeValueArray[] = $income_src->income_amount;
			$gross_income = $gross_income + $income_src->income_amount;
			$taxable_income = $taxable_income + $income_src->income_amount;*/
		}
		
		for($i=1; $i<=25; $i++)
		{
			$row = [];
			foreach($current_income_account as $income_src)
			{
				
				$row[] = $income_src->income_amount;
				//$headerIncomeValueArray[] = $income_src->income_amount;
				$gross_income = $gross_income + $income_src->income_amount;
				$taxable_income = $taxable_income + $income_src->income_amount;
				
				
			}
			$headerIncomeValueArray[$i] = $row;
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
			
			//$headerIncomeValueArray[] = ''; // income goal
			//$headerIncomeValueArray[] = ''; // Gap From Assets
			//$headerIncomeValueArray[] = ''; // IRMAA
			//$headerIncomeValueArray[] = ''; // Tax Rates
			//$headerIncomeValueArray[] = ''; // Irs Partner
			//$headerIncomeValueArray[] = $finance_account_value; // Total Estate
		}
		
		$headerArray = array_merge($headerAccountOwnerArray,$headerAccountTitleArray,$headerIncomeArray);
		
		//echo "<pre>";print_r($headerAccountOwnerValueArray);die;
		//echo "<pre>";print_r($headerIncomeValueArray);die;
		
		
		//$headerValueArray = array_merge($headerAccountOwnerValueArray,$headerIncomeValueArray);
		$headerValueArray = $headerAccountOwnerValueArray;
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
		
		return $data;
	}
	public function rothConversionPage()
	{
		$data = [];
		$headerAge = [];
		$headerAge[] = 'Roth Conversion';
		$headerAge[] = '';
		// dd($headerAge);
		return $data;
	}
}
