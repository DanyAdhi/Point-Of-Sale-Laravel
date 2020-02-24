@extends('layouts/master')

@section('page-header', 'Management Role')

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
          <form role="form" action="{{ route('roles.store') }}" method="POST">
              @csrf
              <div class="form-group">
                  <label for="role" class="font-weight-bold">Role</label>
                  <input type="text" 
                  name="name"
                  class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" id="name" required>
              </div>
        @endslot
              
        @slot('footer')
            <button type="submit" class="btn btn-primary">Save</button>
          </form>
        @endslot
          
      @endcomponent
    </div>

    <div class="col-xl-8">
      @component('components.card')
        @slot('header')
          Data Roles
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
                  <th>Role</th>
                  <th>Guard</th>
                  <th>Create At</th>
                  <th width="98px">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($roles as $index => $role)
                <tr>
                  <td>{{$index + 1}}</td>
                  <td>{{$role->name}}</td>
                  <td>{{$role->guard_name}}</td>
                  <td>{{$role->created_at}}</td>
                  <td>
                    <div>
                      <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                      </form>
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
@endsection