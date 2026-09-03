{{-- A VERIFIER : remplace 'layouts.app' par le layout reellement utilise --}}
{{-- par les autres pages du site (regarde le debut d'une vue du theme actif). --}}
@extends('layouts.app')

@section('title', 'Soutenir un createur')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Soutenir un createur</h1>

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
                        Tu soutiens actuellement
                        <strong>{{ $support->creatorCode->creator->name ?? 'ce createur' }}</strong>
                        avec le code <strong>{{ $support->creatorCode->code }}</strong>.
                    </p>
                    <form method="POST" action="{{ route('creatorcodes.support.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Retirer mon soutien
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h2 class="h5 mb-3">Entrer un code createur</h2>
                <form method="POST" action="{{ route('creatorcodes.support.update') }}" class="row g-2">
                    @csrf
                    <div class="col-auto">
                        <input type="text" name="code" class="form-control" placeholder="Ex: GUIGUI10" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
                <p class="text-muted mt-2 mb-0">
                    Tes futurs achats en boutique verseront une commission a ce createur, sans surcout pour toi.
                </p>
            </div>
        </div>
    </div>
@endsection
