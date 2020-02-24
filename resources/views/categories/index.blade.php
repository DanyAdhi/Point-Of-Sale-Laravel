@extends('layouts/master')

@section('page-header', 'Overview Categories')

@section('content')
  <div class="row">
    <div class="col-4">
      @component('components.card')
        @slot('header')
          Add Data
        @endslot
        @slot('body')
          @if (session('error'))
              @component('components.alert', ['type' => 'danger'])
                  {!! session('error') !!}
              @endcomponent
          @endif
          <form role="form" action="{{ route('categories.store') }}" method="POST">
              @csrf
              <div class="form-group">
                  <label for="name" class="font-weight-bold">Kategori</label>
                  <input type="text" 
                  name="name"
                  class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" id="name" required>
              </div>
              <div class="form-group">
                  <label for="description">Deskripsi</label>
                  <textarea name="description" id="description" cols="5" rows="5" class="form-control {{ $errors->has('description') ? 'is-invalid':'' }}"></textarea>
              </div>
              
              @endslot
              
        @slot('footer')
            <button type="submit" class="btn btn-primary">Simpan</button>
          </form>
        @endslot
          
      @endcomponent
    </div>


    <div class="col-xl-8">
      @component('components.card')
        @slot('header')
          Data Category
        @endslot
        @slot('body')
            @if (session('success'))
                @component('components.alert', ['type' => 'success'])
                    {!! session('success') !!}
                @endcomponent
            @endif
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th width="20px">No</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th width="98px">Edit</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($categories as $index => $category)
                <tr>
                  <td>{{$index + 1}}</td>
                  <td>{{$category->name}}</td>
                  <td >{{$category->description}}</td>
                  <td>
                    <div>
                      <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                      <button class="btn btn-danger btn-sm" onclick="deleteConfirm('{{$category->id}}', '{{$category->name}}')" data-target="#modalDelete" data-toggle="modal"><i class="fa fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">No Data</td>
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
                <h5 class="modal-title d-inline">Delete Category</h5>
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
        var url = '{{ route("categories.destroy", ":id") }}';    
            url = url.replace(':id', id);
        document.getElementById("url").setAttribute("action", url);
        document.getElementById('message').innerHTML ="Are you sure want to delete category <b>"+name+"</b> and delete all product with this category ?"
        $('#modalDelete').modal();
    }
  </script>
@endsection