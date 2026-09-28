@extends('admin.layouts.admin')

@section('title', 'Change Creator Code')

@section('content')
    <form method="POST" action="{{ route('creatorscodes.admin.update', $creatorCode) }}">
        @csrf
        @method('PUT')
        @include('creatorscodes::admin._form')
    </form>
@endsection
