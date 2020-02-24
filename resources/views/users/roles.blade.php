@extends('layouts.master')

@section('page-header', 'Overview Users')

@section('content')
  <div class="row">
    <div class="col-6">
      @component('components.card')
        @slot('header')
          
        @endslot
        @slot('body')
          @if (session('error'))
              @component('components.alert', ['type' => 'danger'])
                  {!! session('error') !!}
              @endcomponent
          @endif
          <form role="form" action="{{ route('users.add_permission') }}" method="POST">
              @csrf
              @method('PUT')
              <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>:</td>
                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>:</td>
                            <td>
                                @foreach ($roles as $row)
                                <input type="radio" name="role" 
                                    {{ $user->hasRole($row) ? 'checked':'' }}
                                    value="{{ $row }}"> {{  $row }} <br>
                                @endforeach
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        @endslot
              
        @slot('footer')
            <button type="submit" class="btn btn-primary">Set Role</button>
          </form>
        @endslot
          
      @endcomponent
    </div>
  </div>
@endsection