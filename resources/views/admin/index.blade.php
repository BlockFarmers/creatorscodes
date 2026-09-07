@extends('admin.layouts.admin')

@section('title', 'Creators codes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('creatorcodes.admin.commissions') }}" class="btn btn-outline-secondary">
                View commissions
            </a>
            <a href="{{ route('creatorcodes.admin.create') }}" class="btn btn-primary">
                New code
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
                <th>Creator</th>
                <th>Commission</th>
                <th>Active</th>
                <th>Assigned orders</th>
                <th>Total generates</th>
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
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $code->commissions_count }}</td>
                    <td>{{ number_format($code->totalCommission(), 2) }} €</td>
                    <td class="text-end">
                        <a href="{{ route('creatorcodes.admin.edit', $code) }}" class="btn btn-sm btn-outline-primary">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('creatorcodes.admin.destroy', $code) }}" class="d-inline"
                              onsubmit="return confirm('Delete code ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No creator code for the moment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
