@extends('layouts.app')

@section('title', 'Support a creator')

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
                        You are currently supporting
                        <strong>{{ $support->creatorCode->creator->name ?? 'this creator' }}</strong>
                        with the code <strong>{{ $support->creatorCode->code }}</strong>.
                    </p>
                    <form method="POST" action="{{ route('creatorcodes.support.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Withdraw my support
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h2 class="h5 mb-3">Enter a creator code</h2>
                <form method="POST" action="{{ route('creatorcodes.support.update') }}" class="row g-2">
                    @csrf
                    <div class="col-auto">
                        <input type="text" name="code" class="form-control" placeholder="Ex: GUIGUI10" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
                <p class="text-muted mt-2 mb-0">
                    Your future purchases in the shop will generate a commission for this creator, at no extra cost to you.
                </p>
            </div>
        </div>
    </div>
@endsection
