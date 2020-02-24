<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Order;
use App\Order_detail;
use App\Customer;
use App\User;
use Carbon\Carbon;
use App\Exports\OrderInvoice;
use Cookie;
use DB;
use PDF;

class OrderController extends Controller
{
    public function index(Request $request){
        $customers  = Customer::orderBy('name', 'ASC')->get();
        $users      = User::role('kasir')->orderBy('name', 'ASC')->get();
        $orders     = Order::orderBy('created_at', 'DESC')->with('order_detail', 'customer');

        if(!empty($request->customer_id)){
            $orders = $orders->where('customer_id', $request->customer_id);
        }

        if(!empty($request->user_id)){
            $orders = $orders->where('user_id', $request->user_id);
        }

        if(!empty($request->start_date) && !empty($request->end_date)){
            \Validator::make($request->all(), [
                'start_date'    => 'nullable|date',
                'end_date'      => 'nullable|date'
            ]);

            // RE-FORMAT MENJADI Y-m-d H:i:s
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d'). ' 00:00:01';
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d'). ' 23:59:59';

            $orders = $orders->whereBetween('created_at', [$start_date, $end_date])->get();
        }else{
            $orders =  $orders->take(10)->skip(0)->get();
        }
    
        return  view('orders.index', [
            'orders'            => $orders,
            'sold'              => $this->countItem($orders),
            'total'             => $this->countTotal($orders),
            'total_customer'    => $this->countCustomer($orders),
            'customers'         => $customers,
            'users'             => $users
        ]);
    }
    
    private function countItem($orders){
        $data = 0;
        if($orders->count() > 0 ){
            foreach($orders as $order){
                $qty = $order->order_detail->pluck('qty')->all();
                $val = array_sum($qty);
                $data += $val;
            }
        }

        return $data;
    }

    private function countTotal($orders){
        $total = 0;

        if($orders->count() > 0){
            //MENGAMBIL VALUE DARI TOTAL -> PLUCK() AKAN MENGUBAHNYA MENJADI ARRAY
            $sub_total = $orders->pluck('total')->all();

            $total = array_sum($sub_total);
        }
        return $total;
    }

    private function countCustomer($orders){
        $customer = [];
        if($orders->count() > 0 ){
            foreach($orders as $order){
                $customer[] = $order->customer->email;
            }
        }
        return count(array_unique($customer));
        // return $order->distinct('customer.email')->count('customer.email');
    }

    public function invoicePdf($invoice){
        $order = Order::where('invoice', $invoice)->with('customer', 'order_detail.product')->first();
        $pdf = PDF::setOptions(['dpi'=>150, 'defaultFont'=>'sans-serif'])->loadView('orders.report.invoice', compact('order'));
        return $pdf->stream();
    }

    public function invoiceExcel($invoice){
        return (new OrderInvoice($invoice))->download('invoice-'. $invoice . '.xlsx');
    }

    public function addOrder(){
        $products = Product::orderBy('name', 'asc')->get();
        return view('orders.add', compact('products'));
    }

    public function getProduct($id){
        $products = Product::findOrFail($id);
        return response()->json($products, 200);
    }

    public function addToCart(Request $request){
        //validasi data yang diterima
        //dari ajax request addToCart mengirimkan product_id dan qty
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer'
        ]);

    
        //mengambil data product berdasarkan id
        $product = Product::findOrFail($request->product_id);
        //mengambil cookie cart dengan $request->cookie('cart')
        $getCart = json_decode($request->cookie('cart'), true);

        //jika datanya ada
        if ($getCart) {
            //jika key nya exists berdasarkan product_id
            if (array_key_exists($request->product_id, $getCart)) {
                //jumlahkan qty barangnya
                $getCart[$request->product_id]['qty'] += $request->qty;
                //dikirim kembali untuk disimpan ke cookie
                return response()->json($getCart, 200)
                    ->cookie('cart', json_encode($getCart), 120);
            } 
        }

    
        //jika cart kosong, maka tambahkan cart baru
        $getCart[$request->product_id] = [
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => $request->qty
        ];
        //kirim responsenya kemudian simpan ke cookie
        return response()->json($getCart, 200)
            ->cookie('cart', json_encode($getCart), 120);
    }
    
    public function getCart(){
        //mengambil cart dari cookie
        $cart = json_decode(request()->cookie('cart'), true);
        //mengirimkan kembali dalam bentuk json untuk ditampilkan dengan vuejs
        return response()->json($cart, 200);
    }
    
    public function removeCart($id){
        $cart = json_decode(request()->cookie('cart'), true);
        //menghapus cart berdasarkan product_id
        unset($cart[$id]);
        //cart diperbaharui
        return response()->json($cart, 200)->cookie('cart', json_encode($cart), 120);
    }

    public function checkout(){
        return view('orders.checkout');
    }

    public function storeOrder(Request $request){
        \Validator::make($request->all(),[
            '*.email'     => 'required|email',
            '*.name'      => 'required|string|max:100',
            '*.phone'     => 'required|numeric',
            '*.address'   => 'required'
        ])->validate();
        
        //mengambil list cart dari cookie
        $cart = json_decode($request->cookie('cart'), true);
        
        //memanipulasi array untuk menciptakan key baru yakni result dari hasil perkalian price * qty
        $result = collect($cart)->map(function($value){
            return [
                'code'      => $value['code'],
                'name'      => $value['name'],
                'qty'       => $value['qty'],
                'price'     => $value['price'],
                'result'    => $value['price'] * $value['qty']
            ];
        })->all();


        // database transaction
        DB::beginTransaction();
        try {
            //menyimpan data ke table customers
            $customer = Customer::firstOrCreate([
                'email'     => $request[0]['email']
            ],[
                'name'      => $request[0]['name'],
                'phone'     => $request[0]['phone'],
                'address'   => $request[0]['address']
            ]);

            // menyimpan data ke table order
            $order = Order::create([
                'invoice'       => $this->generateInvoice(),
                'customer_id'   => $customer->id,
                'user_id'       => \Auth::user()->id,
                'total'         => array_sum(array_column($result, 'result'))
            ]);

            //looping cart untuk disimpan ke table order_details
            foreach($result as $key=>$row){
                $order->order_detail()->create([
                    'product_id'    => $key,
                    'qty'           => $row['qty'],
                    'price'         => $row['price']
                ]);
            }

            //apabila tidak terjadi error, penyimpanan diverifikasi
            DB::commit();

            //me-return status dan message berupa code invoice, dan menghapus cookie
            return response()->json([
                'status'    => 'success',
                'message'   => $order->invoice,
            ], 200)->cookie(Cookie::forget('cart'));
        } catch(\Exception $e){
            //jika ada error, maka akan dirollback sehingga tidak ada data yang tersimpan 
            DB::rollback();

            //pesan gagal akan di-return
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function generateInvoice(){
        //mengambil data dari table orders
        $order = Order::orderBy('created_at', 'DESC');
        //jika sudah terdapat records
        if($order->count() > 0){
            $order = $order->first();
            //explode invoice untuk mendapatkan angkanya
            $explode = explode('-', $order->invoice);
            //angka dari hasil explode di +1
            return 'INV-'.($explode[1] + 1);
        }else{
            //jika belum terdapat records maka akan me-return INV-1
            return 'INV-1';
        }
    }


}
