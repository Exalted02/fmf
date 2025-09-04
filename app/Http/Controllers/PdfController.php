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

		$roth = $this->rothConversionPage();
		
		$data = $this->current_financial_account_page();
		//echo "<pre>";print_r($data);die;
		
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
		
		return view('income-plan-pdf', $data, $roth);
		return $pdf->download('income-plan.pdf');
	}
	public function current_financial_account_page()
	{
		
		$lastId = Client_portfolio_Desires::where('user_id', auth()->user()->id)->latest('id')->value('id');
		//$lastId = 4;
		
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
			/*if(!in_array($account_owner, $owner))
			{
				$headerAccountOwnerArray[] =  $account_owner;
				$owner[] = $account_owner;
			}*/
			
			if($key == 0)
			{
				$headerAccountOwnerArray[] = 'Husband';
			}
			
			if($key == 1)
			{
				$headerAccountOwnerArray[] = 'Wife';
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
			}
			
			
			
			/*if (stripos($acount->account_title, '401K') !== false)
			{
				$headerAccountTitleArray[] = 'RMD';
				$headerAccountTitleArray[] = 'Value';
			}*/
			
			if(stripos($acount->account_title, 'Annuity') !== false)
			{
				if($acount->account_owner == 1)
				{
					$headerAccountTitleArray[] = 'Husband RMD/Income';
				}
				//$headerAccountTitleArray[] = 'RMD';
				if($acount->account_owner == 2)
				{
					$headerAccountTitleArray[] = 'RMD';
					$headerAccountTitleArray[] = 'Wife RMD/Income';
				}
				
				if($acount->account_owner == 3)
				{
					$headerAccountTitleArray[] = 'Joint RMD/Income';
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
		$joint_annuity_rmd_inc =0;
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
				$gross_income = 0;
				
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
					
					$currentYear = date('Y');
					$husband_dob = $currentYear - $husbandAge;
					//echo $husband_dob; die;
					$husband_age_rmd = 74;
					$wife_age_rmd = 73;
					if($husband_dob >= 1960)
					{
						$husband_age_rmd = 76;
						$wife_age_rmd = 75;
					}
					
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
					if($acount->tax_qualification == 1    && stripos($acount->account_title, 'Annuity') === false)
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
							/*foreach ($previous_tax_quali_arr as $val) {
								$previous_tax_quali_data_arr[] = $val;
							}*/
							
							if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
							{
								$percentRmd = distribution_period()[$new_wife_age][0];
								$rmd = $previous_tax_quali_arr[$key] / $percentRmd;
								$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd);
								//echo $rmd;die;
								$account_value = number_format($current_tax_value);
								$percentRmd = distribution_period()[$new_wife_age][0];
								
								
								
								//$rmd = $previous_tax_quali_arr[$key] / $percentRmd;
								//$previous_tax_quali_arr[$k] = $current_tax_value;
								
								
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
								if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
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
								if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								{
									$percentRmd = distribution_period()[$new_wife_age][0];
									
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
						
						//---- 03-09-2025------
						if($acount->account_owner == 3)
						{
							if($i==0)
							{
								$joint_annuity = $acount->account_value;
								$finance_account_value = $finance_account_value + $joint_annuity;
							}
							else
							{
								$previous_joint_annuity =  $joint_annuity;
								if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								{
									$rmd_husband = $previous_joint_annuity * 0.055932;
									$joint_annuity = $previous_joint_annuity * 1.045 - $rmd_husband;
									$account_value = number_format($joint_annuity);
									$finance_account_value = $finance_account_value + $joint_annuity;
								}
								else
								{
									$joint_annuity = $joint_annuity * 1.045;
									$account_value = number_format($joint_annuity);
									$finance_account_value = $finance_account_value + $joint_annuity;
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
						$gross_income = $gross_income + $nq_icome;
					}
					
					// calculation for tax_qualification = 1 (IRA) RMD and Income
					if($acount->tax_qualification == 1   && stripos($acount->account_title, 'Annuity') === false)
					{
						if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
						{
							$percentRmd = distribution_period()[$new_wife_age][0];
							
							//------ 03-09-2025----
							$row[] = number_format($previous_tax_quali_arr[$key] / $percentRmd);

							$k401_rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							
							$rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd);
							$previous_tax_quali_arr[$key] = $current_tax_value;
							
							$gross_income = $gross_income + $rmd;
							
							//-------------------------
							
							//-------- previous ----
							//$row[] = number_format($previous_tax_quali_data_arr[$key] / $percentRmd);

							//$k401_rmd = $previous_tax_quali_data_arr[$key] / $percentRmd;
							//-----------------
							$vs++;
						}
						else
						{
							$rmd = 0;
							$k401_rmd = 0;
							$row[] = '';
							//$row[] = '';
							
							$current_tax_value = $previous_tax_quali_arr[$key] * 1.05;
							//$previous_tax_quali_arr[$key] = $current_tax_value;
						}
						
					}
					
					if (stripos($acount->account_title, 'Annuity') !== false)
					{
						if($acount->account_owner == 1)
						{
							if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
							{
								//$percentRmd = percent_k401_yearly()[$i];
								$row[] = number_format($previous_husband_annuity * 0.055932);
								$husband_annuity_rmd_inc = $previous_husband_annuity * 0.055932;
								$gross_income = $gross_income + $husband_annuity_rmd_inc;
							}
							else 
							{
								$husband_annuity_rmd_inc = 0;
								$row[] = '';
								$gross_income = $gross_income + $husband_annuity_rmd_inc;
								//$previous_annuity = $acount->account_value;
							}
						}
						
						if($acount->account_owner == 2)
						{
							if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
							{
								$annual_income_value = Current_financial_account::where('sl_no', $lastId)->where('user_id', auth()->user()->id)->where('account_owner', 2)->where('account_title','LIKE', '%annuity%')->first();
								$wife_annuity_rmd_inc = $annual_income_value ? $annual_income_value->annual_income_value : 0;
								
								$percentRmd = distribution_period()[$new_wife_age][0];
								$row[] = number_format($previous_wife_annuity / $percentRmd);
								$row[] = $wife_annuity_rmd_inc;
								//$row[] = '$134,475';
								//$wife_annuity_rmd_inc = 134475;
								
								$gross_income = $gross_income + $wife_annuity_rmd_inc;
								
							}
							else 
							{
								$wife_annuity_rmd_inc = 0;
								$row[] = '';
								$row[] = '';
								$gross_income = $gross_income + $wife_annuity_rmd_inc;
								//$previous_annuity = $acount->account_value;
							}
						}
						
						if($acount->account_owner == 3)
						{
							if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
							{
								//$percentRmd = percent_k401_yearly()[$i];
								$row[] = number_format($previous_joint_annuity * 0.055932);
								$joint_annuity_rmd_inc = $previous_joint_annuity * 0.055932;
								
								$gross_income = $gross_income + $joint_annuity_rmd_inc;
							}
							else 
							{
								$joint_annuity_rmd_inc = 0;
								$row[] = '';
								$gross_income = $gross_income + $joint_annuity_rmd_inc;
								//$previous_annuity = $acount->account_value;
							}
						}
						
					}
				}
				
				
				if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
				{
					$row[] = distribution_period()[$new_wife_age][0];	
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
						$gross_income = $gross_income + $income_src->income_amount;
					}
					else
					{
						$current_inc_value =  $previous_income_arr[$k] * 1.025; 
						$account_value = number_format($current_inc_value);
						$previous_income_arr[$k] = $current_inc_value;
						$sum_current_inc = $sum_current_inc + $current_inc_value;
						
						$gross_income = $gross_income + $current_inc_value;
					}
					
					// calculation for income goal
					$desired_gross_income_retirement = $portfolio_Desire_data ? $portfolio_Desire_data->desired_gross_income_retirement : 0 ; 
					$COLA = $portfolio_Desire_data ? $portfolio_Desire_data->COLA : 0;
					
					
					//------------------------
					$row[] = $account_value;
					//$row[] = $income_src->income_amount;
					//$headerIncomeValueArray[] = $income_src->income_amount;
					//$gross_income = $nq_icome + $k401_rmd + $wife_annuity_rmd_inc + $husband_annuity_rmd_inc + $sum_current_inc;
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
            "lastId" => $lastId,
        ];
		
		return $data;
	}
	public function rothConversionPage()
	{
		$data = [];
		$headerAge = [];
		$headerAge[] = 'Roth Conversion';
		$headerAge[] = '';
		//-calculation of total value-husband roth calculation---
		$lastId = Client_portfolio_Desires::where('user_id', auth()->user()->id)->latest('id')->value('id');
		$current_finance_husband_data = Current_financial_account::where('sl_no', $lastId)->where('account_owner', 2)->where('account_title', 'LIKE', '%Annuity%')->first();
		$husband_account_value = $current_finance_husband_data ? $current_finance_husband_data->account_value : '';
		
		$a12 = 0;
		$a14 = 0;
		$a17 = 0;
		$a20 = 0;
		$J_16 = 0;
		$index17_previous = 0;
		$index19_previous = 0;
			
		for($col = 1; $col <= 13; $col++)
		{
			$index12_previous = 0;
			for($row = 1; $row <= 9; $row++)
			{
				$h_acc_value = $husband_account_value ?? '';
				$a12 = round($h_acc_value * 0.21);
				$a14 =  $h_acc_value + $a12;
				$a17 =  round($a14 * 1.05);
				$a20_pre = round($a17/6);
				
				$a20 =  round($a17/6);
				$index = $col.$row;
				if($col >= 3 && $col<=9)
				{
					if($row == 1 && $col<=8)
					{
						if($index17_previous == 0 && $index19_previous == 0)
						{		
							$index12_previous = $a17;
						}
						else{
						   $index12_previous = $index17_previous-$index19_previous;
						}
					}
					elseif($row == 2 && $col<=8)
					{
						$index13_previous = $index12_previous*0.0095;
					}
					elseif(($col >=3 && $col <= 8) && $row ==3)
					{
						
					}
					elseif($row == 4 && $col<=8)
					{
						$index15_previous = $a20*0.22;
					}
					elseif($row == 5 && $col<=8)
					{
						if($col == 3)
						{
							$C16 = round($a20-$index15_previous);
						}
						
						if($col == 4)
						{
							$D_16= $a20-$index15_previous;
						}
						
						if($col == 5)
						{
							$E_16= $a20-$index15_previous;
						}
						
						if($col == 6)
						{
							$F_16= $a20-$index15_previous;
						}
						
						if($col == 7)
						{
							$G_16= $a20-$index15_previous;
						}
						
						if($col == 8)
						{
							$H_16= $a20-$index15_previous;
						}
					}
					elseif($row == 6 && $col<=8)
					{
						$index17_previous = ($index12_previous-$index13_previous-$a20) * 1.05;
												
						if($col == 3)
						{
							$C_17 = ($index12_previous-$index13_previous-$a20) * 1.05;
						}
						
						if($col == 4)
						{
							$D_17 = ($index12_previous-$index13_previous-$a20) * 1.05;
						}
						
						if($col == 5)
						{
							$E_17 = ($index12_previous-$index13_previous-$a20) * 1.05;
						}
						
						if($col == 6)
						{
							$F_17 = ($index12_previous-$index13_previous-$a20) * 1.05;
						}
						
						if($col == 7)
						{
							$G_17 = ($index12_previous-$index13_previous-$a20) * 1.05;
						}
						
						if($col == 8)
						{
							$H_17 = ($index12_previous-$index13_previous-$a20) * 1.05;
						}
					}
					elseif($row == 8)
					{
						$index19_previous = 0;
						if($col == 9)
						$h_19 = distribution_period()[76][1];
					}
					
					if($row==1 && $col==9)
					$i12 = round($index17_previous - distribution_period()[76][1]);
					
					if($row==2 && $col==9)
					$i13 = $i12 * 0.0095;
					
					if($row==3 && $col==9)
					$i14 = $i12 - $i13;
					
					if($row==4 && $col==9)
					$i15 = $i14 * 0.22;
					
					if($row==5 && $col==9)
					$i16 = $i14 - $i15;
				
					if($row==6 && $col==9)
					$i17 = $i12 - $i13 - $i14;
				}
				
				if($col>10)
				{
					if($col==12)
					{
						if($row == 1)
						$L_14 = $C16*1.05;
						
						if($row == 2)
						$L_15 = ($L_14+ $D_16) * 1.05;
					
						if($row == 3)
						$L_16 = ($L_15+ $E_16) * 1.05;
						
						if($row == 4)
						$L_17 = ($L_16+ $F_16) * 1.05;
						
						if($row == 5)
						$L_18 = ($L_17+ $G_16) * 1.05;
					
						if($row == 6)
						$L_19 = ($L_18+ $H_16) * 1.05;
						
						if($row == 7)
						$L_20 = ($L_19+ $i16) * 1.05;
					}
					
					if($col==13)
					{
						if($row == 1)
						$M_14 = $C_17+$L_14;
						
						if($row == 2)
						$M_15 = $D_17+$L_15;
						
						if($row == 3)
						$M_16 = $E_17+$L_16;
						
						if($row == 4)
						$M_17 = $F_17+$L_17;
						
						if($row == 5)
						$M_18 = $G_17+$L_18;
						
						if($row == 6)
						$M_19 = $H_17+$L_19;
						
						if($row == 7)
						$M_20 = ($J_16+$L_20) * 1.05;
					}
				}
			}
		}
		//-------------------------------------------------------
		// dd($headerAge);
		return [
			'm_14'=>$M_14,
			'm_15'=>$M_15,
			'm_16'=>$M_16,
			'm_17'=>$M_17,
			'm_18'=>$M_18,
			'm_19'=>$M_19,
			'm_20'=>$M_20,
		];
	}
}
