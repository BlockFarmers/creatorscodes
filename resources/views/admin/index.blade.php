@extends('admin.layouts.admin')

@section('title', trans('creatorscodes::admin.codes.title'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('creatorscodes.admin.commissions') }}" class="btn btn-outline-secondary">
                {{ trans('creatorscodes::admin.codes.view_commissions') }}
            </a>
            <a href="{{ route('creatorscodes.admin.create') }}" class="btn btn-primary">
                {{ trans('creatorscodes::admin.codes.new') }}
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>{{ trans('creatorscodes::admin.codes.columns.code') }}</th>
                <th>{{ trans('creatorscodes::admin.codes.columns.creator') }}</th>
                <th>{{ trans('creatorscodes::admin.codes.columns.commission') }}</th>
                <th>{{ trans('creatorscodes::admin.codes.columns.active') }}</th>
                <th>{{ trans('creatorscodes::admin.codes.columns.orders') }}</th>
                <th>{{ trans('creatorscodes::admin.codes.columns.total') }}</th>
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
                            <span class="badge bg-success">{{ trans('creatorscodes::admin.codes.status.active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ trans('creatorscodes::admin.codes.status.inactive') }}</span>
                        @endif
                    </td>
                    <td>{{ $code->commissions_count }}</td>
                    <td>{{ number_format($code->totalCommission(), 2) }} €</td>
                    <td class="text-end">
                        <a href="{{ route('creatorscodes.admin.edit', $code) }}" class="btn btn-sm btn-outline-primary">
                            {{ trans('creatorscodes::admin.actions.edit') }}
                        </a>
                        <form method="POST" action="{{ route('creatorscodes.admin.destroy', $code) }}" class="d-inline"
                              onsubmit="return confirm(@js(trans('creatorscodes::admin.codes.delete_confirm')));">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">{{ trans('creatorscodes::admin.actions.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">{{ trans('creatorscodes::admin.codes.empty') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
