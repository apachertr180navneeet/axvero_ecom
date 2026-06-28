@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Affiliate Logs') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('User') }}</th>
                        <th>{{ translate('Order') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        <th>{{ translate('Type') }}</th>
                        <th>{{ translate('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($affiliate_logs as $key => $log)
                        <tr>
                            <td>{{ $key + 1 + ($affiliate_logs->currentPage() - 1) * $affiliate_logs->perPage() }}</td>
                            <td>
                                @if ($log->user != null)
                                    {{ $log->user->name }}
                                @else
                                    {{ translate('User not found') }}
                                @endif
                            </td>
                            <td>
                                @if ($log->order != null)
                                    {{ $log->order->code }}
                                @else
                                    {{ translate('N/A') }}
                                @endif
                            </td>
                            <td>{{ single_price($log->referral_amount) }}</td>
                            <td>
                                @if ($log->log_type == 1)
                                    <span class="badge badge-inline badge-info">{{ translate('Click') }}</span>
                                @else
                                    <span class="badge badge-inline badge-success">{{ translate('Sale') }}</span>
                                @endif
                            </td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $affiliate_logs->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
