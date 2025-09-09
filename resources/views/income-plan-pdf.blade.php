@php 
use Carbon\Carbon;
$headerCount = count($excelheaderArray)-1;
//echo "<pre>";print_r($excelheaderArray);die;
//echo "<pre>";print_r($excelheaderValueArray);die;
//echo "<pre>";print_r(husband_roth_tax_conversion());die;
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
		];
	}
	elseif($financial_account->account_owner == 2)
	{
		$wifeAsset[] = [
			'account_owner' => 'Wife',
			'account_title' => $financial_account->account_title,
			'tax_qualification' => $financial_account->tax_qualification,
			'account_value' => $financial_account->account_value,
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
$roth_yr = $roth_year_data ? $roth_year_data->year : '';

@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Income Plan Cover</title>
    <style>
		@font-face {
			font-family: 'SofiaPro-Regular';
			src: url('{{ asset('front-assets/fonts/Sofia/SofiaPro.woff2') }}') format('woff2'),
				 url('{{ asset('front-assets/fonts/Sofia/SofiaPro.woff') }}') format('woff');
			font-weight: 500;
			font-style: italic;
		}

		body { font-family: SofiaPro-Regular; }
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
    </style>
</head>
<body>
	<div style="page-break-after: always;">
		<table>
			<tr>
				<td width="20%">
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="180">
				</td>
				<td width="80%">
					<p style="margin-left: 10px; color: #929292;">
						1233 NW 107th ter<br>
						Plantation, FL 33322<br>
						darryl.stein@gmail.com<br>
						267-280-3660
					</p>
				</td>
			</tr>
		</table>

		<table style="margin-top: 100px;">
			<tr>
				<!-- Left Side -->
				<td width="50%" style="border-right: 1px solid #3490CD;">
					<h1 style="color: #3490CD;">Building Rewarding Income Goals</h1>
					<h2>Income Allocation Tool</h2>
				</td>

				<!-- Right Side -->
				<td width="50%" style="padding-left: 20px;">
					<p><strong style="color: #3490CD;">Prepared For:</strong><br>
					Client and Partner</p>

					<p><strong style="color: #3490CD;">Agent/Representative:</strong><br>
					{{ $client_nm ?? ''}}</p>

					<p><strong style="color: #3490CD;">Date Prepared:</strong>
					{{ Carbon::parse($created_at)->format('d/m/Y') }}</p>
				</td>
			</tr>
		</table>

		<table class="footer">
			<tr>
				<td style="background-color: ;padding: 10px;text-align: left;font-size: 12px;">
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
				<td width="10%">
					<img src="{{ asset('front-assets/img/income-goals.png') }}" width="80">
				</td>
				<td width="90%">
					<h1 style="color: #3490CD;">Building Rewarding Income Goals</h1>
					<h2>Income Allocation Tool</h2>
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
		<table class="footer">
			<tr>
				<td style="background-color: ;padding: 10px;text-align: left;font-size: 12px;">
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
		<div class="section-title"></div>
		<table>
			<tr>
				<td width="80%" class="section-title">
					Current Financial Accounts
				</td>
				<td width="20%">
					<img src="{{ asset('front-assets/img/-logo1.png') }}" width="180">
				</td>
			</tr>
		</table>
		
		<!-- Wife & Husband Accounts -->
		<table>
			<tr>
				<!-- Wife's Accounts -->
				<td width="50%" valign="top">
					<strong>Wife's Accounts</strong><br>
					@if(!empty($wifeAsset))
						@foreach($wifeAsset as $val)
							@php 
								$tax_quali = $val['tax_qualification'] == 1 ? 'IRA ' : 'non-qualified';
								$subTotalWife += $val['account_value'];
								$w++;
							@endphp
							#{{ $w }} {{ $val['account_title'] }}&nbsp; {{$tax_quali ?? ''}}&nbsp;&nbsp; $ {{ number_format($val['account_value']) }} <br>
						@endforeach
					@endif
					{{--#1 Variable Annuity &nbsp;&nbsp; $2,377,000 <br>
					#2 401k T-IRA &nbsp;&nbsp; $156,000 <br><br>--}}
					<span class="subtotal">Subtotal $ {{ number_format($subTotalWife) }}</span>
				</td>

				<!-- Husband's Accounts -->
				<td width="50%" valign="top">
					<strong>Husband's Accounts</strong><br>
					@if(!empty($husbandAsset))
						@foreach($husbandAsset as $val)
							@php 
								$tax_quali = $val['tax_qualification'] == 1 ? 'IRA ' : 'non-qualified';
								$subTotalHusband += $val['account_value'];
								$h++;
							@endphp
							#{{ $h }} {{ $val['account_title'] }}&nbsp; {{ $tax_quali ?? '' }}&nbsp;&nbsp; $ {{ number_format($val['account_value']) }} <br>
						@endforeach
					@endif
					{{--#1 Variable Annuity &nbsp;&nbsp; $803,952 <br><br>--}}
					<span class="subtotal">Subtotal ${{ number_format($subTotalHusband) }}</span>
				</td>
			</tr>
		</table>

		<!-- Joint Accounts -->
		<table>
			<tr>
				<td width="100%" valign="top">
					<strong>Joint Accounts</strong><br>
					@if(!empty($jointAsset))
						@foreach($jointAsset as $val)
							@php 
								$tax_quali = $val['tax_qualification'] == 1 ? 'IRA ' : 'non-qualified';
								$subTotalJoint += $val['account_value'];
								$j++;
							@endphp
							#{{ $j }} {{ $val['account_title'] }}&nbsp; {{$tax_quali ?? ''}}&nbsp;&nbsp; $ {{ number_format($val['account_value']) }} <br>
						@endforeach
					@endif
					{{--#1 Variable Annuity &nbsp;&nbsp; $440,400 <br>
					#2 Savings &nbsp;&nbsp; $76,400 <br><br>--}}
					<span class="subtotal">Subtotal ${{ number_format($subTotalJoint) }}</span>
				</td>
				
			</tr>
		</table>

		<!-- Totals -->
		<table style="margin-left: -200px;">
			<tr>
				<td width="70%"></td>
				<td class="totals">Asset Total ${{ number_format($subTotalWife + $subTotalHusband + $subTotalJoint) }}</td>
			</tr>
			<tr>
				<td></td>
				<td class="totals">Income Total $ {{ number_format($incomeTotal) }}</td>
			</tr>
		</table>

		<!-- Current Income Accounts -->
		<table>
			<tr>
				<td width="50%" valign="top">
					<strong>Current Income Accounts</strong><br>
					@if($current_income_account->isNotEmpty())
						@foreach($current_income_account as $income_account)
						@php 
							$subTotalCurrent += $income_account->income_amount;
						@endphp
						{{ $income_account->client_name ?? ''}} &nbsp;&nbsp; ${{  number_format($income_account->income_amount) }} <br>
						@endforeach
					@endif
					{{--Wife SS &nbsp;&nbsp; $35,772 <br>
					Husband SS &nbsp;&nbsp; $25,764 <br><br>--}}
					
					<span class="subtotal">Subtotal $ {{ number_format($subTotalCurrent)}}</span>
				</td>
			</tr>
			<input type="hidden" id="subTotalCurrent" value="{{ $subTotalCurrent ?? 0 }}">
		</table>
		{{--<table>
			<tr>
				<td width="38%"></td>
				<td class="totals">Asset Total ${{ number_format($subTotalWife + $subTotalHusband + $subTotalJoint) }}</td>
			</tr>
			<tr>
				<td></td>
				<td class="totals">Income Total $ {{ number_format($incomeTotal) }}</td>
			</tr>
		</table>--}}

		<br><br>
		<p>
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>
		<table class="footer">
			<tr>
				<td style="background-color: ;padding: 10px;text-align: left;font-size: 12px;">
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
	$total_wife_rmd_inc_key = '';
	$total_husband_rmd_inc_key = '';
	$total_joint_rmd_inc_key = '';
	$total_inc_tax_key = '';
	$total_IRMAA_key = '';
	$total_irs_partner_key = '';
	$total_estate_key = '';
	$rmd_position_keys = [];
	$total_rmd_inc = [];
	$count_rmd = 0;
	$total_rmd_value = 0;
	
	@endphp
	<div style="page-break-after: always;">
		<table>
			<tr>
				<td class="section-title">
					Current Allocation Plan Details
				</td>
			</tr>
		</table>
		<table class="">
			<thead>
				<tr>
					<td>
						Desired Income ${{$desired_retirement_income}}
					</td>
				</tr>
			</thead>
		</table>
		<table class="calc-report">
			<thead>
			<tr>
			    @if(!empty($excelheaderArray))
					@foreach($excelheaderArray as $h=>$header)
					<th>{{ $header ?? '' }}</th>
					
					
					
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
					
					@if($header == 'Wife RMD/Income')
						@php
							$total_wife_rmd_inc_key = $h;
							$count_rmd =0;
						@endphp
					@endif
					
					@if($header == 'Husband RMD/Income')
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
						
							<tr>
							@foreach($excelheaderValue as $k=>$headerVal)
							<td>{{ $headerVal ?? '' }}</td>
							@endforeach
							</tr>
						
					@endforeach
					<tr><td>&nbsp;</td></tr>
					
					@foreach($excelheaderValueArray as $key=>$excelheaderValue)
						@if($key == 0)
						<tr>
							@foreach($excelheaderValue as $subkey=>$headerVal)
								<td><strong>{{ $total_inc_tax_key == $subkey ?   '$' . number_format($total_inc_tax) : ($total_IRMAA_key == $subkey ?  '$' . number_format($total_IRMAA) : ($total_irs_partner_key == $subkey ?  '$' . number_format($total_irs_partner) : ($total_estate_key == $subkey ?  '$' . number_format($total_estate) : ($total_wife_rmd_inc_key == $subkey ?  '$' . number_format($total_wife_rmd_inc) : ($total_husband_rmd_inc_key == $subkey ?  '$' . number_format($total_husband_rmd_inc) : ($total_joint_rmd_inc_key == $subkey ?  '$' . number_format($total_joint_rmd_inc) : '') ) ) ) )) }}
								
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
							<td>
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
		
	</div>
	
	{{--<div class="row">
			<div style="margin-left:450px;"><strong>Total RMD: {{ '$ '. number_format($total_rmd_value + $total_wife_rmd_inc + $total_husband_rmd_inc + $total_joint_rmd_inc)}}</strong></div>
	</div>--}}
	
	@if(!empty($current_finance_husband_data))
	<div style="page-break-after: always;">
		<div>
			<h2><strong style="margin-left:200px;">Husband Roth Conversion From Taxable To Free Tax</strong></h2>
		</div>
		</hr>
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
		@endphp
		
		
		<table class="calc-report">
			<thead>
				<tr>
					<th>Roth Conversion</br>${{ number_format($husband_account_value) ?? ''}}</br>21% Bonus</th>
					<th></th>
					<th>70</br>Yr 1</th>
					<th>71</br>Yr 2</th>
					<th>72</br>Yr 3</th>
					<th>73</br>Yr 4</th>
					<th>74</br>Yr 5</th>
					<th>75</br>Yr 6</th>
					<th>76</br>Yr 7</th>
					<th>77</br>Yr 8</th>
					<th>Annual Converted</th>
					<th>Year End Roth Value</th>
					<th>Year End Account Value</th>
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
									<td style="height:10px;text-align: left">
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
										     if($index17_previous == 0 && $index19_previous == 0){										$index12_previous = $a17;
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
												@endif
												
												@if($col == 7)
												
											       $ {{ number_format(distribution_period()[74][1]) }}
												
												@endif
												
												@if($col == 8)
												
													$ {{ number_format(distribution_period()[75][1]) }}
												
												@endif
												
												@if($col == 9)
												
													$ {{ number_format(distribution_period()[76][1]) }}
													
													@php 
													   $h_19 = distribution_period()[76][1];
													@endphp
												
												@endif
										@endif
										
										@if($row==1 && $col==9)
										$ {{ number_format(round($index17_previous - distribution_period()[76][1])) }}
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
		
		<table class="footer">
			<tr>
				<td style="padding: 10px;text-align: left;font-size: 12px;">
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

