@php 
use Carbon\Carbon;
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

		<table>
			<tr>
				<!-- Left Side -->
				<td width="50%" style="border-right: 1px solid #3490CD;">
					<h1 style="color: #3490CD;">Building Rewarding Income Goals</h1>
					<h2>Income Allocation Tool</h2>
				</td>

				<!-- Right Side -->
				<td width="50%" style="padding-left: 20px;">
					<p><strong style="color: #3490CD;">Prepared For:</strong><br>
					Sample Husband and Wife</p>

					<p><strong style="color: #3490CD;">Agent/Representative:</strong><br>
					Darryl Stein</p>

					<p><strong style="color: #3490CD;">Date Prepared:</strong>
					{{ Carbon::parse($created_at)->format('d/m/Y') }}</p>
				</td>
			</tr>
		</table>

		<table>
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
					<td width="30%" style="text-align: right;">$ {{ $growth_allocation ?? ''}}</td>
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
				{{--<tr>
					<td width="70%">Primary Goals: {{$primary1 ?? ''}}, Legacy, Tax Free Estate Plan</td>
					<td width="30%" style="text-align: right;"></td>
				</tr>--}}
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
					#1 Variable Annuity &nbsp;&nbsp; $2,377,000 <br>
					#2 401k T-IRA &nbsp;&nbsp; $156,000 <br><br>
					<span class="subtotal">Subtotal $2,533,000</span>
				</td>

				<!-- Husband's Accounts -->
				<td width="50%" valign="top">
					<strong>Husband's Accounts</strong><br>
					#1 Variable Annuity &nbsp;&nbsp; $803,952 <br><br>
					<span class="subtotal">Subtotal $803,952</span>
				</td>
			</tr>
		</table>

		<!-- Joint Accounts -->
		<table>
			<tr>
				<td width="100%" valign="top">
					<strong>Joint Accounts</strong><br>
					#1 Variable Annuity &nbsp;&nbsp; $440,400 <br>
					#2 Savings &nbsp;&nbsp; $76,400 <br><br>
					<span class="subtotal">Subtotal $516,800</span>
				</td>
			</tr>
		</table>

		<!-- Totals -->
		<table>
			<tr>
				<td width="70%"></td>
				<td class="totals right">Asset Total $3,853,752</td>
			</tr>
			<tr>
				<td></td>
				<td class="totals right">Income Total $61,536</td>
			</tr>
		</table>

		<!-- Current Income Accounts -->
		<table>
			<tr>
				<td width="100%" valign="top">
					<strong>Current Income Accounts</strong><br>
					Wife SS &nbsp;&nbsp; $35,772 <br>
					Husband SS &nbsp;&nbsp; $25,764 <br><br>
					<span class="subtotal">Subtotal $61,536</span>
				</td>
			</tr>
		</table>

		<br><br>
		<p>
			Fidelity Mutual Financial: Advisor Darryl Stein <br>
			267-280-3660 <br>
			www.TheFidelityMutual.com
		</p>
	</div>
</body>
</html>
