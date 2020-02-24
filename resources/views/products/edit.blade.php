@extends('layouts/master')

@section('page-header', 'Edit Data Product')

@section('content')
  <div class="row">
    <div class="col-12">
      @component('components.card')
        
        @slot('body')
          @if (session('error'))
              @component('components.alert', ['type' => 'danger'])
                  {!! session('error') !!}
              @endcomponent
          @endif
          <form role="form" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="col-10" >
              @csrf
              @method('PUT')
              <div class="form-group">
                  <label class="font-weight-bold">Product Code</label>
                  <input type="text" name="code" value="{{$product->code}}" class="form-control {{ $errors->has('code') ? 'is-invalid':'' }}" maxlength="10" readonly required>
                  <small class="text-danger">{{ $errors->first('code') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Product Name</label>
                <input type="text" name="name" value="{{$product->name}}" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" required>
                <small class="text-danger">{{ $errors->first('name') }}</small>
              </div>
              <div class="form-group">
                  <label for="description" class="font-weight-bold">Description</label>
                  <textarea name="description" id="description" cols="5" rows="5" class="form-control {{ $errors->has('description') ? 'is-invalid':'' }}">{{$product->description}}</textarea>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Stock</label>
                <input type="number" name="stok" value="{{$product->stok}}" class="form-control {{ $errors->has('stok') ? 'is-invalid':'' }}" required>
                <small class="text-danger">{{ $errors->first('stok') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Price</label>
                <input type="number" name="price" value="{{$product->price}}" class="form-control {{ $errors->has('price') ? 'is-invalid':'' }}" required>
                <small class="text-danger">{{ $errors->first('price') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Categories</label>
                <select name="category_id" id="category_id" required class="form-control {{ $errors->has('category_id') ? 'is-invalid':'' }}">
                    <option value="">--Select--</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{$category->id == $product->category_id ? 'Selected':''}}>{{ ucfirst($category->name) }}</option>
                    @endforeach
                </select>
                <small class="text-danger">{{ $errors->first('category_id') }}</small>
              </div>
              <div class="form-group">
                <label for="">Foto</label>
                <input type="file" name="photo" class="form-control">
                <small class="text-danger">{{ $errors->first('photo') }}</p>
                @if (!empty($product->photo))
                    <hr>
                    <img src="{{ asset('admin/img/products/' . $product->photo) }}" 
                        alt="{{ $product->name }}"
                        width="150px" height="150px">
                @endif
              </div>
              
        @endslot
              
        @slot('footer')
            <button type="submit" class="btn btn-primary">Update</button>
          </form>
        @endslot
          
      @endcomponent
    </div>
  </div>
@endsection