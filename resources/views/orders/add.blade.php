@extends('layouts.master')

@section('page-title', 'Transaction')

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
  <div class="row" id="dw">
    <div class="col-8">
      @component('components.card')
        @slot('header') @endslot

        @slot('body')
        <div class="row">
          <div class="col-4">
            <form action="#" @submit.prevent="addToCart" method="POST">
              <div class="form-group">
                  <label class="font-weight-bold">Product</label>
                  {{-- <input type="number" v-model="cart.product_id" name="product_id" id="product_id" /> --}}
                  <select name="product_id" id="product_id"
                    v-model="cart.product_id"
                    class="select-custom" width="100%">
                      {{-- <option value="">--Select--</option> --}}
                      @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                      @endforeach
                  </select>
              </div>
            
              <div class="form-group">
                <label for="qty">Qty</label>
                <input type="number" name="qty" 
                v-model="cart.qty"
                id="qty" value="1" 
                min="1" class="form-control">
              </div>
              <button class="btn btn-primary" :disabled="submitCart"><i class="fa fa-shopping-cart"></i> @{{ submitCart ? 'Loading...':'Add Cart' }} </button>
            </form>
          </div>
          <div class="col-5">
            <h4>Detail Produk</h4>
            <div v-if="product.name">
                <table class="table table-stripped">
                    <tr>
                        <th>Kode</th>
                        <td>:</td>
                        <td>@{{ product.code }}</td>
                    </tr>
                    <tr>
                        <th width="3%">Produk</th>
                        <td width="2%">:</td>
                        <td>@{{ product.name }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>:</td>
                        <td>@{{ product.price | currency }}</td>
                    </tr>
                </table>
            </div>
          </div>
          {{-- show image --}}
          <div class="col-md-3" v-if="product.photo">
            <img :src="'/admin/img/products/' + product.photo" 
                height="150px" 
                width="150px" 
                :alt="product.name">
          </div>
        </div>
              
        @endslot
              
        @slot('footer')
        @endslot
      @endcomponent
    </div>

    @include('orders.cart')
  </div>
@endsection

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
  <script src="{{ asset('js/transaksi.js') }}"></script>
@endsection