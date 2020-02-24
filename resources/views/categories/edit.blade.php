@extends('layouts/master')

@section('page-header', 'Overview Categories')

@section('content')
  <div class="row">
    <div class="col-md-12">
      @component('components.card')
        @slot('header')
          Edit Category
        @endslot
        @slot('body')
            @if (session('success'))
                @component('components.alert', ['type' => 'success'])
                    {!! session('success') !!}
                @endcomponent
            @endif

            <form role="form" action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Kategori</label>
                    <input type="text" 
                        name="name"
                        value="{{ $category->name }}"
                        class="form-control col-sm-6 {{ $errors->has('name') ? 'is-invalid':'' }}" id="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" cols="5" rows="5" class="form-control col-sm-6 {{ $errors->has('description') ? 'is-invalid':'' }}">{{ $category->description }}</textarea>
                </div>

        @endslot
        @slot('footer')
                <button class="btn btn-info">Update</button>
            </form>
        @endslot
      @endcomponent
    </div>
  </div>
@endsection