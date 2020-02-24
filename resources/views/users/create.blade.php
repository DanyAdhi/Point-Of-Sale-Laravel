@extends('layouts/master')

@section('page-header', 'Create Data Users')

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
          <form role="form" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="col-10" >
              @csrf
              <div class="form-group">
                  <label class="font-weight-bold">Name</label>
                  <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" required>
                  <small class="text-danger">{{ $errors->first('name') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Email</label>
                <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid':'' }}" required>
                <small class="text-danger">{{ $errors->first('email') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Roles</label>
                <select name="role" id="role" required class="form-control {{ $errors->has('role') ? 'is-invalid':'' }}">
                    <option value="">--Select--</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                <small class="text-danger">{{ $errors->first('role') }}</small>
              </div>              
        @endslot
              
        @slot('footer')
            <button type="submit" class="btn btn-primary">Save</button>
          </form>
        @endslot
          
      @endcomponent
    </div>
  </div>
@endsection