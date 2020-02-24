<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function search(Request $request){
        \Validator::make($request->all(), [
            'email' => 'required|email'
        ])->validate();

        $customer = Customer::where('email', $request->email)->get();
        if($customer->count() > 0){
            return response()->json([
                'status'    => 'success',
                'data'      => $customer
            ], 200);
        }elseif($customer->count() == 0){
            return response()->json([
                'status'    => 'emtpy',
                'data'      => $customer
            ], 200);
        }else{
            return response()->json([
                'status'    => 'fail',
                'data'      => $customer
            ],200);
        }
    }
}
