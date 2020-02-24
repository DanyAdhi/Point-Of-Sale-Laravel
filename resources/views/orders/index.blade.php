@extends('layouts.master')

@section('page-title', 'Management Order')

@section('css')
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
  <div class="row" id="dw">
    <div class="col-12">
      @component('components.card')
        @slot('header')
          Filter Transaction
        @endslot
        @slot('body')
          <form action="{{ route('orders.index') }}" method="GET">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="">Start Date</label>
                  <input type="text" name="start_date" class="form-control {{ $errors->has('start_date') ? 'is-invalid':'' }}" id="start_date" value="{{ request()->get('start_date') }}" >
                </div>
                <div class="form-group">
                  <label for="">End Date</label>
                  <input type="text" name="end_date"  class="form-control {{ $errors->has('end_date') ? 'is-invalid':'' }}" id="end_date" value="{{ request()->get('end_date') }}">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-sm">Cari</button>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="">Pelanggan</label>
                  <select name="customer_id" class="form-control">
                      <option value="">Pilih</option>
                      @foreach ($customers as $cust)
                        <option value="{{ $cust->id }}"
                            {{ request()->get('customer_id') == $cust->id ? 'selected':'' }}>
                            {{ $cust->name }} - {{ $cust->email }}
                        </option>
                      @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Kasir</label>
                  <select name="user_id" class="form-control">
                      <option value="">Pilih</option>
                      @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request()->get('user_id') == $user->id ? 'selected':'' }}> 
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>
          </form>
        @endslot
        @slot('footer')
          
        @endslot
      @endcomponent
    </div>
    <div class="col-12">
      @component('components.card')
        @slot('header')
          Data Transaction
        @endslot
        @slot('body')
          <div class="row">
            <div class="col-sm-4 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Item Terjual</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{$sold}}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Omset</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($total) }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Customers</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_customer }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fa fa-user fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="table table-responsive">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Telp</th>
                    <th>Total</th>
                    <th>Cashier</th>
                    <th>Transaction Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($orders as $order)
                    <tr>
                      <td><strong>#{{$order->invoice}}</strong></td>
                      <td>{{$order->customer->name}}</td>
                      <td>{{$order->customer->phone}}</td>
                      <td>Rp {{number_format($order->total)}}</td>
                      <td>{{$order->user->name}}</td>
                      <td>{{$order->created_at->format('d-m-Y H:i:s')}}</td>
                      <td>
                        <a href="{{ route('orders.pdf', $order->invoice) }}" target="_blank" class="btn btn-primary btn-sm">
                          <i class="fa fa-print"></i>
                        </a>
                        <a href="{{ route('orders.excel', $order->invoice) }}" target="_blank" class="btn btn-info btn-sm">
                            {{-- <i class="fa fa-file-excel-o"></i> --}}
                            <i class="far fa-file-excel"></i>
                        </a>
                      </td>
                    </tr>   
                  @empty
                      <tr>
                        <td class="text-center" colspan="7">No Data</td>
                      </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

          </div>
        @endslot
        @slot('footer')
          
        @endslot
      @endcomponent
    </div>
  </div>
@endsection


@section('js')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#start_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            showAnim: "fold",
        });

        $('#end_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            showAnim: "fold",
        });
    </script>
@endsection