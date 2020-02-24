@extends('layouts/master')

@section('page-header', 'Overview Users')

@section('content')
  <div class="row">
    <div class="col-sm-12">
      @component('components.card')
        @slot('header')
          Data Users
        @endslot
        @slot('headerButton')
          <a href="{{route('users.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary"><i class="fas fa-plus fa-sm"></i> Add</a>
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
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th width="130px">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $index => $user)
              <tr>
                <td>{{$index + 1}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                  @foreach ($user->getRoleNames() as $role)
                    <label class="badge badge-info">{{ $role }}</label>
                  @endforeach  
                </td>
                <td>
                  <a href="{{ route('users.roles', $user->id) }}" class="btn btn-info btn-sm"><i class="fa fa-user-secret"></i></a>
                  <a href="{{route('users.edit', $user->id)}}" class="btn btn-warning btn-sm"> <i class="fa fa-edit"></i></a>
                  <form action="{{route('users.destroy', $user->id)}}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i></button>
                  </form>
                </td>
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
@endsection