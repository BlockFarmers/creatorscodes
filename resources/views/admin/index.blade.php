{{-- A VERIFIER : remplace 'admin.layouts.admin' par le layout admin reel --}}
{{-- (ouvre une vue admin existante, ex. plugins/shop/resources/views/admin/*.blade.php, --}}
{{-- et copie sa ligne @extends). --}}
@extends('admin.layouts.admin')

@section('title', 'Codes createur')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Codes createur</h1>
        <div>
            <a href="{{ route('creatorcodes.admin.commissions') }}" class="btn btn-outline-secondary">
                Voir les commissions
            </a>
            <a href="{{ route('creatorcodes.admin.create') }}" class="btn btn-primary">
                Nouveau code
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Code</th>
                <th>Createur</th>
                <th>Commission</th>
                <th>Actif</th>
                <th>Commandes attribuees</th>
                <th>Total genere</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($codes as $code)
                <tr>
                    <td><strong>{{ $code->code }}</strong></td>
                    <td>{{ $code->creator->name ?? '—' }}</td>
                    <td>{{ number_format($code->commission_rate, 2) }} %</td>
                    <td>
                        @if ($code->active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </td>
                    <td>{{ $code->commissions_count }}</td>
                    <td>{{ number_format($code->totalCommission(), 2) }} €</td>
                    <td class="text-end">
                        <a href="{{ route('creatorcodes.admin.edit', $code) }}" class="btn btn-sm btn-outline-primary">
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('creatorcodes.admin.destroy', $code) }}" class="d-inline"
                              onsubmit="return confirm('Supprimer ce code ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Aucun code createur pour le moment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
