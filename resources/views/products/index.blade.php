@extends('layouts/master')

@section('page-header', 'Overview Products')

@section('content')
  <div class="row">
    <div class="col-sm-12">
      @component('components.card')
        @slot('header')
          Data Products
        @endslot
        @slot('headerButton')
          @if (auth()->user()->can('Create Product'))
            <a href="{{route('products.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary"><i class="fas fa-plus fa-sm"></i> Add</a>
          @endif
        @endslot

        @slot('body')
        @if (session('success'))
            @component('components.alert', ['type' => 'success'])
                {!! session('success') !!}
            @endcomponent
        @endif
        <table class="table table-bordered" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th width="20px">No</th>
              <th>Photo</th>
              <th>Product Name</th>
              <th>Stock</th>
              <th>Price</th>
              <th>Category</th>
              <th width="170px">Update</th>
              @if (auth()->user()->can('Edit Product') || auth()->user()->can('Delete Product'))
                <th width="95px">Action</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @forelse ($products as $index => $product)
              <tr>
                <td>{{$index + 1}}</td>
                <td>
                  @if (!empty($product->photo))
                    <img src="{{ asset('admin/img/products/' . $product->photo) }}" alt="{{ $product->name }}" width="50px" height="50px">
                  @else
                    <img src="http://via.placeholder.com/50x50" alt="{{ $product->name }}">
                  @endif
                </td>
                <td>{{$product->name}}</td>
                <td>{{$product->stok}}</td>
                <td>{{$product->price}}</td>
                <td>{{$product->category->name}}</td>
                <td>{{$product->updated_at}}</td>
                @if (auth()->user()->can('Edit Product') || auth()->user()->can('Delete Product'))
                  <td>
                    @if (auth()->user()->can('Edit Product'))
                      <a href="{{route('products.edit', $product->id)}}" class="btn btn-warning btn-sm"> <i class="fa fa-edit"></i></a>
                    @endif
                    @if (auth()->user()->can('Delete Product'))
                      <button type="submit" class="btn btn-danger btn-sm" onclick="deleteConfirm('{{$product->id}}', '{{$product->name}}')" data-target="modalDelete" data-togle="modal"> <i class="fa fa-trash"></i></button>
                    @endif
                  </td>
                @endif
              </tr>  
            @empty
              <tr>
                <td colspan="7" class="text-center">No Data</td>
              </tr>
                
            @endforelse
          </tbody>
        </table>

        @endslot
      @endcomponent
    </div>
  </div>

  <!-- Modal Delete -->
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-inline">Delete Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="message"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <form action="" id="url" method="POST" class="d-inline">
                  @csrf 
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Delete</button>
              </form>
            </div>
        </div>
      </div>
    </div>
  <!-- End Modal Delete -->
@endsection

@section('js')
  <script>
    function deleteConfirm(id, name){ 
        var url = '{{ route("products.destroy", ":id") }}';    
            url = url.replace(':id', id);
        document.getElementById("url").setAttribute("action", url);
        document.getElementById('message').innerHTML ="Are you sure want to delete product <b>"+name+"</b> ?"
        $('#modalDelete').modal();
    }
  </script>
@endsection