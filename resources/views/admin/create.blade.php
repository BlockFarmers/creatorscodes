@extends('admin.layouts.admin')

@section('title', 'Nouveau code createur')

@section('content')
    <form method="POST" action="{{ route('creatorcodes.admin.store') }}">
        @csrf
        @include('creatorcodes::admin._form')
    </form>
@endsection
