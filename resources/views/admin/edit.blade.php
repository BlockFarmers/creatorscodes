@extends('admin.layouts.admin')

@section('title', 'Modifier le code createur')

@section('content')
    <h1 class="h3 mb-3">Modifier le code {{ $creatorCode->code }}</h1>

    <form method="POST" action="{{ route('creatorcodes.admin.update', $creatorCode) }}">
        @csrf
        @method('PUT')
        @include('creatorcodes::admin._form')
    </form>
@endsection
