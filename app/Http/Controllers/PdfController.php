<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Client_portfolio_Desires;
use App\Models\Current_financial_account;
use App\Models\Guaranteed_income_sources;
use Illuminate\Support\Facades\Session;
use App\Models\Roth_conversion_year;

class PdfController extends Controller
{
	public function incomePlan()
	{
		if(empty(auth()->user()->id))
		{
			return redirect('login');
		}
		
		$sl_no = Session::get('sl_no');
		if(!empty($sl_no))
		{
			$lastId = $sl_no;
		}else{
			$lastId = Client_portfolio_Desires::where('user_id', auth()->user()->id)->latest('id')->value('id');
		}
		
		$roth = $this->rothConversionPage($lastId);
		//echo "<pre>";print_r($roth);die;
		$data = $this->current_financial_account_page($lastId);
		//echo "<pre>";print_r($data);die;
		$plan_allo_header = $this->allocation_plan_details($lastId);
		//echo "<pre>";print_r($plan_allo_header);die;
		
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
		//$pdf->loadView('income-plan-pdf', $data, $roth)->setPaper('a4', 'landscape');
		
		return view('income-plan-pdf',  array_merge($plan_allo_header, $data, $roth));
		
		return $pdf->download('income-plan.pdf');
	}
	public function current_financial_account_page($lastId)
	{
		
		// $lastId = Client_portfolio_Desires::where('user_id', auth()->user()->id)->latest('id')->value('id');
		
		//echo $lastId; die;
		
		$portfolio_Desire_data = Client_portfolio_Desires::with(['get_representative_details'])->where('user_id', auth()->user()->id)->where('id', $lastId)->first();
		
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
		$headerAccountOwnerArray[] = $portfolio_Desire_data->client_name ?? '';
		
		if(!empty($portfolio_Desire_data->partner_name))
		$headerAccountOwnerArray[] = $portfolio_Desire_data->partner_name ?? '';
		
		
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
			
			/*if($key == 0)
			{
				$headerAccountOwnerArray[] = 'Husband';
			}
			
			if($key == 1)
			{
				$headerAccountOwnerArray[] = 'Wife';
			}*/
			
			
			$headTitle = $acount->account_owner == 1 ? $acount->owner_name.' '.$acount->account_title : ($acount->account_owner == 2 ? $acount->owner_name.' '.$acount->account_title : $acount->owner_name.' '.$acount->account_title);
			
			$headerAccountTitleArray[] = $headTitle;
			
			//if (stripos($acount->account_title, 'nq') !== false)
				
			if ($acount->tax_qualification == 2 && !preg_match('/\bsavings?\b/i', $acount->account_title))
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
					//$headerAccountTitleArray[] = 'Husband RMD/Income';
					$headerAccountTitleArray[] = $acount->owner_name.' '.'RMD/Income';
				}
				//$headerAccountTitleArray[] = 'RMD';
				if($acount->account_owner == 2)
				{
					$headerAccountTitleArray[] = 'RMD';
					//$headerAccountTitleArray[] = 'Wife RMD/Income';
					$headerAccountTitleArray[] = $acount->owner_name.' '.'RMD/Income';
				}
				
				if($acount->account_owner == 3)
				{
					//$headerAccountTitleArray[] = 'Joint RMD/Income';
					$headerAccountTitleArray[] = $acount->owner_name.' '.'RMD/Income';
				}
			}
			
		}
		
		//$headerAccountTitleArray[] = 'Value';
		
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
		$rmd = [];
		$previous_joint_annuity = 0;
		
		
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
						
						if(!empty($wifeAge))
						$row[] = $wifeAge + $j;
					}
					
					$new_husband_age = $husbandAge + $j;
					$new_wife_age = $wifeAge + $j;
					//echo $new_wife_age; die;
					$currentYear = date('Y');
					$husband_dob = $currentYear - $husbandAge;
					
					$wife_dob = $currentYear - $wifeAge;
					//echo $husband_dob; die;
					//$husband_age_rmd = 74; // 15-09-2025
					//$wife_age_rmd = 73; // 15-09-2025
					
					/*$husband_age_rmd = $acount->age_income_start ?? '';
					$wife_age_rmd = $acount->age_income_start ?? '';
					
					$rmd_start_age  = $acount->rmd_start_age ?? '';
					//if($husband_dob >= 1960)
						
					if(($husband_dob >= 1960 && $acount->account_owner==1) || ($wife_dob >= 1960 && $acount->account_owner==2))
					{
						$husband_age_rmd = 75;
						$wife_age_rmd = 75;
						
						$rmd_start_age = 75;
					}*/
					
					//----- rmd age calculation-------
					$husband_age_rmd = '0000';
					$wife_age_rmd = '0000';
					if($acount->account_owner == 1)
					{
						$husband_age_rmd = $acount->rmd_start_age ?? '0000';
						$husband_dob = $currentYear - $husband_age_rmd;
						if($husband_dob >= 1960)
						{
							$husband_age_rmd = 75;
						}
					}
					
					if($acount->account_owner == 2)
					{
						$wife_age_rmd = $acount->rmd_start_age ?? '0000';
						
						$wife_dob = $currentYear - $wife_age_rmd;
						if($wife_dob >= 1960)
						{
							$wife_age_rmd = 75;
						}
					}
					
					if($acount->account_owner == 1)
					{
						$joint_age_rmd = $acount->rmd_start_age ?? '0000';
						$husband_dob = $currentYear - $husband_age_rmd;
						if($husband_dob >= 1960)
						{
							$joint_age_rmd = 75;
						}
					}
					
					//----- calculation part ----------
					$account_value = number_format($acount->account_value);
					
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
						}
					}
					
					//if (stripos($acount->account_title, 'nq') !== false)
					if ($acount->tax_qualification == 2 && !preg_match('/\bsavings?\b/i', $acount->account_title))
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
							
						}
					}
					
					// here loop should extends according to tax_qualification fields = 1 and has no Annuity
					if($acount->tax_qualification == 1    && stripos($acount->account_title, 'Annuity') === false)
					{
						if($i==0)
						{
							//$k401 =  $acount->account_value;
							$previous_tax_quali_arr[$key] = $acount->account_value;
							//$account_value = $acount->account_value;
							$finance_account_value = $finance_account_value + $acount->account_value;
						}
						else
						{
							//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
							
							if($new_husband_age >=$husband_age_rmd && $acount->account_owner == 1)
							{
								//echo $new_husband_age; die;
								$percentRmd = distribution_period()[$new_husband_age][0];
								//$rmd[$key] = $previous_tax_quali_arr[$key] / $percentRmd;
								$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd[$key]);
								//echo "<pre>";print_r($rmd);die;
								$account_value = number_format($current_tax_value);
								$percentRmd = distribution_period()[$new_husband_age][0];
								//$percentRmd = distribution_period()[$new_wife_age][0]; 15-09-2025
								
								$finance_account_value = $finance_account_value + $current_tax_value;
								
							}
							else if($new_wife_age >= $wife_age_rmd  && $acount->account_owner == 2)
							{
								$percentRmd = distribution_period()[$new_wife_age][0];
								//$rmd[$key] = $previous_tax_quali_arr[$key] / $percentRmd;
								$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd[$key]);
								//echo "<pre>";print_r($rmd);die;
								$account_value = number_format($current_tax_value);
								$percentRmd = distribution_period()[$new_wife_age][0];
								
								$finance_account_value = $finance_account_value + $current_tax_value;
							}
							else
							{
								$rmd[$key] = 0;
								$current_tax_value = $previous_tax_quali_arr[$key] * 1.05;
								$account_value = number_format($current_tax_value); 
								$previous_tax_quali_arr[$key] = $current_tax_value;
								
								$finance_account_value = $finance_account_value + $current_tax_value;
							}
							//echo $finance_account_value;die;
						}
					}
					
					//--tax_qualification=1 and has annuity-----
					
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
								//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
									
								if($new_husband_age >=$husband_age_rmd && $acount->account_owner == 1)
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
								//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
									
								if($new_wife_age >=$wife_age_rmd  && $acount->account_owner == 2)
								{
									//echo $new_wife_age;die;
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
								//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								
								if($new_husband_age >=$joint_age_rmd && $new_wife_age >= $joint_age_rmd)
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
					//echo 'hii'.$previous_joint_annuity; die;
					//$row[] = $acount->account_value;
					$row[] = $account_value;
					
					//if (stripos($acount->account_title, 'nq') !== false)
						
					if ($acount->tax_qualification == 2 && !preg_match('/\bsavings?\b/i', $acount->account_title))
					{
						$row[] = '$26375';
						$nq_icome = 26375;
						$gross_income = $gross_income + $nq_icome;
					}
					
					// calculation for tax_qualification = 1 (IRA) RMD and Income has no Annuity
					if($acount->tax_qualification == 1   && stripos($acount->account_title, 'Annuity') === false)
					{
						//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
							
						if($new_husband_age >=$husband_age_rmd && $acount->account_owner == 1)
						{
							$percentRmd = distribution_period()[$new_husband_age][0];
							
							//------ 03-09-2025----
							$row[] = number_format($previous_tax_quali_arr[$key] / $percentRmd);

							$k401_rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							
							//$rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd[$key]);
							$rmd[$key] = $previous_tax_quali_arr[$key] / $percentRmd;
							
							$previous_tax_quali_arr[$key] = $current_tax_value;
							
							$gross_income = $gross_income + $rmd[$key];
							
							$vs++;
						}
						else if($new_wife_age >= $wife_age_rmd  && $acount->account_owner == 2)
						{
							$percentRmd = distribution_period()[$new_wife_age][0];
							
							//------ 03-09-2025----
							$row[] = number_format($previous_tax_quali_arr[$key] / $percentRmd);

							$k401_rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							
							//$rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd[$key]);
							$rmd[$key] = $previous_tax_quali_arr[$key] / $percentRmd;
							
							$previous_tax_quali_arr[$key] = $current_tax_value;
							
							$gross_income = $gross_income + $rmd[$key];
							
							$vs++;
						}
						else
						{
							$rmd[$key] = 0;
							$k401_rmd = 0;
							$row[] = '';
							//$row[] = '';
							
							$current_tax_value = $previous_tax_quali_arr[$key] * 1.05;
							//$previous_tax_quali_arr[$key] = $current_tax_value;
						}
						
					}
					
					
					// calculation for tax_qualification = 1 (IRA) RMD and Income has Annuity
					if (stripos($acount->account_title, 'Annuity') !== false)
					{
						if($acount->account_owner == 1)
						{
							//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								
							if($new_husband_age >=$husband_age_rmd)
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
							//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								
							if($new_wife_age >= $wife_age_rmd)
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
							//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								
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
				
				
				/*if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
				{
					//$row[] = distribution_period()[$new_wife_age][0];	
				}
				else
				{
					//$row[] = '';
				}*/

				
				foreach($current_income_account as $k=>$income_src)
				{
					$inc_src = $income_src->frequency == 1  ? $income_src->income_amount*12 : $income_src->income_amount;
					
					$account_value = number_format($inc_src);
					if($i==0)
					{
						// update 27-09-2025
						$previous_income_arr[$k] = $income_src->frequency == 1  ? $income_src->income_amount*12 : $income_src->income_amount;
						$inc_src = $income_src->frequency == 1  ? $income_src->income_amount*12 : $income_src->income_amount;
						$sum_current_inc = $sum_current_inc + $inc_src;
						$gross_income = $gross_income + $inc_src;
						
						// comment 27-09-2025
						/*$previous_income_arr[$k] = $income_src->income_amount;
						$sum_current_inc = $sum_current_inc + $income_src->income_amount;
						$gross_income = $gross_income + $income_src->income_amount;*/
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
					$irmaaVal = 14190.40;
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
				
				$row[] = number_format($income_goal);
				$row[] = number_format($gross_income);
				$row[] = number_format($taxable_income);
				//$row[] = number_format($income_goal);
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
			$headerIncomeArray[] = 'Income Goal';
			$headerIncomeArray[] = 'Gross Income';
			$headerIncomeArray[] = 'Taxable Income';
			//$headerIncomeArray[] = 'Income Goal';
			$headerIncomeArray[] = 'Gap From Assets';
			$headerIncomeArray[] = 'IRMAA';
			$headerIncomeArray[] = 'Tax Rates';
			$headerIncomeArray[] = 'IRS Partner';
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
			"representative" => $portfolio_Desire_data->get_representative_details->name ?? '',
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
	public function rothConversionPage($lastId)
	{
		$data = [];
		$headerAge = [];
		$headerAge[] = 'Roth Conversion';
		$headerAge[] = '';
		//-calculation of total value-husband roth calculation---
		// $lastId = Client_portfolio_Desires::where('user_id', auth()->user()->id)->latest('id')->value('id');
		
		$current_finance_husband_data = Current_financial_account::where('sl_no', $lastId)->where('account_owner', 1)->where('account_title', 'LIKE', '%Annuity%')->first();
		$husband_account_value = $current_finance_husband_data ? $current_finance_husband_data->account_value : '';
		
		$m_14 = 0;
		$m_15 = 0;
		$m_16 = 0;
		$m_17 = 0;
		$m_18 = 0;
		$m_19 = 0;
		$m_20 = 0;
		
		$M_14 = 0;
		$M_15 = 0;
		$M_16 = 0;
		$M_17 = 0;
		$M_18 = 0;
		$M_19 = 0;
		$M_20 = 0;
		
		$a12 = 0;
		$a14 = 0;
		$a17 = 0;
		$a20 = 0;
		$J_16 = 0;
		$index17_previous = 0;
		$index19_previous = 0;
			
		if(!empty($husband_account_value))
		{
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
		}
		//-------------------------------------------------------
		// dd($headerAge);
		//echo $M_18; die;
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
	public function allocation_plan_details($lastId)
	{
		$portfolio_Desire_data = Client_portfolio_Desires::with(['get_representative_details'])->where('user_id', auth()->user()->id)->where('id', $lastId)->first();
		
		$current_financial_account = Current_financial_account::where('sl_no', $lastId)->where('user_id', auth()->user()->id)->get();

		$current_income_account = Guaranteed_income_sources::where('sl_no', $lastId)->where('user_id', auth()->user()->id)->get();
		$headerAccountOwnerArray = [];
		$headerAccountTitleArray = [];
		$headerIncomeArray = [];
		$headerValueArray = [];

		$headerAccountOwnerArray[] = 'Year';
		$headerAccountOwnerArray[] = $portfolio_Desire_data->client_name ?? '';
		
		if(!empty($portfolio_Desire_data->partner_name))
		$headerAccountOwnerArray[] = $portfolio_Desire_data->partner_name ?? '';

		foreach($current_financial_account as $key=>$acount)
		{
			$account_owner = $acount->account_owner == 1 ? 'Husband' : ($acount->account_owner == 2 ? 'Wife' : 'Joint');
			
			$headTitle = $acount->account_owner == 1 ? $acount->owner_name.' '.$acount->account_title : ($acount->account_owner == 2 ? $acount->owner_name.' '.$acount->account_title : $acount->owner_name.' '.$acount->account_title);
			$headerAccountTitleArray[] = $headTitle;
			
			if ($acount->tax_qualification == 2 && !preg_match('/\bsavings?\b/i', $acount->account_title))
			{
				$headerAccountTitleArray[] = 'Income';
			}
			
			if($acount->tax_qualification == 1 && stripos($acount->account_title, 'Annuity') === false)
			{
				$headerAccountTitleArray[] = 'RMD';
			}
		
			if(stripos($acount->account_title, 'Annuity') !== false)
			{
				if($acount->account_owner == 1)
				{
					$headerAccountTitleArray[] = 'IRA balance for RMD';
					$headerAccountTitleArray[] = $acount->owner_name.' RMD/Income';
					$headerAccountTitleArray[] = $acount->owner_name.' '.'Tax Free Income';
				}
				//$headerAccountTitleArray[] = 'RMD';
				if($acount->account_owner == 2)
				{
					$headerAccountTitleArray[] = 'IRA balance for RMD';
					$headerAccountTitleArray[] = $acount->owner_name.' RMD/Income';
					$headerAccountTitleArray[] = $acount->owner_name.' '.'Tax Free Income';
				}
				
				if($acount->account_owner == 3)
				{
					//$headerAccountTitleArray[] = 'Joint RMD/Income';
					$headerAccountTitleArray[] = $acount->owner_name.' '.'RMD/Income';
				}
			}
		}
		
		// CALCULATION FOR 72 YEARS HUSBAND ROTH 
		$a12 = 0;
		$a14 = 0;
		$a17 = 0;
		$a20 = 0;
		$J_16 = 0;
		
		$roth_year_data = Roth_conversion_year::where('sl_no', $lastId)->first();
		$roth_yr = $roth_year_data ? $roth_year_data->year : 0;
		
		$index17_previous = 0;
		$index19_previous = 0;
		$C16=0;$D_16=0;$E_16=0;$F_16=0;$G_16=0;$H_16=0;$C_17=0;$D_17=0;
		$E_17=0;$F_17=0;$G_17=0;$H_17=0;$h_19=0;$i12=0;$i13=0;$i14=0;$i15=0;
		$i16=0;$i17=0;$L_14=0;$L_15=0;$L_16=0;$L_17=0;$L_18=0;$L_19=0;$L_20=0;
		$M_14=0;$M_15=0;$M_16=0;$M_17=0;$M_18=0;$M_19=0;$J_16=0;$M_20=0;
		
		$max_yr = 2+$roth_yr;
		/*$tax_free_val = $roth_yr == 1 ? $m_14 : ($roth_yr == 2 ? $m_15 : ($roth_yr == 3 ? $m_16 : ($roth_yr == 4 ? $m_17 : ($roth_yr == 5 ? $m_18 : ( $roth_yr == 6 ? $m_19 : ($roth_yr == 7 ? $m_20 : $m_20 ))))) );*/
		
		$husband_allo_RMD = [];
		
		$current_finance_husband_data = Current_financial_account::where('sl_no', $lastId)->where('account_owner', 1)->where('account_title', 'LIKE', '%Annuity%')->first();

		$husband_account_value = $current_finance_husband_data ? $current_finance_husband_data->account_value : '';
		
		//echo $husband_account_value;die;
		if(!empty($husband_account_value))
		{
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
					
					if($col >= 3 && $col<=$max_yr)
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
							
							if($col==5)
							{
								//echo $index12_previous;die;
							   $husband_allo_RMD[72] = round($index12_previous);
							}
							//echo "<pre>";print_r($husband_allo_RMD);die;
							if($col==6)
							{
							   $husband_allo_RMD[73] = round($index12_previous);
							}
							
							if($col==7)
							{
							   $husband_allo_RMD[74] = round($index12_previous);
							}
							
							if($col==8)
							{
							   $husband_allo_RMD[75] = round($index12_previous);
							}
						}
						elseif($row == 2 && $col<=8)
						{
							$index13_previous = $index12_previous*0.0095;
						}
						elseif(($col >=3 && $col <= 8) && $row ==3)
						{
							$index15_previous = $a20*0.22;
						}
						elseif($row == 6 && $col<=8)
						{
							$index17_previous = ($index12_previous-$index13_previous-$a20) * 1.05;
						}
						elseif($row == 8)
						{
							$index19_previous = 0;
							if($col == 6)
							{
								$index19_previous= distribution_period()[73][1];
							}
							
							if($col == 7)
							{
								$index19_previous= distribution_period()[74][1];
							}
							
							if($col == 8)
							{
								$index19_previous= distribution_period()[75][1];
							}
						}
						
						if($row==1 && $col==9)
						{
							$husband_allo_RMD[76] = round($index17_previous - distribution_period()[75][1]);
						}
					}
				}
			}
		}
		
		//echo "<pre>";print_r($husband_allo_RMD);die;
		// CALCULATION FOR 72 YEARS WIFE ROTH 
		
		$index_18 = 0 ;
		$index_20 = 0 ;
		$index_21 = 0 ;
		$roth_year_data = Roth_conversion_year::where('sl_no', $lastId)->first();
		$wife_roth = !empty($roth_year_data->wife_roth_year) ? $roth_year_data->wife_roth_year : '';
		
		$wife_max_year = $wife_roth != '' ? $wife_roth+4 : 12;
		$wife_roth_yr  = !empty($roth_year_data->wife_roth_year) ? $roth_year_data->wife_roth_year : 8;
		
		$current_finance_wife_data = Current_financial_account::where('sl_no', $lastId)->where('account_owner', 2)->where('account_title', 'LIKE', '%Annuity%')->first();

		$wife_account_value = $current_finance_wife_data ? $current_finance_wife_data->account_value : 0;
		
		if(!empty($wife_account_value))
		{		
			for($col = 1; $col <= 16; $col++)
			{
				$index_12 = 0;
				$index_15 = 0;
				$index_16 = 0;
				$index_13 = 0;
				$index_17 = 0;
					for($row = 1; $row <= 14; $row++)
					{
						$index = $col.$row;
						$w_acc_value = $wife_account_value ?? 0;
						//echo $w_acc_value;die;
						$a12 = $w_acc_value !='' ? round($w_acc_value * 0.21) : 0;
						$a15 = $wife_account_value + $a12;
						$a18 = round($a15 * (1 + 0.05));
						if($col == 1)
						{
							if($row == 8){
								$a20 = $a18/9;
							}
								
						}
						elseif($col == 4)
						{
							if($row == 1)
							{
								$index_12 = $a15;
							}
							
							if($row == 6)
							{
								$index_18 = $a18;
							}
						}	
							
						if($col>4 && $col<=$wife_max_year)
						{
							if($row == 1)
							{
								if($col==5)
								{
									$index_12 = $index_18 - $index_21;
								}
								elseif($col>8 && $col<13)
								{
									if($col==9)
									{
										//$I_12 = ($index_18*1.05) - $index_21;
										
										$I_12 = ($index_18*1.05) - $index_20;
										
										$index_12 = ($index_18*1.05) - $index_20;
										$wife_allo_RMD[74] = round($I_12);
									}
									elseif($col==10)
									{
										//$J_12 = ($index_18*1.05) - $index_21;
										
										$J_12 = ($index_18*1.05) - $index_20;
										
										$index_12 = ($index_18*1.05) - $index_20;
										$wife_allo_RMD[75] = round($J_12);
									}
									elseif($col==11)
									{
										//$K_12 = ($index_18*1.05) - $index_21;
										
										$K_12 = ($index_18*1.05) - $index_20;
										
										$index_12 = ($index_18*1.05) - $index_20;
										$wife_allo_RMD[76] = round($K_12);
									}
									elseif($col==12)
									{
										//$L_12 = ($index_18*1.05) - $index_21;
										
										$L_12 = ($index_18*1.05) - $index_20;
										
										$index_12 = ($index_18*1.05) - $index_20;
										$wife_allo_RMD[77] = round($L_12);
									}
								}
								else{
									$index_12 = ($index_18*1.05) - $index_21;
									if($col==7)
									{
										$G_12 = ($index_18*1.05) - $index_21;
										$wife_allo_RMD[72] = round($G_12);
									}
									elseif($col==8)
									{
										$H_12 = ($index_18*1.05) - $index_21;
										$wife_allo_RMD[73] = round($H_12);
									}
								}
							}
							elseif($row == 2)
							{
								$index_13 = $index_12* 0.0095;
							}
							elseif($row == 3)
							{
								if($col==5)
								{
									$index_15 = $a18/$wife_roth_yr;
								}
								
								if($wife_max_year==6 && $col==6)
								{
									$index_15 = $index_12-$index_13;
								}
								elseif($col==6)
								{
									$index_15 = $a18/$wife_roth_yr;
								}
								
								if($wife_max_year==7 && $col==7)
								{
									$index_15 = $index_12-$index_13;
								}
								elseif($col==7)
								{
									$index_15 = $a18/$wife_roth_yr;
								}
								
								if($wife_max_year==8 && $col==8)
								{
									$index_15 = $index_12-$index_13;
								}
								elseif($col==8)
								{
									$index_15 = $a18/$wife_roth_yr;
								}
								
								if($wife_max_year==9 && $col==9)
								{
									$index_15 = $index_12-$index_13;
								}
								elseif($col==9)
								{
									$index_15 = $a18/$wife_roth_yr;
								}
								
								if($wife_max_year==10 && $col==10)
								{
									$index_15 = $index_12-$index_13;
								}
								elseif($col==10)	
								{
									$index_15 = $a18/$wife_roth_yr;
								}	

								if($wife_max_year==11 && $col==11)
								{
									$index_15 = $index_12-$index_13;
								}
								elseif($col==11)
								{
									$index_15 = $a18/$wife_roth_yr;
								}
								
								if($col==12)
								{
									$index_15 = $index_12-$index_13;
								}
							}
							elseif($row == 4)
							{
								$index_16 = $index_15 * 0.24;
							}
							elseif($row == 5)
							{
								$index_17 = $index_15-$index_16;
							}
							elseif($row == 6)
							{
								$index_18 = $index_12-$index_13-$index_15;
							}
							elseif($row == 8)
							{
								if($col==8)
								{
									$index_20 = $G_12/wife_distribution_period()[73][0];
								}
								
								if($col==9)
								{
									$index_20 = $H_12/wife_distribution_period()[74][0];
								}
								
								if($col==10)
								{
									$index_20 = $I_12/wife_distribution_period()[75][0];
								}
								
								if($col==11)
								{
									$index_20 = $J_12/wife_distribution_period()[76][0];
								}
								
								if($col==12)
								{
									$index_20 = $K_12/wife_distribution_period()[77][0];
								}
							}
						}
					}
			}
		}
		//echo "<pre>";print_r($wife_allo_RMD);die;
		// fetch values
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
		$rmd = [];
		$previous_joint_annuity = 0;
		$desired_gross_income_retirement = 0;
		$taxable_income = 0;
		
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
						
						if(!empty($wifeAge))
						$row[] = $wifeAge + $j;
					}
					
					$new_husband_age = $husbandAge + $j;
					$new_wife_age = $wifeAge + $j;
					//echo $new_wife_age; die;
					$currentYear = date('Y');
					$husband_dob = $currentYear - $husbandAge;
					
					$wife_dob = $currentYear - $wifeAge;
					
					
					//----- rmd age calculation-------
					$husband_age_rmd = '0000';
					$wife_age_rmd = '0000';
					$joint_age_rmd = '0000';
					
					$husband_age_rmd_ira_bal = 72;
					$wife_age_rmd_ira_bal = 72;
					
					if($acount->account_owner == 1)
					{
						$husband_age_rmd = 72;
						$husband_age_rmd = $acount->rmd_start_age ?? '0000';
						$husband_dob = $currentYear - $husband_age_rmd;
						if($husband_dob >= 1960)
						{
							$husband_age_rmd = 75;
						}
					}
					
					if($acount->account_owner == 2)
					{
						$wife_age_rmd = 72;
						$wife_age_rmd = $acount->rmd_start_age ?? '0000';
						
						$wife_dob = $currentYear - $wife_age_rmd;
						if($wife_dob >= 1960)
						{
							$wife_age_rmd = 75;
						}
					}
					
					if($acount->account_owner == 1)
					{
						$joint_age_rmd = 72;
						$joint_age_rmd = $acount->rmd_start_age ?? '0000';
						$husband_dob = $currentYear - $joint_age_rmd;
						if($husband_dob >= 1960)
						{
							$joint_age_rmd = 75;
						}
					}
					
					//----- calculation part ----------
					$account_value = number_format($acount->account_value);
					
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
						}
					}
					
					//if (stripos($acount->account_title, 'nq') !== false)
					if ($acount->tax_qualification == 2 && !preg_match('/\bsavings?\b/i', $acount->account_title))
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
							
						}
					}
					
					// here loop should extends according to tax_qualification fields = 1 and has no Annuity
					if($acount->tax_qualification == 1    && stripos($acount->account_title, 'Annuity') === false)
					{
						if($i==0)
						{
							//$k401 =  $acount->account_value;
							$previous_tax_quali_arr[$key] = $acount->account_value;
							//$account_value = $acount->account_value;
							$finance_account_value = $finance_account_value + $acount->account_value;
						}
						else
						{
							//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
							
							if($new_husband_age >=$husband_age_rmd && $acount->account_owner == 1)
							{
								//echo $new_husband_age; die;
								$percentRmd = distribution_period()[$new_husband_age][0];
								//$rmd[$key] = $previous_tax_quali_arr[$key] / $percentRmd;
								$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd[$key]);
								//echo "<pre>";print_r($rmd);die;
								$account_value = number_format($current_tax_value);
								$percentRmd = distribution_period()[$new_husband_age][0];
								//$percentRmd = distribution_period()[$new_wife_age][0]; 15-09-2025
								
								$finance_account_value = $finance_account_value + $current_tax_value;
								
							}
							else if($new_wife_age >= $wife_age_rmd  && $acount->account_owner == 2)
							{
								$percentRmd = distribution_period()[$new_wife_age][0];
								//$rmd[$key] = $previous_tax_quali_arr[$key] / $percentRmd;
								$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd[$key]);
								//echo "<pre>";print_r($rmd);die;
								$account_value = number_format($current_tax_value);
								$percentRmd = distribution_period()[$new_wife_age][0];
								
								$finance_account_value = $finance_account_value + $current_tax_value;
							}
							else
							{
								$rmd[$key] = 0;
								$current_tax_value = $previous_tax_quali_arr[$key] * 1.05;
								$account_value = number_format($current_tax_value); 
								$previous_tax_quali_arr[$key] = $current_tax_value;
								
								$finance_account_value = $finance_account_value + $current_tax_value;
							}
							//echo $finance_account_value;die;
						}
					}
					
					//--tax_qualification=1 and has annuity-----
					
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
								//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
									
								if($new_husband_age >=$husband_age_rmd_ira_bal && $acount->account_owner == 1)
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
								//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
									
								if($new_wife_age >=$wife_age_rmd_ira_bal  && $acount->account_owner == 2)
								{
									//echo $new_wife_age;die;
									//$percentRmd = distribution_period()[$new_wife_age][0];// 22-09-2025
									
									
									//$rmd_wife = $previous_wife_annuity / $percentRmd; // 22-05-2025
									$rmd_wife = 0;
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
								//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
									
								if($new_husband_age >=$joint_age_rmd && $new_wife_age >= $joint_age_rmd)
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
					
					//if (stripos($acount->account_title, 'nq') !== false)
						
					if ($acount->tax_qualification == 2 && !preg_match('/\bsavings?\b/i', $acount->account_title))
					{
						$row[] = '$26375';
						$nq_icome = 26375;
						$gross_income = $gross_income + $nq_icome;
					}
					
					// calculation for tax_qualification = 1 (IRA) RMD and Income has no Annuity
					if($acount->tax_qualification == 1   && stripos($acount->account_title, 'Annuity') === false)
					{
						//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
							
						if($new_husband_age >=$husband_age_rmd && $acount->account_owner == 1)
						{
							$percentRmd = distribution_period()[$new_husband_age][0];
							
							//------ 03-09-2025----
							$row[] = number_format($previous_tax_quali_arr[$key] / $percentRmd);

							$k401_rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							
							//$rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd[$key]);
							$rmd[$key] = $previous_tax_quali_arr[$key] / $percentRmd;
							
							$previous_tax_quali_arr[$key] = $current_tax_value;
							
							$gross_income = $gross_income + $rmd[$key];
							
						}
						else if($new_wife_age >= $wife_age_rmd  && $acount->account_owner == 2)
						{
							$percentRmd = distribution_period()[$new_wife_age][0];
							
							//------ 03-09-2025----
							$row[] = number_format($previous_tax_quali_arr[$key] / $percentRmd);

							$k401_rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							
							//$rmd = $previous_tax_quali_arr[$key] / $percentRmd;
							$current_tax_value = round(($previous_tax_quali_arr[$key] * 1.05) - $rmd[$key]);
							$rmd[$key] = $previous_tax_quali_arr[$key] / $percentRmd;
							
							$previous_tax_quali_arr[$key] = $current_tax_value;
							
							$gross_income = $gross_income + $rmd[$key];
							
						}
						else
						{
							$rmd[$key] = 0;
							$k401_rmd = 0;
							$row[] = '';
							//$row[] = '';
							
							$current_tax_value = $previous_tax_quali_arr[$key] * 1.05;
							//$previous_tax_quali_arr[$key] = $current_tax_value;
						}
						
					}
					
					
					// calculation for tax_qualification = 1 (IRA) RMD and Income has Annuity
					if (stripos($acount->account_title, 'Annuity') !== false)
					{
						if($acount->account_owner == 1)
						{
							//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								
							if($new_husband_age >=$husband_age_rmd_ira_bal)
							{
								//$percentRmd = percent_k401_yearly()[$i];
								//$row[] = number_format($previous_husband_annuity * 0.055932);
								if($new_husband_age >= 72 && $new_husband_age<=76)
								{
									//echo $new_husband_age; die;
									if (isset($husband_allo_RMD[$new_husband_age]))
									$row[] = number_format($husband_allo_RMD[$new_husband_age]);
								}
								else{
									$row[] = '';
								}
								
								
								
								if($new_husband_age >= 73 && $new_husband_age<=77)
								{
									$dist_periods = distribution_period()[$new_husband_age][0];
									
									if (isset($husband_allo_RMD[$new_husband_age-1]))
									$row[] = number_format(round($husband_allo_RMD[$new_husband_age-1]/$dist_periods)) ; 
								}
								else{
									$row[] = '';
								}
								
								$row[] = '';
								$husband_annuity_rmd_inc = $previous_husband_annuity * 0.055932;
								$gross_income = $gross_income + $husband_annuity_rmd_inc;
							}
							else 
							{
								$husband_annuity_rmd_inc = 0;
								$row[] = '';
								$row[] = '';
								$row[] = '';
								$gross_income = $gross_income + $husband_annuity_rmd_inc;
								//$previous_annuity = $acount->account_value;
							}
						}
						
						if($acount->account_owner == 2)
						{
							//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								
							if($new_wife_age >= $wife_age_rmd_ira_bal)
							{
								$annual_income_value = Current_financial_account::where('sl_no', $lastId)->where('user_id', auth()->user()->id)->where('account_owner', 2)->where('account_title','LIKE', '%annuity%')->first();
								$wife_annuity_rmd_inc = $annual_income_value ? $annual_income_value->annual_income_value : 0;
								
								//$percentRmd = distribution_period()[$new_wife_age][0];
								//$row[] = number_format($previous_wife_annuity / $percentRmd).'www';
								if($new_wife_age >= 72 && $new_wife_age<=77)
								{
									if(isset($wife_allo_RMD[$new_wife_age]))
									$row[] = number_format($wife_allo_RMD[$new_wife_age]);
								}
								else{
									$row[] = '';
								}
								
								if($new_wife_age >= 73 && $new_wife_age<=78)
								{
									$dist_periods = wife_distribution_period()[$new_wife_age][0];
									
									if(isset($wife_allo_RMD[$new_wife_age-1]))
									$row[] = number_format(round($wife_allo_RMD[$new_wife_age-1]/$dist_periods)) ; 
								}
								else{
									$row[] = '';
								}
								
								$row[] = '';
								//$row[] = '$134,475';
								//$wife_annuity_rmd_inc = 134475;
								
								$gross_income = $gross_income + $wife_annuity_rmd_inc;
								
							}
							else 
							{
								$wife_annuity_rmd_inc = 0;
								$row[] = '';
								$row[] = '';
								$row[] = '';
								$gross_income = $gross_income + $wife_annuity_rmd_inc;
								//$previous_annuity = $acount->account_value;
							}
						}
						
						if($acount->account_owner == 3)
						{
							//if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
								
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
				
				
				/*if($new_husband_age >=$husband_age_rmd && $new_wife_age >= $wife_age_rmd)
				{
					//$row[] = distribution_period()[$new_wife_age][0];	
				}
				else
				{
					//$row[] = '';
				}*/

				
				foreach($current_income_account as $k=>$income_src)
				{
					$inc_src = $income_src->frequency == 1  ? $income_src->income_amount*12 : $income_src->income_amount;
					
					$account_value = number_format($inc_src);
					//$account_value = number_format($income_src->income_amount);// 27-09-2025
					if($i==0)
					{
						$previous_income_arr[$k] = $income_src->frequency == 1  ? $income_src->income_amount*12 : $income_src->income_amount;
						
						// update  27-09-2025
						$inc_amt = $income_src->frequency == 1  ? $income_src->income_amount*12 : $income_src->income_amount;
						
						$sum_current_inc = $sum_current_inc + $inc_amt;
						$gross_income = $gross_income + $inc_amt;
						
						//$previous_income_arr[$k] = $income_src->income_amount;
						// comment 27-09-2025
						/*$sum_current_inc = $sum_current_inc + $income_src->income_amount;
						$gross_income = $gross_income + $income_src->income_amount;*/
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
					$irmaaVal = 14190.40;
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
				
				$row[] = number_format($income_goal);
				$row[] = number_format($gross_income);
				$row[] = number_format($taxable_income);
				//$row[] = number_format($income_goal);
				$row[] = number_format($income_goal - $gross_income);
				$row[] = number_format($irmaaVal);
				$row[] = $tax_rate .'%';
				$row[] = number_format($irs_partner);
				$row[] = number_format($finance_account_value);
				
				$headerValueArray[$i] = $row;
				$j++;
		}			
		
		
	
		foreach($current_income_account as $income_src)
		{
			$headerIncomeArray[] = $income_src->client_name;
		}
		
		if($current_income_account->isNotEmpty())
		{
			$headerIncomeArray[] = 'Income Goal';
			$headerIncomeArray[] = 'Gross Income';
			$headerIncomeArray[] = 'Taxable Income';
			//$headerIncomeArray[] = 'Income Goal';
			$headerIncomeArray[] = 'Gap From Assets';
			$headerIncomeArray[] = 'IRMAA';
			$headerIncomeArray[] = 'Tax Rates';
			$headerIncomeArray[] = 'IRS Partner';
			$headerIncomeArray[] = 'Total Estate';
		}
			
		$headerArray = array_merge($headerAccountOwnerArray,$headerAccountTitleArray,$headerIncomeArray);
		//echo "<pre>";print_r($headerArray);die;
		$data = [
			"plan_allocation_header"=> $headerArray,
			"plan_allocation_value" => $headerValueArray,
		];
		return $data;
	}
}
