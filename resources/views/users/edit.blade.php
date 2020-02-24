@extends('layouts/master')

@section('page-header', 'Update Data Users')

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
          <form role="form" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="col-10" >
              @csrf
              @method('PUT')
              <div class="form-group">
                  <label class="font-weight-bold">Name</label>
                <input type="text" name="name" value="{{$user->name}}" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" required>
                  <small class="text-danger">{{ $errors->first('name') }}</small>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Email</label>
                <input type="text" name="email" value="{{$user->email}}" class="form-control {{ $errors->has('email') ? 'is-invalid':'' }}" required>
                <small class="text-danger">{{ $errors->first('email') }}</small>
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