@php 
use Carbon\Carbon;
$headerCount = count($excelheaderArray)-1;
//echo "<pre>";print_r($excelheaderArray);die;
//echo "<pre>";print_r($excelheaderValueArray);die;
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

$total_inc_tax = 0;
$total_IRMAA = 0;
$total_irs_partner = 0;
$total_estate = 0;
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
        .totals { font-weight: bold; font-size: 14px; }
        .right { text-align: right; }
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

		<table style="margin-top: 200px;">
			<tr>
				<td style="background-color: #3490CD;padding: 10px;text-align: center;color: #fff;font-size: 12px;">
					Information and interactive calculators are made available as self-help tools for independent use. Simplicity Group does not guarantee their applicability to any individual circumstances. The Simplicity Group encourages you to seek personalized guidance from qualified professionals regarding all personal finance issues. This analysis is based solely on the information you provide. The results presented by this calculator are hypothetical and for illustrative purposes, and do not represent current or future performance of any specific financial product. No guarantees are made as to the accuracy of any projection. All financial products carry a degree of risk, and past performance is not a guarantee of future results. Generally, the greater the return, the greater the risk.<br>

					This calculator does not reflect any possible taxes. It also does not reflect fees, expenses and charges that may be associated with a financial product holding the savings.
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
		<table>
			<tr>
				<td width="70%"></td>
				<td class="totals right">Asset Total ${{ number_format($subTotalWife + $subTotalHusband + $subTotalJoint) }}</td>
			</tr>
			<tr>
				<td></td>
				<td class="totals right">Income Total $ {{ number_format($incomeTotal) }}</td>
			</tr>
		</table>

		<!-- Current Income Accounts -->
		<table>
			<tr>
				<td width="100%" valign="top">
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

		<br><br>
		<p>
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>
	</div>
	
	<div style="page-break-after: always;">
		<table class="calc-report">
			<thead>
			<tr>
			    @if(!empty($excelheaderArray))
					@foreach($excelheaderArray as $h=>$header)
					<th>{{ $header ?? '' }}</th>
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
					
					@if($header == 'Irs Partner')
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
					@if(($key >=0 && $key < 6) || ($key >15 && $key < 18) || ($key >20 && $key <= 25))
						<tr>
						@foreach($excelheaderValue as $k=>$headerVal)
						<td>{{ $headerVal ?? '' }}</td>
						@endforeach
						</tr>
						@endif
					@endforeach
					<tr><td>&nbsp;</td></tr>
					
					@foreach($excelheaderValueArray as $key=>$excelheaderValue)
						@if($key == 0)
						<tr>
							@foreach($excelheaderValue as $subkey=>$headerVal)
								<td><strong>{{ $total_inc_tax_key == $subkey ? number_format($total_inc_tax) : ($total_IRMAA_key == $subkey ? number_format($total_IRMAA) : ($total_irs_partner_key == $subkey ? number_format($total_irs_partner) : ($total_estate_key == $subkey ? number_format($total_estate) : '') )) }}</strong></td>
							@endforeach
						</tr>
						@endif
					@endforeach
					
				@endif
			</tbody>
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

