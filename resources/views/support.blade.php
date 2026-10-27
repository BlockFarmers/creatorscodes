@extends('layouts.app')

@section('title', trans('creatorscodes::messages.support.title'))

@section('content')
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($support && $support->creatorCode)
            <div class="card mb-4">
                <div class="card-body">
                    <p class="mb-2">
                        {!! trans('creatorscodes::messages.support.current', [
                            'creator' => '<strong>'.e($support->creatorCode->creator->name ?? trans('creatorscodes::messages.support.this_creator')).'</strong>',
                            'code' => '<strong>'.e($support->creatorCode->code).'</strong>',
                        ]) !!}
                    </p>
                    <form method="POST" action="{{ route('creatorscodes.support.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            {{ trans('creatorscodes::messages.support.withdraw') }}
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h2 class="h5 mb-3">{{ trans('creatorscodes::messages.support.enter_code') }}</h2>
                <form method="POST" action="{{ route('creatorscodes.support.update') }}" class="row g-2">
                    @csrf
                    <div class="col-auto">
                        <input type="text" name="code" class="form-control" placeholder="{{ trans('creatorscodes::messages.support.placeholder') }}" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">{{ trans('creatorscodes::messages.support.confirm') }}</button>
                    </div>
                </form>
                <p class="text-muted mt-2 mb-0">
                    {{ trans('creatorscodes::messages.support.info') }}
                </p>
            </div>
        </div>
    </div>
@endsection
