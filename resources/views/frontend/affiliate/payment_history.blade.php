@extends('frontend.layouts.user_panel')

@section('panel_content')

    <div class="card modern-card shadow-none rounded-0 border-0 mb-4">
        <div class="card-header pt-4 border-bottom-0 pb-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Payment History') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Date') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        <th>{{ translate('Method') }}</th>
                        <th>{{ translate('Details') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($affiliate_payments as $key => $payment)
                        <tr>
                            <td>{{ $key + 1 + ($affiliate_payments->currentPage() - 1) * $affiliate_payments->perPage() }}</td>
                            <td>{{ $payment->created_at }}</td>
                            <td>{{ single_price($payment->amount) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td>{{ $payment->payment_details }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            <div class="aiz-pagination">
                {{ $affiliate_payments->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
