@php 
use Carbon\Carbon;
$headerCount = count($excelheaderArray)-1;
//echo "<pre>";print_r($excelheaderArray);die;
//echo "<pre>";print_r($excelheaderValueArray);die;
//echo "<pre>";print_r(husband_roth_tax_conversion());die;
//echo "<pre>";print_r($plan_allocation_header);die;
//echo "<pre>";print_r($plan_allocation_value);die;

$husbandAsset = [];
$wifeAsset = [];
$jointAsset = [];
foreach($current_financial_account as $financial_account)
{
	if($financial_account->account_owner == 1)
	{
		$husbandAsset[] = [
			'account_owner' => 'Husband',
			'account_title' => $financial_account->account_title,
			'tax_qualification' => $financial_account->tax_qualification,
			'account_value' => $financial_account->account_value,
			'owner_name' => $financial_account->owner_name,
		];
	}
	elseif($financial_account->account_owner == 2)
	{
		$wifeAsset[] = [
			'account_owner' => 'Wife',
			'account_title' => $financial_account->account_title,
			'tax_qualification' => $financial_account->tax_qualification,
			'account_value' => $financial_account->account_value,
			'owner_name' => $financial_account->owner_name,
		];
	}
	else
	{
		$jointAsset[] = [
			'account_owner' => 'Joint',
			'account_title' => $financial_account->account_title,
			'tax_qualification' => $financial_account->tax_qualification,
			'account_value' => $financial_account->account_value,
		];
	}
	
}
//echo $wifeAsset[0]['owner_name'];die;
//echo "<pre>";print_r($wifeAsset);die;

$subTotalHusband = 0;
$subTotalWife = 0;
$subTotalJoint = 0;
$subTotalCurrent = 0;
$incomeTotal = 0;
$h=0;
$w=0;
$j=0;
$c=0;
//echo "<pre>";print_r($husbandAsset);
//echo "<pre>";print_r($wifeAsset);
//echo "<pre>";print_r($jointAsset);die;

if($current_income_account->isNotEmpty())
{
	foreach($current_income_account as $income_account)
	{
		$incomeTotal  += $income_account->income_amount;
	}
}

$total_wife_rmd_inc = 0;
$total_husband_rmd_inc = 0;
$total_joint_rmd_inc = 0;
$total_inc_tax = 0;
$total_IRMAA = 0;
$total_irs_partner = 0;
$total_estate = 0;

$currentYear = date('Y');
$age = 70;
//echo $birthYear = $currentYear - $age; die;

$current_finance_husband_data = App\Models\Current_financial_account::where('sl_no', $lastId)->where('account_owner', 1)->where('account_title', 'LIKE', '%Annuity%')->first();

$husband_account_value = $current_finance_husband_data ? $current_finance_husband_data->account_value : '';

$roth_year_data = App\Models\Roth_conversion_year::where('sl_no', $lastId)->first();
$roth_yr = $roth_year_data ? $roth_year_data->year : 0;
$wife_roth = !empty($roth_year_data->wife_roth_year) ? $roth_year_data->wife_roth_year : '';
//echo $roth_yr; die;

$show_specific_year = !empty($roth_year_data->show_specific_year) ? explode(';', $roth_year_data->show_specific_year) : [];
//echo "<pre>";print_r($show_specific_year);die;


$current_finance_wife_data = App\Models\Current_financial_account::where('sl_no', $lastId)->where('account_owner', 2)->where('account_title', 'LIKE', '%Annuity%')->first();

$wife_account_value = $current_finance_wife_data ? $current_finance_wife_data->account_value : '';

$wife_max_year = $wife_roth != '' ? $wife_roth+4 : 12;
$wife_roth_yr  = !empty($roth_year_data->wife_roth_year) ? $roth_year_data->wife_roth_year : 8;

$hus_RMD_Income = '';
$wife_RMD_Income = '';
$hus_tax_free_Income = '';
$wife_tax_free_Income = '';

if(!empty($husbandAsset))
{
$hus_RMD_Income = $husbandAsset[0]['owner_name'] .' RMD/Income';
$hus_tax_free_Income = $husbandAsset[0]['owner_name'] .' Tax Free Income';
}

if(!empty($wifeAsset))
{
$wife_RMD_Income = $wifeAsset[0]['owner_name'] .' RMD/Income';
$wife_tax_free_Income = $wifeAsset[0]['owner_name'] .' Tax Free Income';
}


//echo $wife_RMD_Income;die;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Income Plan Cover</title>
    <style>
		@font-face {
            font-family: 'SofiaPro-Regular';
            src: url("{{ public_path('fonts/Sofia/SofiaPro-Regular.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body { font-family: 'SofiaPro-Regular', sans-serif; }
		table { width: 100%; }
		.report td {
			background-color: #F3F4F6;
			padding: 5px;
			font-size: 20px;
		}
		.calc-report td, .calc-report th {
			font-size: 10px;
			word-wrap: break-word;
			text-align: center;
			background-color: #F3F4F6;
		}
		.heading-color {
			color: #3490CD;
		}
		
		.section-title { text-align: center; font-weight: bold; font-size: 20px; margin-bottom: 10px; }
        .subtotal { font-weight: bold; }
        .totals { font-weight: bold; font-size: 26px; }
        .right { text-align: right; }
		
		.footer {
			position: fixed;
            bottom: 110px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #000;
		}
		.fixed-company-name {
			margin-top: 0;
			position: fixed;
			bottom: 110px;
			font-size: 14px;
		}
		.mt-5 {
			margin-top: 5px;
		}
		.mt-10 {
			margin-top: 10px;
		}
		
		
    </style>
</head>
<body>
	<div style="page-break-after: always;">
		<table>
			<tr>
				<td width="80%">
					<p style="text-align: center; color: #929292;">
						1233 NW 107th ter<br>
						Plantation, FL 33322<br>
						darryl.stein@gmail.com<br>
						267-280-3660
					</p>
				</td>
				<td width="20%">
				@if(isset($setting->advisor_logo))
					<img src="{{ url('uploads/advisor_logo/'. $setting->advisor_logo) }}" width="200">
				@else
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="200">
				@endif
				</td>
			</tr>
		</table>

		<table style="margin-top: 100px;">
			<tr>
				<!-- Left Side -->
				<td width="50%" style="border-right: 1px solid #3490CD;">
				<h1 style="color: #3490CD;">Building Rewarding Income Goals</h1>
				{{--<h1 style="color: #3490CD;">{{ isset($setting) ? $setting->advisor_text : ''}}</h1>--}}
					<h2>Income Allocation Tool</h2>
				</td>

				<!-- Right Side -->
				<td width="50%" style="padding-left: 20px;">
					<p><strong style="color: #3490CD;">Prepared For:</strong><br>
					{{ $client_nm ?? ''}} and {{ $partner_nm ?? ''}}</p>

					<p><strong style="color: #3490CD;">Agent/Representative:</strong><br>
					{{ $representative ?? ''}}</p>

					<p><strong style="color: #3490CD;">Date Prepared:</strong>
					{{ Carbon::parse($created_at)->format('d/m/Y') }}</p>
				</td>
			</tr>
		</table>
		<br>
		<p class="fixed-company-name">
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>
		<table class="footer">
			<tr>
				<td style="text-align: left;font-size: 12px;">
					The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity
					Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide.
					The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees
					are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the
					greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.</br></br>

					Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences.
				</td>
			</tr>
		</table>
	</div>
	<div style="page-break-after: always;">
		<table>
			<tr>
				<td width="80%" style="text-align: center;">
					<h2 style="color: #3490CD;">{{ isset($setting) ? $setting->advisor_text : ''}}</h2>
					<h3>Income Allocation Tool</h3>
				</td>
				<td width="20%">
				@if(isset($setting->advisor_logo))
					<img src="{{ url('uploads/advisor_logo/'. $setting->advisor_logo) }}" width="200">
				@else
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="200">
				@endif
				</td>
			</tr>
		</table>
		<table class="report">
			<thead>
				<tr>
					<th width="70%" style="color: #3490CD;text-align: left;padding: 15px 0;">Report Summary</th>
					<th width="30%"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="70%">Current Position</td>
					<td width="30%" style="text-align: right;">$ {{ $current_position ?? ''}}</td>
				</tr>
				<tr>
					<td width="70%">Current Age</td>
					<td width="30%" style="text-align: right;">{{ $current_age ?? ''}}</td>
				</tr>
				<tr>
					<td width="70%">Retirement Age</td>
					<td width="30%" style="text-align: right;">{{ $retirement_age ?? ''}}</td>
				</tr>
				<tr>
					<td width="70%">Desired Retirement Income</td>
					<td width="30%" style="text-align: right;">$ {{ $desired_retirement_income ?? ''}}</td>
				</tr>
				<tr>
					<td width="70%">COLA</td>
					<td width="30%" style="text-align: right;">{{ $cola ?? ''}} %</td>
				</tr>
				<tr>
					<td width="70%">Growth Allocation</td>
					<td width="30%" style="text-align: right;">$ {{ $growth_allocation ?? '' }}</td>
				</tr>
				{{--<tr>
					<td width="70%">Income Bucket</td>
					<td width="30%" style="text-align: right;">$0</td>
				</tr>--}}
				@php 
				$primary_goal = explode(",", $primary_goals);
				
				$primary1 = '';
				$primary2 = '';
				$primary3 = '';
				
				if(!empty($primary_goal))
				{
					if (isset($primary_goal[0])) {
						$primary1 = $primary_goal[0] == '1' 
							? 'Income' 
							: ($primary_goal[0] == '2' ? 'Tax Reduction' : 'Legacy');
					} else {
						$primary1 = null;
					}
					
					if (isset($primary_goal[1])) {
						$primary2 = $primary_goal[1] == '1' 
							? ', Income' 
							: ($primary_goal[1] == '2' ? ', Tax Reduction' : ', Legacy');
					} else {
						$primary2 = null;
					}
					
					if (isset($primary_goal[2])) {
						$primary3 = $primary_goal[2] == '1' 
							? ', Income' 
							: ($primary_goal[2] == '2' ? ', Tax Reduction' : ', Legacy');
					} else {
						$primary3 = null;
					}
				}
				@endphp
				<tr>
					<td width="70%">Primary Goals: {{ $primary1 ?? '' }}{{ $primary2 ?? '' }}{{ $primary3 ?? '' }}</td>
					<td width="30%" style="text-align: right;"></td>
				</tr>
			</tbody>
		</table>
		<br>
		<p class="fixed-company-name">
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>
		<table class="footer">
			<tr>
				<td style="text-align: left;font-size: 12px;">
					The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity
					Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide.
					The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees
					are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the
					greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.</br></br>

					Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences".
				</td>
			</tr>
		</table>
	
	</div>
	
	<div style="page-break-after: always;">
		<table>
			<tr>
				<td width="80%">
					<h3 class="heading-color" style="text-align:center;">Current Financial Accounts</h3>
				</td>
				<td width="20%">
				@if(isset($setting->advisor_logo))
					<img src="{{ url('uploads/advisor_logo/'. $setting->advisor_logo) }}" width="200">
				@else
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="200">
				@endif
				</td>
			</tr>
			
		</table>
		<table style="margin-bottom: 20px;">
			<tr>
				<td width="20%" valign="top">
				</td>
				<td width="80%" class="heading-color" style="font-weight: 700;">
					Primary Goals: Income, Tax Reduction, Legacy
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td width="20%" valign="top">
				</td>
				<td width="80%" class="heading-color" style="font-weight: 700;">
					Desired Retirement Income ${{ number_format($desired_retirement_income) }} + 3% inflation
				</td>
			</tr>
		</table>
		
		<!-- Wife & Husband Accounts -->
		<table>
			<tr>
				<!-- Wife's Accounts -->
				<td width="20%" valign="top">
				</td>
				<td width="40%" valign="top">
					@if(!empty($wifeAsset))
					<div style="margin-top: 20px;">
					<strong style="text-decoration: underline;">{{ $partner_nm ?? ''}}'s Accounts</strong><br>
						@foreach($wifeAsset as $val)
							@php 
								$tax_quali = $val['tax_qualification'] == 1 ? 'IRA ' : 'non-qualified';
								$subTotalWife += $val['account_value'];
								$w++;
							@endphp
							<div class="mt-5">#{{ $w }} {{ $val['account_title'] }}&nbsp; {{$tax_quali ?? ''}}&nbsp;&nbsp; ${{ number_format($val['account_value']) }}</div>
						@endforeach
					<div class="subtotal mt-10">Subtotal <span style="background-color:#A1F21D;">${{ number_format($subTotalWife) }}</span></div>
					</div>
					@endif
					<!-- Joint Accounts -->
					@if(!empty($jointAsset))
					<div style="margin-top: 20px;">
						<strong style="text-decoration: underline;">Joint Accounts</strong><br>
							@foreach($jointAsset as $val)
								@php 
									$tax_quali = $val['tax_qualification'] == 1 ? 'IRA ' : 'non-qualified';
									$subTotalJoint += $val['account_value'];
									$j++;
								@endphp
								<div class="mt-5">#{{ $j }} {{ $val['account_title'] }}&nbsp; {{$tax_quali ?? ''}}&nbsp;&nbsp; ${{ number_format($val['account_value']) }}</div>
							@endforeach
						<div class="subtotal mt-10">Subtotal <span style="background-color:#A1F21D;">${{ number_format($subTotalJoint) }}</span></div>
					</div>
					@endif
					<!-- Current income account -->
					@if($current_income_account->isNotEmpty())
					<div style="margin-top: 20px;">
					<strong style="text-decoration: underline;">Current Income Accounts</strong><br>
						@foreach($current_income_account as $income_account)
						@php 
							$subTotalCurrent += $income_account->income_amount;
						@endphp
						<div class="mt-5">{{ $income_account->client_name ?? ''}} &nbsp;&nbsp; ${{  number_format($income_account->income_amount) }} </div>
						@endforeach
					<div class="subtotal mt-10">Subtotal <span style="background-color:#A1F21D;">${{ number_format($subTotalCurrent)}}</span></div>
					</div>
					@endif
				</td>

				<!-- Husband's Accounts -->
				<td width="40%" valign="top">
					@if(!empty($husbandAsset))
					<div style="margin-top: 20px;">	
					<strong style="text-decoration: underline;">{{ $client_nm ?? ''}}'s Accounts</strong><br>
						@foreach($husbandAsset as $val)
							@php 
								$tax_quali = $val['tax_qualification'] == 1 ? 'IRA ' : 'non-qualified';
								$subTotalHusband += $val['account_value'];
								$h++;
							@endphp
							<div class="mt-5">#{{ $h }} {{ $val['account_title'] }}&nbsp; {{ $tax_quali ?? '' }}&nbsp;&nbsp; ${{ number_format($val['account_value']) }}</div>
						@endforeach
					<div class="subtotal mt-10">Subtotal <span style="background-color:#A1F21D;">${{ number_format($subTotalHusband) }}</span></div>
					</div>
					@endif
					<div style="margin-top: 30px;">
						<div class="totals"><span style="background-color:#A1F21D;">Asset Total ${{ number_format($subTotalWife + $subTotalHusband + $subTotalJoint) }}</span></div>
						<div class="totals">Income Total ${{ number_format($incomeTotal) }}</div>
					</div>
				</td>
			</tr>
			<input type="hidden" id="subTotalCurrent" value="{{ $subTotalCurrent ?? 0 }}">
		</table>
		<br>
		<p class="fixed-company-name">
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>
		<table class="footer">
			<tr>
				<td style="text-align: left;font-size: 12px;">
					The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity
					Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide.
					The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees
					are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the
					greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.</br></br>

					Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences".
				</td>
			</tr>
		</table>
	</div>
	
	@php 
	$total_wife_rmd_inc_key = 0;
	$total_husband_rmd_inc_key = 0;
	$total_joint_rmd_inc_key = '';
	$total_inc_tax_key = '';
	$total_IRMAA_key = '';
	$total_irs_partner_key = '';
	$total_estate_key = '';
	$rmd_position_keys = [];
	$total_rmd_inc = [];
	$count_rmd = 0;
	$total_rmd_value = 0;
	$total_wife_rmd_val = 0;
	$total_tax_rate_key = '';
	$last_tax_rate = '';
	
	@endphp
	<div style="page-break-after: always;">
		<table>
			<tr>
				<td width="80%">
					<h3 class="heading-color" style="text-align:center;">Current Allocation Plan Details</h3>
				</td>
				<td width="20%">
				@if(isset($setting->advisor_logo))
					<img src="{{ url('uploads/advisor_logo/'. $setting->advisor_logo) }}" width="200">
				@else
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="200">
				@endif
				</td>
			</tr>
			
		</table>
		<table style="margin-bottom: 20px;">
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Primary Goals: Income, Tax Reduction, Legacy
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Desired Retirement Income ${{ number_format($desired_retirement_income) }} + 3% inflation
				</td>
			</tr>
		</table>
		<table class="calc-report">
			<thead>
			<tr>
			    @if(!empty($excelheaderArray))
					@foreach($excelheaderArray as $h=>$header)
				    
					@php 
						$th_bg_color = '';
						if($header == 'RMD')
						{
							$th_bg_color = 'red';
						}
						
						if($header == 'Taxable Income')
						{
							$th_bg_color = 'red';
						}
						
						if($header == 'Tax Rates')
						{
							$th_bg_color = 'red';
						}
						
						if($header == 'IRMAA')
						{
							$th_bg_color = 'red';
						}
						
						
						if(stripos($header, 'IRA') !== false)
						{
							$th_bg_color = 'red';
						}
						
					@endphp
					<th style="font-size:12px;background-color: {{ $th_bg_color }}">{{ $header ?? '' }}</th>
					
					
					
					@if(stripos($header, 'Wife') !== false && stripos($header, 'Annuity') !== false)
						@php 
							$count_rmd = 1;
						@endphp
					@endif
						
					
					
					@if($header == 'RMD' && $count_rmd == 0)
						@php
							$rmd_position_keys[] = $h;
							$total_rmd_inc[$h] = 0;
						@endphp
					@endif
					
					@if($header == $wife_RMD_Income)
						@php
							$total_wife_rmd_inc_key = $h;
							$count_rmd =0;
						@endphp
					@endif
					
					@if($header == $hus_RMD_Income)
						@php
							$total_husband_rmd_inc_key = $h;
						@endphp
					@endif
					
					@if($header == 'Joint RMD/Income')
						@php
							$total_joint_rmd_inc_key = $h;
						@endphp
					@endif
					
					@if($header == 'Taxable Income')
						@php
							$total_inc_tax_key = $h;
						@endphp
					@endif
					
					@if($header == 'IRMAA')
						@php
							$total_IRMAA_key = $h;
						@endphp
					@endif
					
					@if($header == 'Tax Rates')
						@php
							$total_tax_rate_key = $h;
						@endphp
					@endif
					
					@if($header == 'IRS Partner')
						@php
							$total_irs_partner_key = $h;
						@endphp
					@endif
					
					@if($header == 'Total Estate')
						@php
							$total_estate_key = $h;
						@endphp
					@endif
					
					@endforeach
				@endif
			</tr>
			</thead>
			@if(!empty($excelheaderValueArray))
				@foreach($excelheaderValueArray as $key=>$excelheaderValue)
					@foreach($excelheaderValue as $k=>$headerVal)
					
						@if(in_array($k, $rmd_position_keys))
							@php
								$total_rmd_val = (int) str_replace(',', '', $headerVal);
								$total_rmd_inc[$k] = $total_rmd_inc[$k] + $total_rmd_val;
							@endphp
						@endif
						
						@if($total_wife_rmd_inc_key-1 == $k)
							@php
							$wife_rmd = (int) str_replace(',', '', $headerVal);
								$total_wife_rmd_val = $total_wife_rmd_val + $wife_rmd;
							@endphp 
						@endif
					
						@if($total_wife_rmd_inc_key == $k)
							@php
							$total_wife_rmd = (int) str_replace(',', '', $headerVal);
								$total_wife_rmd_inc = $total_wife_rmd_inc + $total_wife_rmd;
							@endphp 
						@endif
						
						@if($total_husband_rmd_inc_key == $k)
							@php
							$total_husband_rmd = (int) str_replace(',', '', $headerVal);
								$total_husband_rmd_inc = $total_husband_rmd_inc + $total_husband_rmd;
							@endphp 
						@endif
						
						@if($total_joint_rmd_inc_key == $k)
							@php
							$total_joint_rmd = (int) str_replace(',', '', $headerVal);
								$total_joint_rmd_inc = $total_joint_rmd_inc + $total_joint_rmd;
							@endphp 
						@endif
						
						@if($total_inc_tax_key == $k)
							@php
							$total_inc_tax_numeric = (int) str_replace(',', '', $headerVal);
								$total_inc_tax = $total_inc_tax + $total_inc_tax_numeric;
							@endphp 
						@endif
						
						@if($total_IRMAA_key == $k)
							@php
							$total_IRMAA_numeric = (int) str_replace(',', '', $headerVal);
								$total_IRMAA = $total_IRMAA + $total_IRMAA_numeric;
							@endphp 
						@endif
						
						@if($total_tax_rate_key == $k)
							@php
							$last_tax_rate_numeric = (int) str_replace(',', '', $headerVal);
								$last_tax_rate = $last_tax_rate_numeric;
							@endphp 
						@endif
						
						@if($total_irs_partner_key == $k)
							@php
							$total_irs_partner_numeric = (int) str_replace(',', '', $headerVal);
								$total_irs_partner = $total_irs_partner + $total_irs_partner_numeric;
							@endphp 
						@endif
						
						@if($total_estate_key == $k)
							@php
							$total_estate_numeric = (int) str_replace(',', '', $headerVal);
								$total_estate = $total_estate + $total_estate_numeric;
							@endphp 
						@endif
					@endforeach
				@endforeach
			@endif
			<tbody>
				@if(!empty($excelheaderValueArray))
					@foreach($excelheaderValueArray as $key=>$excelheaderValue)
						@if(!empty($show_specific_year))
							@if(in_array($key, $show_specific_year))
								<tr>
								@foreach($excelheaderValue as $k=>$headerVal)
								@php 
									$tr_bg_color = '';
									if(in_array($k, $rmd_position_keys))
									{
										$tr_bg_color = 'red';
									}
									
									if($total_tax_rate_key == $k)
									{
										$tr_bg_color = 'red';
									}
									
									if($total_IRMAA_key == $k)
									{
										$tr_bg_color = 'red';
									}
									
									
								@endphp
								<td style="font-size:12px;background-color: {{ $tr_bg_color }}">{{ $headerVal ?? '' }}</td>
								@endforeach
								</tr>
							@endif
						@else
							<tr>
								@foreach($excelheaderValue as $k=>$headerVal)
								@php 
									$tr_bg_color = '';
									if(in_array($k, $rmd_position_keys))
									{
										$tr_bg_color = 'red';
									}
									
									if($total_tax_rate_key == $k)
									{
										$tr_bg_color = 'red';
									}
									
									if($total_IRMAA_key == $k)
									{
										$tr_bg_color = 'red';
									}
									
								@endphp
								<td style="background-color: {{ $headerVal !='' ? $tr_bg_color : '' }}">{{ $headerVal ?? '' }}</td>
								@endforeach
							</tr>
						@endif
							
					@endforeach
					<tr><td>&nbsp;</td></tr>
					
					@foreach($excelheaderValueArray as $key=>$excelheaderValue)
						@if($key == 0)
						<tr>
							@foreach($excelheaderValue as $subkey=>$headerVal)
							
							@php
								$tr_tot_bg_color = '';
								
								if(in_array($subkey, $rmd_position_keys))
								{
									$tr_tot_bg_color = 'red';
								}
								
								if($total_IRMAA_key == $subkey)
								{
									$tr_tot_bg_color = 'red';
								}
								
								if($total_estate_key == $subkey)
								{
									$tr_tot_bg_color = 'red';
								}
								
							@endphp
							
								<td style="background-color: {{ $tr_tot_bg_color }}"><strong>{{ $total_inc_tax_key == $subkey ?   '$' . number_format($total_inc_tax) : ($total_IRMAA_key == $subkey ?  '$' . number_format($total_IRMAA) : ($total_irs_partner_key == $subkey ?  '$' . number_format($total_irs_partner) : ($total_estate_key == $subkey ?  '$' . number_format($total_estate) : ($total_wife_rmd_inc_key == $subkey ?  '$' . number_format($total_wife_rmd_inc) : ($total_husband_rmd_inc_key == $subkey ?  '$' . number_format($total_husband_rmd_inc) : ($total_joint_rmd_inc_key == $subkey ?  '$' . number_format($total_joint_rmd_inc) : '') ) ) ) )) }}
								
								@if(in_array($subkey, $rmd_position_keys))
									${{ number_format($total_rmd_inc[$subkey]) }}
								@endif
								</strong></td>
							@endforeach
						</tr>
						@endif
					@endforeach
					<tr><td>&nbsp;</td></tr>
					@if(!empty($total_rmd_inc))
						@foreach($total_rmd_inc as $val)
							@php 
								$total_rmd_value = $total_rmd_value + $val;
							@endphp
						@endforeach
					@endif
					
					@foreach($excelheaderValueArray as $key=>$excelheaderValue)
						@if($key == 0)
						<tr>
						@foreach($excelheaderValue as $subkey=>$headerVal)
							<td style="">
							@if($subkey == 6)
							<strong>Total RMD: {{ '$ '. number_format($total_rmd_value + $total_wife_rmd_inc + $total_husband_rmd_inc + $total_joint_rmd_inc)}}</strong>
							@endif
							</td>
						@endforeach
						</tr>
						@endif
					@endforeach
				@endif
			</tbody>
		</table>
		
		{{--<br>
		<p class="fixed-company-name">
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>--}}
		{{--<table class="footer">
			<tr>
				<td style="text-align: left;font-size: 12px;">
					The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity
					Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide.
					The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees
					are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the
					greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.</br></br>

					Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences".
				</td>
			</tr>
		</table>--}}
		
	</div>
	
	{{--<div class="row">
			<div style="margin-left:450px;"><strong>Total RMD: {{ '$ '. number_format($total_rmd_value + $total_wife_rmd_inc + $total_husband_rmd_inc + $total_joint_rmd_inc)}}</strong></div>
	</div>--}}
	
	@if(!empty($current_finance_husband_data))
	<div style="page-break-after: always;">
		<table>
			<tr>
				<td width="80%">
					<h3 class="heading-color" style="text-align:center;">Husband Roth Conversion From Taxable To Free Tax</h3>
				</td>
				<td width="20%">
				@if(isset($setting->advisor_logo))
					<img src="{{ url('uploads/advisor_logo/'. $setting->advisor_logo) }}" width="200">
				@else
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="200">
				@endif
				</td>
			</tr>
			
		</table>
		<table style="margin-bottom: 20px;">
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Primary Goals: Income, Tax Reduction, Legacy
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Desired Retirement Income ${{ number_format($desired_retirement_income) }} + 3% inflation
				</td>
			</tr>
		</table>
		@php 
		
			$a12 = 0;
			$a14 = 0;
			$a17 = 0;
			$a20 = 0;
			$J_16 = 0;
			$index17_previous = 0;
			$index19_previous = 0;
			$C16=0;$D_16=0;$E_16=0;$F_16=0;$G_16=0;$H_16=0;$C_17=0;$D_17=0;
			$E_17=0;$F_17=0;$G_17=0;$H_17=0;$h_19=0;$i12=0;$i13=0;$i14=0;$i15=0;
			$i16=0;$i17=0;$L_14=0;$L_15=0;$L_16=0;$L_17=0;$L_18=0;$L_19=0;$L_20=0;
			$M_14=0;$M_15=0;$M_16=0;$M_17=0;$M_18=0;$M_19=0;$J_16=0;$M_20=0;
			
			$max_yr = 2+$roth_yr;
			$tax_free_val = $roth_yr == 1 ? $m_14 : ($roth_yr == 2 ? $m_15 : ($roth_yr == 3 ? $m_16 : ($roth_yr == 4 ? $m_17 : ($roth_yr == 5 ? $m_18 : ( $roth_yr == 6 ? $m_19 : ($roth_yr == 7 ? $m_20 : $m_20 ))))) );
			
			$husband_allo_RMD[] = [];
		@endphp
		
		
		<table class="calc-report">
			<thead>
				<tr>
					<th style="font-size:12px;">Roth Conversion</br>${{ number_format($husband_account_value) ?? ''}}</br>21% Bonus</th>
					<th></th>
					<th style="font-size:12px;">70</br>Yr 1</th>
					<th style="font-size:12px;">71</br>Yr 2</th>
					<th style="font-size:12px;">72</br>Yr 3</th>
					<th style="font-size:12px;">73</br>Yr 4</th>
					<th style="font-size:12px;">74</br>Yr 5</th>
					<th style="font-size:12px;">75</br>Yr 6</th>
					<th style="font-size:12px;">76</br>Yr 7</th>
					<th style="font-size:12px;">77</br>Yr 8</th>
					<th style="font-size:12px;">Annual Converted</th>
					<th style="font-size:12px;">Year End Roth Value</th>
					<th style="font-size:12px;">Year End Account Value</th>
				</tr>
			</thead>
			<tbody>
				@for($col = 1; $col <= 13; $col++)
					@php 
						$index12_previous = 0;
					@endphp
					<td>
						<table class="calc-report">
							@for($row = 1; $row <= 9; $row++)
								
									@php
										//$a12 = 0;
										//$a14 = 0;
										//$a17 = 0;
										//$a20 = 0;
										
										//if($col==3)
										//{
											$h_acc_value = $husband_account_value ?? '';
											$a12 = round($h_acc_value * 0.21);
											$a14 =  $h_acc_value + $a12;
											$a17 =  round($a14 * 1.05);
											$a20_pre = round($a17/6);
										//}
										
										$a20 =  round($a17/6);
										$index = $col.$row;
									@endphp
								
								<tr>
									<td style="font-size:14px ;height:10px;text-align: left">
									@if($col == 1 && $row == 1)
										$ {{ number_format($a12) }}
									@endif
									
									@if($col == 1 && $row == 2)
										$ {{ number_format($a14) }}
									@endif
									
									@if($col == 1 && $row == 3)
										5%
									@endif
									
									@if($col == 1 && $row == 4)
										Cons. Growth
									@endif
									
									@if($col == 1 && $row == 5)
										$ {{ number_format($a17) }}
									@endif
									@if($col == 1 && $row == 7)
										Conversion
									@endif
									
									@if($col == 1 && $row == 8)
										$ {{ number_format($a20) }}
									@endif
									
									{{ husband_roth_tax_conversion()[$index] ?? '' }}
									
									@if($col >= 3 && $col<=$max_yr)
									    @if($row == 1 && $col<=8)
											$ {{ $index17_previous == 0 ? number_format($a17) : number_format(round($index17_previous-$index19_previous)) }}
											@php
										     if($index17_previous == 0 && $index19_previous == 0){								$index12_previous = $a17;
											   }
											   else{
												   $index12_previous = $index17_previous-$index19_previous;
											   }
											   
											@endphp
										@elseif($row == 2 && $col<=8)
										   $ {{ number_format(round($index12_previous*0.0095)) }}
										   @php 
											  $index13_previous = $index12_previous*0.0095;
											@endphp
										@elseif(($col >=3 && $col <= 8) && $row ==3)
											$ {{ number_format($a20) ?? '' }}
										@elseif($row == 4 && $col<=8)
											$ {{ number_format(round($a20*0.22)) }}
											@php 
											    $index15_previous = $a20*0.22;
											@endphp
										@elseif($row == 5 && $col<=8)
											$ {{ number_format(round($a20-$index15_previous)) }}
											@php
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
											
											@endphp
										@elseif($row == 6 && $col<=8)
										$ {{ number_format(round(($index12_previous-$index13_previous-$a20) * 1.05)) }}
											@php
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
												
											@endphp
										@elseif($row == 7 && $col<=9)
											@if($col==3)
											$ {{ number_format($m_14) }}
											@elseif($col==4)
											$ {{ number_format($m_15) }}
											@elseif($col==5)
											$ {{ number_format($m_16) }}
											@elseif($col==6)
											$ {{ number_format($m_17) }}
											@elseif($col==7)
											$ {{ number_format($m_18) }}
											@elseif($col==8)
											$ {{ number_format($m_19) }}
											@elseif($col==9)
											$ {{ number_format($m_20) }}
											@endif
											
										@elseif($row == 8)
											@php
												$index19_previous = 0;
											@endphp
												@if($col == 6)
													$ {{ number_format(distribution_period()[73][1]) }}
													@php
													    $index19_previous= distribution_period()[73][1];
													@endphp
												@endif
												
												@if($col == 7)
												
											       $ {{ number_format(distribution_period()[74][1]) }}
												   
												   @php
													 $index19_previous= distribution_period()[74][1];
													@endphp
												
												@endif
												
												@if($col == 8)
												
													$ {{ number_format(distribution_period()[75][1]) }}
													
													@php
													 $index19_previous= distribution_period()[75][1];
													@endphp
												
												@endif
												
												@if($col == 9)
												
													$ {{ number_format(distribution_period()[76][1]) }}
													
													@php 
													   $h_19 = distribution_period()[76][1];
													@endphp
												
												@endif
										@endif
										
										@if($row==1 && $col==9)
										$ {{ number_format(round($index17_previous - distribution_period()[75][1])) }}
										@php 
										  $i12 = round($index17_previous - distribution_period()[76][1]);
										@endphp
										
										@endif
										
										@if($row==2 && $col==9)
											$ {{ number_format($i12 * 0.0095) }}
										
											@php 
											  $i13 = $i12 * 0.0095;
											@endphp
										@endif
										
										@if($row==3 && $col==9)
											$ {{ number_format($i12 - $i13) }}
										
											@php 
											  $i14 = $i12 - $i13;
											@endphp
										@endif
										@if($row==4 && $col==9)
											$ {{ number_format($i14 * 0.22) }}
										
											@php 
											  $i15 = $i14 * 0.22;
											@endphp
										@endif
										
										@if($row==5 && $col==9)
											$ {{ number_format($i14 - $i15) }}
										
											@php 
											  $i16 = $i14 - $i15;
											@endphp
										@endif
										
										@if($row==6 && $col==9)
											$ {{ number_format($i12 - $i13 - $i14) }}
										
											@php 
											  $i17 = $i12 - $i13 - $i14;
											@endphp
										@endif
									@endif
									
									@if($col>10)
										@if($row<=$roth_yr)
											@if($col==11 && $row<=7)
												Year {{$row}}
											@endif
											
											@if($col==12)
												@if($row == 1)
													${{ number_format(round($C16*1.05))  }}
													@php 
													  $L_14 = $C16*1.05;
													@endphp
												@endif
												
												@if($row == 2)
													${{ number_format(round($L_14+ $D_16) * 1.05)  }}
													@php 
													  $L_15 = ($L_14+ $D_16) * 1.05;
													@endphp
												@endif
												
												@if($row == 3)
													${{ number_format(round($L_15+ $E_16) * 1.05)  }}
													@php 
													  $L_16 = ($L_15+ $E_16) * 1.05;
													@endphp
												@endif
												
												@if($row == 4)
													${{ number_format(round($L_16+ $F_16) * 1.05)  }}
													@php 
													  $L_17 = ($L_16+ $F_16) * 1.05;
													@endphp
												@endif
												
												@if($row == 5)
													${{ number_format(round($L_17+ $G_16) * 1.05)  }}
													@php 
													  $L_18 = ($L_17+ $G_16) * 1.05;
													@endphp
												@endif
												
												@if($row == 6)
													${{ number_format(round($L_18+ $H_16) * 1.05)  }}
													@php 
													  $L_19 = ($L_18+ $H_16) * 1.05;
													@endphp
												@endif
												
												@if($row == 7)
												  ${{ number_format(round($L_19+ $i16) * 1.05)  }}
													@php 
													  $L_20 = ($L_19+ $i16) * 1.05;
													@endphp
												@endif
												
											@endif
											
											@if($col==13)
												@if($row == 1)
													${{ number_format(round($C_17+$L_14))  }}
													@php 
														$M_14 = $C_17+$L_14;
													@endphp
												@endif
												
												@if($row == 2)
													${{ number_format(round($D_17+$L_15))  }}
													@php 
													  $M_15 = $D_17+$L_15;
													@endphp
												@endif
												
												@if($row == 3)
													${{ number_format(round($E_17+$L_16))  }}
													@php 
													  $M_16 = $E_17+$L_16;
													@endphp
												@endif
												
												@if($row == 4)
													${{ number_format(round($F_17+$L_17))  }}
													@php 
													  $M_17 = $F_17+$L_17;
													@endphp
												@endif
												
												@if($row == 5)
													${{ number_format(round($G_17+$L_18))  }}
													@php 
													  $M_18 = $G_17+$L_18;
													@endphp
												@endif
												
												@if($row == 6)
													${{ number_format(round($H_17+$L_19))  }}
													@php 
													  $M_19 = $H_17+$L_19;
													@endphp
												@endif
												
												@if($row == 7)
													${{ number_format( round(($J_16+$L_20) * 1.05) )  }}
													@php 
													  $M_20 = ($J_16+$L_20) * 1.05;
													@endphp
												@endif
											@endif
										@endif
									@endif
									
									@if($col==3 && $row==9)
										<strong>IRA $ {{ number_format($husband_account_value) }}</strong>
									@elseif($col==10 && $row==9)
									<strong>Tax Free $ {{ number_format($tax_free_val) }}</strong>
									@endif
									</td>
								</tr>
							@endfor
							
							
						</table>
					</td>
				@endfor
			</tbody>
		</table>
		<br>
		<p class="fixed-company-name">
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>
		<table class="footer">
			<tr>
				<td style="text-align: left;font-size: 12px;">
					The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity
					Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide.
					The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees
					are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the
					greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.</br></br>

					Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences".
				</td>
			</tr>
		</table>
	</div>
	@endif
	
	@if(!empty($current_finance_wife_data))
	<div style="page-break-after: always;">
		<table>
			<tr>
				<td width="80%">
					<h3 class="heading-color" style="text-align:center;">Wife Roth Conversion From Taxable To Free Tax</h3>
				</td>
				<td width="20%">
				@if(isset($setting->advisor_logo))
					<img src="{{ url('uploads/advisor_logo/'. $setting->advisor_logo) }}" width="200">
				@else
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="200">
				@endif
				</td>
			</tr>
			
		</table>
		<table style="margin-bottom: 20px;">
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Primary Goals: Income, Tax Reduction, Legacy
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Desired Retirement Income ${{ number_format($desired_retirement_income) }} + 3% inflation
				</td>
			</tr>
		</table>
		@php 
			$a12 = 0;
			$a15 = 0;
			$a18 = 0;
			$a20 = 0;
			$index_18 = 0 ;
			$index_20 = 0 ;
			$index_21 = 0 ;
			$O_15 = 0;
			$O_16 = 0;
			$O_17 = 0;
			$O_18 = 0;
			$O_19 = 0;
			$O_20 = 0;
			$O_21 = 0;
			$O_22 = 0;
			$O_23 = 0;
			$G_12 = 0;
			$H_12 = 0;
			$I_12 = 0;
			$J_12 = 0;
			$K_12 = 0;
			$L_12 = 0;
			$roth_year_start = 15;
			$P_15 = 0;
			$P_16 = 0;
			$P_17 = 0;
			$P_18 = 0;
			$P_19 = 0;
			$P_20 = 0;
			$P_21 = 0;
			$P_22 = 0;
			$P_23 = 0;
			$tot_amt_val = 15;
			$wife_allo_RMD = [];
		
		@endphp
		</hr>
		<table class="calc-report">
			<thead>
				<tr>
					<th style="font-size:12px;">Roth Conversion</br>${{ number_format($wife_account_value) ?? ''}}</br>21% Bonus</th>
					<th></th>
					<th></th>
					<th style="font-size:12px;">69</br>End of ></th>
					<th style="font-size:12px;">70</br>Yr 1</th>
					<th style="font-size:12px;">71</br>Yr 2</th>
					<th style="font-size:12px;">72</br>Yr 3</th>
					<th style="font-size:12px;">73</br>Yr 4</th>
					<th style="font-size:12px;">74</br>Yr 5</th>
					<th style="font-size:12px;">75</br>Yr 6</th>
					<th style="font-size:12px;">76</br>Yr 7</th>
					<th style="font-size:12px;">77</br>Yr 8</th>
					<th style="font-size:12px;"></th>
					<th style="font-size:12px;">Annual Converted</th>
					<th style="font-size:12px;">Year End Roth Value</th>
					<th style="font-size:12px;">Total Account Value</th>
				</tr>
			</thead>
			<tbody>
				@for($col = 1; $col <= 16; $col++)
					@php 
						$index_12 = 0;
						$index_15 = 0;
						$index_16 = 0;
						$index_13 = 0;
						$index_17 = 0;
					@endphp
					<td>
						<table class="calc-report" border="0">
							@for($row = 1; $row <= 14; $row++)
							@php 
								$index = $col.$row;
								
								$w_acc_value = $wife_account_value ?? '';
								$a12 = round($w_acc_value * 0.21);
								$a15 = $wife_account_value + $a12;
								
								$a18 = round($a15 * (1 + 0.05));
							@endphp
							<tr>
								<td class="wife-cal"  style="font-size:14px; {{ $col==2 ? 'display:none':($col==3 ? 'height:10px;width:100px;text-align:left' : ($col==13 ? 'height:10px;width:70px;text-align: left' : 'height:10px;width:0px;text-align: center')) }}">
									@if($col == 1)
										@if($row == 1)
											$ {{ number_format($a12) }}
										@elseif($row == 3)
											$ {{ number_format($a15) }}
										@elseif($row == 4)
											5%
										@elseif($row == 5)
											Conservative growth
										@elseif($row == 6)
											 $ {{ number_format($a18) }}
										@elseif($row == 8)
											$ {{ number_format($a18/$wife_roth_yr) }}
											@php 
												$a20 = $a18/$wife_roth_yr;
											@endphp
										@endif
									@elseif($col == 2 && $row == 4)
									  
									@elseif($col == 3)
									{{ wife_roth_tax_conversion()[$index] ?? '' }}
									@elseif($col == 4)
									    @if($row == 1)
											  {{ number_format($a15) }}
											@php 
												$index_12 = $a15;
											@endphp
										@endif
										
										@if($row == 2)
											{{ number_format(round($index_12 * 0.0095)) }}
										@endif
										
										@if($row == 3)
											&nbsp;
										@endif
										@if($row == 4)
											&nbsp;	
										@endif
										@if($row == 5)
											 &nbsp;	
										@endif
										@if($row == 6)
											{{ number_format($a18) }}
											@php 
												$index_18 = $a18;
											@endphp
										@endif
										@if($row == 7)
											 &nbsp;	
										@endif
										@if($row == 8)
											 &nbsp;	
										@endif
										@if($row == 9)
											 &nbsp;
										@endif
										@if($row == 9)
											 &nbsp;
										@endif
										@if($row == 10)
											 &nbsp;
										@endif
									@endif
									
									@if($col>4 && $col<=$wife_max_year)
										@if($row == 1)
											@if($col==5)
												{{ number_format($index_18 - $index_21)}}
												@php 
													$index_12 = $index_18 - $index_21;
												@endphp
											@elseif($col>8 && $col<13)
											
											{{ number_format(($index_18*1.05) - $index_20) }}
												@php 
													$index_12 = ($index_18*1.05) - $index_21;
													if($col==7)
													{
														$G_12 = ($index_18*1.05) - $index_21;
													}
													elseif($col==8)
													{
														$H_12 = ($index_18*1.05) - $index_21;
													}
													elseif($col==9)
													{
														//$I_12 = ($index_18*1.05) - $index_21;
														
														$I_12 = ($index_18*1.05) - $index_20;
														
														$index_12 = ($index_18*1.05) - $index_20;
													}
													elseif($col==10)
													{
														//$J_12 = ($index_18*1.05) - $index_21;
														
														$J_12 = ($index_18*1.05) - $index_20;
														
														$index_12 = ($index_18*1.05) - $index_20;
													}
													elseif($col==11)
													{
														//$K_12 = ($index_18*1.05) - $index_21;
														
														$K_12 = ($index_18*1.05) - $index_20;
														
														$index_12 = ($index_18*1.05) - $index_20;
													}
													elseif($col==12)
													{
														//$L_12 = ($index_18*1.05) - $index_21;
														
														$L_12 = ($index_18*1.05) - $index_20;
														
														$index_12 = ($index_18*1.05) - $index_20;
													}
												
												@endphp
											
											@else
												{{ number_format(($index_18*1.05) - $index_21) }}
												@php 
													$index_12 = ($index_18*1.05) - $index_21;
													if($col==7)
													{
														$G_12 = ($index_18*1.05) - $index_21;
														$wife_allo_RMD[72] = $G_12;
													}
													elseif($col==8)
													{
														$H_12 = ($index_18*1.05) - $index_21;
														$wife_allo_RMD[73] = $H_12;
													}
													elseif($col==9)
													{
														$I_12 = ($index_18*1.05) - $index_21;
														$wife_allo_RMD[74] = $I_12;
													}
													elseif($col==10)
													{
														$J_12 = ($index_18*1.05) - $index_21;
														$wife_allo_RMD[75] = $J_12;
													}
													elseif($col==11)
													{
														$K_12 = ($index_18*1.05) - $index_21;
														$wife_allo_RMD[76] = $K_12;
													}
													elseif($col==12)
													{
														$L_12 = ($index_18*1.05) - $index_21;
														$wife_allo_RMD[77] = $L_12;
													}
												
												@endphp
											@endif
										@elseif($row == 2)
											{{ number_format($index_12* 0.0095) }}
											@php 
												$index_13 = $index_12* 0.0095;
											@endphp
										@elseif($row == 3)
											@if($col==5)
										     {{ number_format($a18/$wife_roth_yr) }}
											 @php 
												$index_15 = $a18/$wife_roth_yr;
											 @endphp
											@endif
											
											@if($wife_max_year==6 && $col==6)
										     {{ number_format($index_12-$index_13) }}
											 @php 
												$index_15 = $index_12-$index_13;
											 @endphp
											@elseif($col==6)
												{{ number_format($a18/$wife_roth_yr) }}
												 @php 
													$index_15 = $a18/$wife_roth_yr;
												 @endphp
											@endif
											
											@if($wife_max_year==7 && $col==7)
										     {{ number_format($index_12-$index_13) }}
												 @php 
													$index_15 = $index_12-$index_13;
												 @endphp
											@elseif($col==7)
												{{ number_format($a18/$wife_roth_yr) }}
												 @php 
													$index_15 = $a18/$wife_roth_yr;
												 @endphp
											@endif
											
											@if($wife_max_year==8 && $col==8)
										     {{ number_format($index_12-$index_13) }}
											 @php 
												$index_15 = $index_12-$index_13;
											 @endphp
											@elseif($col==8)
												{{ number_format($a18/$wife_roth_yr) }}
												@php 
													$index_15 = $a18/$wife_roth_yr;
												@endphp
											@endif
											
											@if($wife_max_year==9 && $col==9)
										     {{ number_format($index_12-$index_13) }}
											 @php 
												$index_15 = $index_12-$index_13;
											 @endphp
											@elseif($col==9)
												{{ number_format($a18/$wife_roth_yr) }}
												 @php 
													$index_15 = $a18/$wife_roth_yr;
												 @endphp
											@endif
											
											@if($wife_max_year==10 && $col==10)
										     {{ number_format($index_12-$index_13) }}
											 @php 
												$index_15 = $index_12-$index_13;
											 @endphp
											@elseif($col==10)
												{{ number_format($a18/$wife_roth_yr) }}
												 @php 
													$index_15 = $a18/$wife_roth_yr;
												 @endphp
											@endif
											
											@if($wife_max_year==11 && $col==11)
										     {{ number_format($index_12-$index_13) }}
											 @php 
												$index_15 = $index_12-$index_13;
											 @endphp
											@elseif($col==11)
												{{ number_format($a18/$wife_roth_yr) }}
												 @php 
													$index_15 = $a18/$wife_roth_yr;
												 @endphp
											@endif
											
											
											@if($col==12)
										     {{ number_format($index_12-$index_13) }}
											 @php 
												$index_15 = $index_12-$index_13;
											 @endphp
											@endif
										@elseif($row == 4)
											{{number_format($index_15 * 0.24) }}
											@php
												$index_16 = $index_15 * 0.24;
											@endphp
										@elseif($row == 5)
											{{number_format($index_15-$index_16) }}
											
											@php 
												$index_17 = $index_15-$index_16;
											@endphp
										@elseif($row == 6)
											{{ number_format($index_12-$index_13-$index_15) }}
											@php 
												$index_18 = $index_12-$index_13-$index_15;
											@endphp
										@elseif($row == 7)
											@if($col==5)
												{{ number_format($index_18 + ($index_17*1.05))}}
												@php
													$O_15 = $index_17*1.05;
													
													$P_15 = $index_18 + ($index_17*1.05);
												@endphp
											@endif
											
											@if($col==6)
												@php
													$O_16 = ($O_15 + $index_17)*1.05;
													
													$P_16 = $index_18 + $O_16;
												@endphp
												{{ number_format($index_18 + $O_16)}}
											@endif
											@if($col==7)
												@php
													$O_17 = ($O_16 + $index_17)*1.05;
													$P_17 = $index_18 + $O_17;
												@endphp
												{{ number_format($index_18 + $O_17)}}
											@endif
											@if($col==8)
												@php
													$O_18 = ($O_17 + $index_17)*1.05;
													$P_18 = $index_18 + $O_18;
												@endphp
												{{ number_format($index_18 + $O_18)}}
											@endif
											@if($col==9)
												@php
													$O_19 = ($O_18 + $index_17)*1.05;
													$P_19 = $index_18 + $O_19;
												@endphp
												{{ number_format($index_18 + $O_19)}}
											@endif
											@if($col==10)
												@php
													$O_20 = ($O_19 + $index_17)*1.05;
													$P_20 = $index_18 + $O_20;
												@endphp
												{{ number_format($index_18 + $O_20)}}
											@endif
											@if($col==11)
												@php
													$O_21 = ($O_20 + $index_17)*1.05;
													$P_21 = $index_18 + $O_21;
												@endphp
												{{ number_format($index_18 + $O_21)}}
											@endif
											@if($col==12)
												@php
													$O_22 = ($O_21 + $index_17)*1.05;
													$P_22 = $index_18 + $O_22;
												@endphp
												{{ number_format($index_18 + $O_22)}}
											@endif
											
										@elseif($row == 8)
											<strong>
											@if($col==8)
											{{ number_format($G_12/wife_distribution_period()[73][0]) }}
												@php 
												$index_20 = $G_12/wife_distribution_period()[73][0];
												//$index_20 = 101104;
												@endphp
											@endif
											@if($col==9)
											{{ number_format($H_12/wife_distribution_period()[74][0]) }}
												@php 
												$index_20 = $H_12/wife_distribution_period()[74][0];
												@endphp
											@endif
											@if($col==10)
											{{ number_format($I_12/wife_distribution_period()[75][0]) }}
												@php 
												$index_20 = $I_12/wife_distribution_period()[75][0];
												@endphp
											@endif
											@if($col==11)
											{{ number_format($J_12/wife_distribution_period()[76][0]) }}
												@php 
												$index_20 = $J_12/wife_distribution_period()[76][0];
												@endphp
											@endif
											@if($col==12)
											{{ number_format($K_12/wife_distribution_period()[77][0]) }}
												@php 
												$index_20 = $K_12/wife_distribution_period()[77][0];
												@endphp
											@endif
											</strong>
										@elseif($row == 9)
											&nbsp;
										@elseif($row == 10)
											&nbsp;
										@endif
									@endif
									@if($col==13 && $row==7)
										@php
											$O_23 = ($O_22 + $index_17)*1.05;
										@endphp
										{{ number_format($index_18 + $O_23)}}
									@endif
									@if($col==14)
										@if($row>2 && $row<$wife_max_year-1)
											Year {{$row-2}}
										@endif
									@endif
									@if($col==15)
										@if($row>2 && $row<$wife_max_year-1)
											$ {{  number_format(${"O_".$roth_year_start}) }}
											@php 
												$roth_year_start++;
											@endphp
										@endif
									@endif
									@if($col==16)
										@if($row>2 && $row<$wife_max_year-1)
											$ {{  number_format(${"P_".$tot_amt_val}) }}
											@php 
												$tot_amt_val++;
											@endphp
										@endif
									@endif
									@if($col==13 && $row==13)
										<strong>Tax Free $ {{ $wife_roth_yr == 1 ? number_format($P_15) : ($wife_roth_yr == 2 ? number_format($P_16) : ($wife_roth_yr == 3 ? number_format($P_17) : ($wife_roth_yr == 4 ? number_format($P_18) : ($wife_roth_yr == 5 ? number_format($P_19) : ($wife_roth_yr == 6 ? number_format($P_20): ($wife_roth_yr == 7 ? number_format($P_21):number_format($P_22) ))))))}}</strong>
									@endif
								</td>
							</tr>
							@endfor
						</table>
					</td>
				@endfor
			</tbody>
		</table>
		<p class="fixed-company-name">
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>
		<table class="footer">
			<tr>
				<td style="text-align: left;font-size: 12px;">
					The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity
					Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide.
					The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees
					are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the
					greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.</br></br>

					Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences".
				</td>
			</tr>
		</table>
	</div>
	@endif
	
	@php 
		$total_irs_partner2 = 0;
		$total_estate2 = 0;
		$total_tax_free_asset = 0;
		$total_husband_tax_free_inc_key = 0;
		$total_wife_tax_free_inc_key = 0;
		$total_wife_tax_free_inc = 0;
		$total_husband_tax_free_inc = 0;
		$total_husband_rmd_inc2 = 0;
		$total_wife_rmd_inc2 =0;
		$total_husband_rmd_inc2_key = 0;
		$total_wife_rmd_inc2_key = 0;
		$total_irs_partner2_key = '';
		$total_tax_rate2_key = '';
		$total_estate2_key = '';
		$total_tax_free_asset_key = '';
		$last_tax_rate2 = '';
	@endphp
	
	
	<div style="page-break-after: always;">
		{{--<div>
			<h2><strong style="margin-left:200px;">Financial Allocation Plan Details Husband And Wife</strong></h2>
		</div>--}}
		<table>
			<tr>
				<td width="80%">
					<h3 class="heading-color" style="text-align:center;">Fidelity Mutual Financial <br>Custom Tax Efficient Retirement Plan <br>Designed for Darryl and Masako Stein</h3>
				</td>
				<td width="20%">
				@if(isset($setting->advisor_logo))
					<img src="{{ url('uploads/advisor_logo/'. $setting->advisor_logo) }}" width="200">
				@else
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="200">
				@endif
				</td>
			</tr>
			
		</table>
		<table style="margin-bottom: 20px;">
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Primary Goals: Income, Tax Reduction, Legacy
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class="section-title">
					Financial Allocation Plan Details Husband And Wife
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Desired Retirement Income ${{ number_format($desired_retirement_income) }} + 3% inflation
				</td>
			</tr>
		</table>
		@php 
			$total_ira_inc2_key = [];
			$total_rmd_inc2_key = [];
		@endphp
		<table class="calc-report">
			<thead>
				<tr>
				@if(!empty($plan_allocation_header))
					@foreach($plan_allocation_header as $h=>$header)
					 @php 
						$th_bg_color = '';
						if($header == 'RMD')
						{
							$th_bg_color = 'red';
						}
						
						if($header == 'Taxable Income')
						{
							$th_bg_color = 'red';
						}
						
						if($header == 'Tax Rates')
						{
							$th_bg_color = 'red';
						}
						
						if($header == 'IRMAA')
						{
							$th_bg_color = 'red';
							
						}
						
						
						if(stripos($header, 'IRA') !== false)
						{
							$th_bg_color = 'red';
						}
						
						if($header == 'Tax Free Assets')
						{
							$th_bg_color = '#A1F21D';
						}	
						
					 @endphp
						<th style="font-size:10px; background-color:{{ $th_bg_color }}">{{ $header }}</th>
						@if($header == 'IRS Partner')
							@php
								$total_irs_partner2_key = $h;
							@endphp
						@endif
						
						@if($header == 'Tax Rates')
							@php
								$total_tax_rate2_key = $h;
							@endphp
						@endif
						
						@if($header == 'Total Estate')
							@php
								$total_estate2_key = $h;
							@endphp
						@endif
						
						@if($header == 'Tax Free Assets')
							@php
								$total_tax_free_asset_key = $h;
							@endphp
						@endif
						
						@if($header == 'Tax Free Assets')
							@php
								$total_tax_free_asset_key = $h;
							@endphp
						@endif
						
						@if($header == $wife_tax_free_Income)
							@php
								$total_wife_tax_free_inc_key = $h;
							@endphp
						@endif
						
						@if($header == $hus_tax_free_Income)
							@php
								$total_husband_tax_free_inc_key = $h;
							@endphp
						@endif
						
						@if($header == $hus_RMD_Income)
							@php
								$total_husband_rmd_inc2_key = $h;
							@endphp
						@endif
						
						@if($header == $wife_RMD_Income)
							@php
								$total_wife_rmd_inc2_key = $h;
							@endphp
						@endif
						
						@if($header == 'IRMAA')
							@php
								$total_irmaa_inc2_key = $h;
							@endphp
						
						@endif
						
						@if($header == 'RMD')
							@php
								$total_rmd_inc2_key[] = $h;
							@endphp
						@endif
						
						@if(stripos($header, 'IRA') !== false)
							@php
								$total_ira_inc2_key[] = $h;
							@endphp
						@endif
						
					@endforeach
				@endif
				</tr>
			</thead>
			
			@if(!empty($plan_allocation_value))
				@foreach($plan_allocation_value as $key=>$excelheaderValue)
					@foreach($excelheaderValue as $k=>$headerVal)
						@if($total_irs_partner2_key == $k)
							@php
							$total_irs_partner2_numeric = (int) str_replace(',', '', $headerVal);
								$total_irs_partner2 = $total_irs_partner2 + $total_irs_partner2_numeric;
							@endphp 
						@endif
						
						@if($total_tax_rate2_key == $k)
							@php
							$last_tax_rate2_numeric = (int) str_replace(',', '', $headerVal);
								$last_tax_rate2 = $last_tax_rate2_numeric;
							@endphp 
						@endif
						
						@if($total_estate2_key == $k)
							@php
							$total_estate2_numeric = (int) str_replace(',', '', $headerVal);
								$total_estate2 = $total_estate2 + $total_estate2_numeric;
							@endphp 
						@endif
						
						@if($total_tax_free_asset_key == $k)
							@php
							$total_tax_free_asset_numeric = (int) str_replace(',', '', $headerVal);
								$total_tax_free_asset = $total_tax_free_asset + $total_tax_free_asset_numeric;
							@endphp 
						@endif
						
						@if($total_wife_tax_free_inc_key == $k)
							@php
							$total_wife_tax_free = (int) str_replace(',', '', $headerVal);
								$total_wife_tax_free_inc = $total_wife_tax_free_inc + $total_wife_tax_free;
							@endphp 
						@endif
						
						@if($total_husband_tax_free_inc_key == $k)
							@php
							$total_husband_tax_free = (int) str_replace(',', '', $headerVal);
								$total_husband_tax_free_inc = $total_husband_tax_free_inc + $total_husband_tax_free;
							@endphp 
						@endif
						
						@if($total_husband_rmd_inc2_key == $k)
							@php
							$total_husband_rmd2 = (int) str_replace(',', '', $headerVal);
								$total_husband_rmd_inc2 = $total_husband_rmd_inc2 + $total_husband_rmd2;
							@endphp 
						@endif
						
						@if($total_wife_rmd_inc2_key == $k)
							@php
							$total_wife_rmd2 = (int) str_replace(',', '', $headerVal);
								$total_wife_rmd_inc2 = $total_wife_rmd_inc2 + $total_wife_rmd2;
							@endphp 
						@endif
						
					@endforeach
				@endforeach
			@endif
			<tbody>
			@if(!empty($plan_allocation_value))
					@foreach($plan_allocation_value as $key=>$excelheaderValue)
						<tr>
							@foreach($excelheaderValue as $k=>$headerVal)
							
							@php 
								$tr_bg_color = '';
								if(in_array($k, $total_rmd_inc2_key))
								{
									$tr_bg_color = 'red';
								}
								
								if($total_tax_rate2_key == $k)
								{
									$tr_bg_color = 'red';
								}
								
								if($total_irmaa_inc2_key == $k)
								{
									$tr_bg_color = 'red';
								}
								
								if($total_tax_free_asset_key == $k)
								{
									$tr_bg_color = '#A1F21D';
								}
								
								if(in_array($k, $total_ira_inc2_key))
								{
									$tr_bg_color = 'red';
								}
								
							@endphp
							<td style="font-size:10px; background-color: {{ $headerVal !='' ? $tr_bg_color : ''}}">{{ $headerVal ?? '' }}</td>
							@endforeach
						</tr>
					@endforeach
					<tr><td>&nbsp;</td></tr>
					
			@endif
			</tbody>
		</table>
		{{--<p class="fixed-company-name">
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>--}}
		{{--<table class="footer">
			<tr>
				<td style="text-align: left;font-size: 12px;">
					The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity
					Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide.
					The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees
					are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the
					greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.</br></br>

					Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences".
				</td>
			</tr>
		</table>--}}
	</div>
	
	<div style="page-break-after: always;">
		{{--<div>
			<h2><strong style="margin-left:200px;">Comparison Summary Report</strong></h2>
		</div>--}}
		<table>
			<tr>
				<td width="80%">
					<h3 class="heading-color" style="text-align:center;">Fidelity Mutual Financial <br>Custom Tax Efficient Retirement Plan <br>Designed for Darryl and Masako Stein</h3>
				</td>
				<td width="20%">
				@if(isset($setting->advisor_logo))
					<img src="{{ url('uploads/advisor_logo/'. $setting->advisor_logo) }}" width="200">
				@else
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="200">
				@endif
				</td>
			</tr>
			
		</table>
		<table style="margin-bottom: 20px;">
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Primary Goals: Income, Tax Reduction, Legacy
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class="section-title">
					Comparison Summary Report
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td class="heading-color" style="font-weight: 700;">
					Desired Retirement Income ${{ number_format($desired_retirement_income) }} + 3% inflation
				</td>
			</tr>
		</table>
		
		<table class="calc-report">
			<thead>
				<tr>
				<td style="background-color:red;"><h3>Current Plan</h3></td>
				<td></td>
				<td><h3>Fidelity Mutual Finance</h3></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="font-size:15px; background-color:red;text:bold;"><strong>$ {{ number_format($total_irs_partner) }}</strong></td>
					<td style="font-size:15px;text:bold;"><h3>Total Taxes Paid By Age 95</h3></td>
					<td style="font-size:15px; background-color:#A1F21D;text:bold;"><strong>$ {{ number_format($total_irs_partner2) }}</strong></td>
				</tr>
				<tr>
					<td style="font-size:15px; background-color:red;text:bold;"><strong>{{ $last_tax_rate }} %</strong></td>
					<td style="font-size:15px;text:bold;"><h3>Tax Bracket by Age 95</h3></td>
					<td style="font-size:15px; background-color:#A1F21D;text:bold;"><strong>{{ $last_tax_rate2 }} %</strong></td>
				</tr>
				<tr>
					<td style="font-size:15px; background-color:red;;text:bold;"><strong>$ {{ number_format($total_estate) }}</strong></td>
					<td style="font-size:15px;;text:bold;"><h3>Total Estate Value by Age 95</h3></td>
					<td style="font-size:15px;background-color:#A1F21D;;text:bold;"><strong>$ {{ number_format($total_estate2) }}</strong></td>
				</tr>
				<tr>
					<td style="font-size:15px; background-color:red;;text:bold;"><strong>0</strong></td>
					<td style="font-size:15px;;text:bold;"><h3>Tax Free Asset Value by Age 90</h3></td>
					<td style="font-size:15px;background-color:#A1F21D;;text:bold;"><strong>$ {{ number_format($total_tax_free_asset) }}</strong></td>
				</tr>
				<tr>
					<td style="font-size:15px;background-color:red;;text:bold;"><strong>0</strong></td>
					<td style="font-size:15px;;text:bold;"><h3>Tax Free Income by Age 95</h3></td>
					<td style="font-size:15px; background-color:#A1F21D;;text:bold;"><strong>$ {{ number_format($total_wife_tax_free_inc + $total_husband_tax_free_inc) }}</strong></td>
				</tr>
				<tr>
					<td style="font-size:15px; background-color:red;;text:bold;"><strong>$ {{ number_format(($total_rmd_value + $total_wife_rmd_inc + $total_husband_rmd_inc) - $total_wife_rmd_val) }}</strong></td>
					<td style="font-size:15px;;text:bold;"><h3>IRA RMD’s by Age 95</h3></td>
					<td style="font-size:15px;background-color:#A1F21D;;text:bold;"><strong>$ {{ number_format($total_husband_rmd_inc2 +  $total_wife_rmd_inc2 ) }}</strong></td>
				</tr>
			</tbody>
		</table>
		{{--<p class="fixed-company-name">
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>--}}
		<table class="footer">
			<tr>
				<td style="text-align: left;font-size: 12px;">
					The following calculators are made available as self-help tools for independent use. Fidelity Mutual Financial does not guarantee their applicability to any individual circumstances. Fidelity
					Mutual Financial encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide.
					The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent the current or future performance of any specific financial product. No guarantees
					are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the
					greater the risk. This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.</br></br>

					Intellectual Property of Fidelity Mutual Financial LLC: "Unauthorized duplication, distribution, or reproduction of this work in any form is strictly prohibited and will result in legal consequences".
				</td>
			</tr>
		</table>
	</div>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function(){
	var subTotalCurrent = $('#subTotalCurrent').val();
	var s_total = Number(subTotalCurrent).toLocaleString('en-IN');
	//alert(s_total);
	$('#tot_current_income').text(s_total);
});
</script>

