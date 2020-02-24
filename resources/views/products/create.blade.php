@extends('layouts/master')

@section('page-header', 'Create Data Product')

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
          <form role="form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="col-10" >
              @csrf
              <div class="form-group">
                  <label class="font-weight-bold">Product Code</label>
                  <input type="text" name="code" class="form-control {{ $errors->has('code') ? 'is-invalid':'' }}" maxlength="10" required>
                  <small class="text-danger">{{ $errors->first('code') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Product Name</label>
                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" required>
                <small class="text-danger">{{ $errors->first('name') }}</small>
              </div>
              <div class="form-group">
                  <label for="description" class="font-weight-bold">Description</label>
                  <textarea name="description" id="description" cols="5" rows="5" class="form-control {{ $errors->has('description') ? 'is-invalid':'' }}"></textarea>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Stock</label>
                <input type="number" name="stok" class="form-control {{ $errors->has('stock') ? 'is-invalid':'' }}" required>
                <small class="text-danger">{{ $errors->first('stock') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Price</label>
                <input type="number" name="price" class="form-control {{ $errors->has('price') ? 'is-invalid':'' }}" required>
                <small class="text-danger">{{ $errors->first('price') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Categories</label>
                <select name="category_id" id="category_id" required class="form-control {{ $errors->has('category_id') ? 'is-invalid':'' }}">
                    <option value="">--Select--</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                    @endforeach
                </select>
                <small class="text-danger">{{ $errors->first('category_id') }}</small>
              </div>
              <div class="form-group">
                <label for="">Foto</label>
                <input type="file" name="photo" class="form-control">
                <p class="text-danger">{{ $errors->first('photo') }}</p>
              </div>
              
        @endslot
              
        @slot('footer')
            <button type="submit" class="btn btn-primary">Simpan</button>
          </form>
        @endslot
          
      @endcomponent
    </div>
  </div>
@endsection