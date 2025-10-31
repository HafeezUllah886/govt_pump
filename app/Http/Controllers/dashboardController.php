<?php

namespace App\Http\Controllers;

use App\Models\expenses;
use App\Models\export;
use App\Models\purchase;
use App\Models\OilPurchase;
use App\Models\OilProducts;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\ReceiveTT;
use App\Models\RouteExpenses;
use App\Models\sale_details;
use App\Models\Slaughtering;
use App\Models\stock;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? date('Y-m-d');
    
        return to_route('sale.index', compact('from', 'to'));
    }
}
