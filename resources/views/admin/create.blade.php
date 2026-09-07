@extends('admin.layouts.admin')

@section('title', 'New creator code')

@section('content')
    <form method="POST" action="{{ route('creatorcodes.admin.store') }}">
        @csrf
        @include('creatorcodes::admin._form')
    </form>
@endsection
