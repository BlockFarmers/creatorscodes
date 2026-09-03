@extends('admin.layouts.admin')

@section('title', 'Nouveau code createur')

@section('content')
    <h1 class="h3 mb-3">Nouveau code createur</h1>

    <form method="POST" action="{{ route('creatorcodes.admin.store') }}">
        @csrf
        @include('creatorcodes::admin._form')
    </form>
@endsection
