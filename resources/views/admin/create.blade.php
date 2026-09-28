@extends('admin.layouts.admin')

@section('title', 'New creator code')

@section('content')
    <form method="POST" action="{{ route('creatorscodes.admin.store') }}">
        @csrf
        @include('creatorscodes::admin._form')
    </form>
@endsection
