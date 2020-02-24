@extends('layouts/master')

@section('page-header', 'Role Permission')

@section('content')
  <div class="row">
    <div class="col-4">
      @component('components.card')
        @slot('header')
          Add New Permission
        @endslot
        @slot('body')
          @if (session('error'))
              @component('components.alert', ['type' => 'danger'])
                  {!! session('error') !!}
              @endcomponent
          @endif
          <form role="form" action="{{ route('users.add_permission') }}" method="POST">
              @csrf
              <div class="form-group">
                  <label for="role" class="font-weight-bold">Role</label>
                  <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" id="name" required>
              </div>
        @endslot
              
        @slot('footer')
            <button type="submit" class="btn btn-primary">Add New</button>
          </form>
        @endslot
          
      @endcomponent
    </div>

    <div class="col-xl-8">
      @component('components.card')
        @slot('header')
          Set Permission to Role
        @endslot
        @slot('body')
            @if (session('success'))
                @component('components.alert', ['type' => 'success'])
                    {!! session('success') !!}
                @endcomponent
            @endif
            <form role="form" action="{{ route('users.roles_permission') }}" method="GET">
              <label for="role" class="font-weight-bold">Roles</label>
              <div class="form-group input-group">
                  <select name="role" class="form-control custom-select">
                      @foreach ($roles as $role)
                          <option value="{{ $role }}" {{ request()->get('role') == $role ? 'selected':'' }}>{{ $role }}</option>
                      @endforeach
                  </select>
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Check</button>
                  </div>
              </div>
            </form>

            @if (!empty($permissions))
              <form role="form" action="{{ route('users.setRolePermission', request()->get('role')) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="role" class="font-weight-bold">Role</label>
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs">
                          <li class="active">
                              <a href="#tab_1" data-toggle="tab">Permissions</a>
                          </li>
                      </ul>
                      <div class="tab-content">
                          <div class="tab-pane active" id="tab_1">
                              @php $no = 1; @endphp
                              @foreach ($permissions as $key => $row)
                                  <input type="checkbox" name="permission[]" value="{{ $row }}" class="minimal-red" 
                                      {{--  CHECK, JIKA PERMISSION TERSEBUT SUDAH DI SET, MAKA CHECKED --}}
                                      {{ in_array($row, $hasPermission) ? 'checked':'' }} > {{ $row }} <br>
                                  @if ($no++%4 == 0)
                                  <br>
                                  @endif
                              @endforeach
                          </div>
                      </div>
                      <div class="pull-right">
                        <button class="btn btn-primary btn-sm">
                            <i class="fa fa-send"></i> Set Permission
                        </button>
                      </div>
                </div>
                {{-- <button type="submit" class="btn btn-primary">Add New</button> --}}
              </form>
            @endif

        @endslot

        @slot('footer')
            
        @endslot

      @endcomponent
    </div>
  </div>
@endsection