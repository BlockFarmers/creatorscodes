@extends('admin.layouts.admin')

@section('title', trans('creatorscodes::admin.codes.edit'))

@section('content')
    <form method="POST" action="{{ route('creatorscodes.admin.update', $creatorCode) }}">
        @csrf
        @method('PUT')
        @include('creatorscodes::admin._form')
    </form>
@endsection
