@extends('admin.layouts.admin')

@section('title', trans('creatorscodes::admin.commissions.title'))

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->has('paypal'))
        <div class="alert alert-danger">{{ $errors->first('paypal') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">{{ trans('creatorscodes::admin.commissions.pending_total') }}</div>
                    <div class="h4 mb-0">{{ number_format($totalPending, 2) }} €</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">{{ trans('creatorscodes::admin.commissions.paid_total') }}</div>
                    <div class="h4 mb-0">{{ number_format($totalPaid, 2) }} €</div>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>{{ trans('creatorscodes::admin.commissions.columns.date') }}</th>
                <th>{{ trans('creatorscodes::admin.commissions.columns.order') }}</th>
                <th>{{ trans('creatorscodes::admin.commissions.columns.creator') }}</th>
                <th>{{ trans('creatorscodes::admin.commissions.columns.code') }}</th>
                <th>{{ trans('creatorscodes::admin.commissions.columns.order_amount') }}</th>
                <th>{{ trans('creatorscodes::admin.commissions.columns.commission') }}</th>
                <th>{{ trans('creatorscodes::admin.commissions.columns.status') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($commissions as $commission)
                <tr>
                    <td>{{ $commission->created_at->format('d/m/Y H:i') }}</td>
                    <td>#{{ $commission->order_id }}</td>
                    <td>{{ $commission->creatorCode->creator->name ?? '—' }}</td>
                    <td>{{ $commission->creatorCode->code ?? '—' }}</td>
                    <td>{{ number_format($commission->order_amount, 2) }} €</td>
                    <td>{{ number_format($commission->commission_amount, 2) }} €</td>
                    <td>
                        @if ($commission->paid_out)
                            <span class="badge bg-success">{{ trans('creatorscodes::admin.commissions.status.paid') }}</span>
                            @if ($commission->paypal_batch_id)
                                <div class="small text-muted">{{ trans('creatorscodes::admin.commissions.status.paypal', ['status' => $commission->paypal_status]) }}</div>
                            @endif
                        @else
                            <span class="badge bg-warning text-dark">{{ trans('creatorscodes::admin.commissions.status.pending') }}</span>
                            @if ($commission->paypal_error)
                                <div class="small text-danger">{{ trans('creatorscodes::admin.commissions.status.paypal_failed') }}</div>
                            @endif
                        @endif
                    </td>
                    <td class="text-end">
                        @unless ($commission->paid_out)
                            @if ($commission->creatorCode && $commission->creatorCode->paypal_email)
                                <form method="POST" action="{{ route('creatorscodes.admin.commissions.paypal-payout', $commission) }}" class="d-inline"
                                      onsubmit="return confirm(@js(trans('creatorscodes::admin.commissions.pay_paypal_confirm', [
                                          'amount' => number_format($commission->commission_amount, 2),
                                          'currency' => $commission->currency,
                                          'email' => $commission->creatorCode->paypal_email,
                                      ])));">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        {{ trans('creatorscodes::admin.commissions.pay_paypal') }}
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('creatorscodes.admin.commissions.mark-paid', $commission) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    {{ trans('creatorscodes::admin.commissions.mark_paid') }}
                                </button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">{{ trans('creatorscodes::admin.commissions.empty') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $commissions->links() }}
@endsection
