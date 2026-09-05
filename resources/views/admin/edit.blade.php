@extends('admin.layouts.admin')

@section('title', 'Modifier le code createur')

@section('content')
    <form method="POST" action="{{ route('creatorcodes.admin.update', $creatorCode) }}">
        @csrf
        @method('PUT')
        @include('creatorcodes::admin._form')
    </form>
@endsection
