@extends('admin.layouts.admin')

@section('title', 'Creator commissions')

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
                    <div class="text-muted">Amount due</div>
                    <div class="h4 mb-0">{{ number_format($totalPending, 2) }} €</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Already paid</div>
                    <div class="h4 mb-0">{{ number_format($totalPaid, 2) }} €</div>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Order</th>
                <th>Creator</th>
                <th>Code</th>
                <th>Order amount</th>
                <th>Commission</th>
                <th>Status</th>
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
                            <span class="badge bg-success">Paid</span>
                            @if ($commission->paypal_batch_id)
                                <div class="small text-muted">PayPal : {{ $commission->paypal_status }}</div>
                            @endif
                        @else
                            <span class="badge bg-warning text-dark">On hold</span>
                            @if ($commission->paypal_error)
                                <div class="small text-danger">Previous PayPal failure</div>
                            @endif
                        @endif
                    </td>
                    <td class="text-end">
                        @unless ($commission->paid_out)
                            @if ($commission->creatorCode && $commission->creatorCode->paypal_email)
                                <form method="POST" action="{{ route('creatorcodes.admin.commissions.paypal-payout', $commission) }}" class="d-inline"
                                      onsubmit="return confirm('Send {{ number_format($commission->commission_amount, 2) }} {{ $commission->currency }} via PayPal to {{ $commission->creatorCode->paypal_email }} ? This action is real.');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Pay via PayPal
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('creatorcodes.admin.commissions.mark-paid', $commission) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    Mark as paid
                                </button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No commission for the moment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $commissions->links() }}
@endsection
