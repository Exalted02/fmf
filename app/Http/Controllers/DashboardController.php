<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
}
