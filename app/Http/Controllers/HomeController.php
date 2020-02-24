<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Order;
use DB;

class HomeController extends Controller
{
    
    public function index()
    {
        return view('home');
    }

    public function getChart(){
        //MENGAMBIL TANGGAL 7 HARI YANG TELAH LALU DARI TANGGAL HARI INI
        $start  = Carbon::now()->subWeek()->addDay()->format('Y-m-d').'00:00:01';
        $end    = Carbon::now()->format('Y-m-d').'23:59:59';
        $order  = Order::select(DB::raw('date(created_at) as order_date'), DB::raw('count(*) as total_order'))
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('created_at')
        ->get()->pluck('total_order', 'order_date')->all();

        for ($i = Carbon::now()->subWeek()->addDay(); $i <= Carbon::now(); $i->addDay()) { 
            if (array_key_exists($i->format('Y-m-d'), $order)) {
                $data[$i->format('Y-m-d')] = $order[$i->format('Y-m-d')];
            }else{
                $data[$i->format('Y-m-d')] = 0;
            }
        }
        return response()->json($data);
    }


}
