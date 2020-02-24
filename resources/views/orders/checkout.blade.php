@extends('layouts.master')

@section('page-title', 'Checkout')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
  <div class="row" id="dw">
    <div class="col-8">
      @component('components.card')
        @slot('header')
          Detail Customer
        @endslot
        @slot('body')
          
          <!-- JIKA VALUE DARI message ada, maka alert success akan ditampilkan -->
          <div v-if="message" class="alert alert-success">
            Transaction has been save, Invoice: <strong>@{{ message }}</strong>
          </div>

          <div class="form-group">
            <label for="email" class="font-weight-bold">Email</label>
            <input type="email" name="email" class="form-control" v-model="customer.email" v-on:keyup.enter.prevent="searchCustomer" required>
            <span>Press enter for check email.</span> <small class="text-danger font-italic">@{{ messageEmail }}</small>
          </div>

          <!-- JIKA formCustomer BERNILAI TRUE, MAKA FORM AKAN DITAMPILKAN -->
          <div v-if="formCustomer">
            <div class="form-group">
              <label for="email" class="font-weight-bold">Customer Name</label>
              <input type="text" name="name" class="form-control" v-model="customer.name" :disabled="resultStatus" required>
            </div>
            
            <div class="form-group">
              <label for="address">Address</label>
              <textarea name="description" cols="5" rows="5" class="form-control" v-model="customer.address" :disabled="resultStatus"></textarea>
            </div>

            <div class="form-group">
              <label for="email" class="font-weight-bold">No Telp</label>
              <input type="text" name="phone" class="form-control" v-model="customer.phone" :disabled="resultStatus" required>
            </div>
          </div>

          
              
        @endslot
              
        @slot('footer')
          <div class="card-footer text-muted">
            <!-- JIKA VALUE DARI errorMessage ada, maka alert danger akan ditampilkan -->
            <div v-if="errorMessage" class="alert alert-danger">
                @{{ errorMessage }}
            </div>
            <!-- JIKA TOMBOL DITEKAN MAKA AKAN MEMANGGIL METHOD sendOrder -->
            <button class="btn btn-primary btn-sm float-right" :disabled="submitForm" @click.prevent="sendOrder">
                @{{ submitForm ? 'Loading...':'Order Now' }}
            </button>
          </div>
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