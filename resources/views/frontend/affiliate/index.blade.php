@extends('frontend.layouts.user_panel')

@section('panel_content')

    <div class="card shadow-sm mb-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0 fs-18 fw-600 text-dark">{{ translate('Affiliate Dashboard') }}</h5>
        </div>
        <div class="card-body">
            <div class="row gutters-16">
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-grad-1 text-center py-4 mb-0">
                        <div class="fs-24 fw-700">{{ $affiliate_stats ? $affiliate_stats->no_of_click : 0 }}</div>
                        <div class="fs-14">{{ translate('No of Click') }}</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-grad-2 text-center py-4 mb-0">
                        <div class="fs-24 fw-700">{{ $affiliate_stats ? $affiliate_stats->no_of_item_sold : 0 }}</div>
                        <div class="fs-14">{{ translate('No of Item Sold') }}</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-grad-3 text-center py-4 mb-0">
                        <div class="fs-24 fw-700">{{ $affiliate_stats ? $affiliate_stats->no_of_delivered : 0 }}</div>
                        <div class="fs-14">{{ translate('No of Delivered') }}</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-grad-4 text-center py-4 mb-0">
                        <div class="fs-24 fw-700">{{ single_price($affiliate_stats ? $affiliate_stats->total_amount : 0) }}</div>
                        <div class="fs-14">{{ translate('Total Amount') }}</div>
                    </div>
                </div>
            </div>

            <div class="form-group row align-items-center mt-3">
                <label class="col-md-2 col-form-label fs-14 fw-600">{{ translate('Referral Link') }}</label>
                <div class="col-md-10">
                    <div class="input-group d-flex align-items-center gap-2">
                        <input type="text" class="form-control flex-grow-1 m-0 rounded" id="referral_link"
                            value="{{ url('/') }}?referral_code={{ auth()->user()->referral_code }}" readonly>
                        <div class="input-group-append ms-2">
                            <button type="button" class="btn btn-primary m-0 rounded"
                                onclick="copyReferralLink()">{{ translate('Copy') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0 fs-18 fw-600 text-dark">{{ translate('Recent Affiliate Logs') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
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
            </div>
            <div class="aiz-pagination">
                {{ $affiliate_logs->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function copyReferralLink() {
            var copyText = document.getElementById('referral_link');
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand('copy');
            AIZ.plugins.notify('success', '{{ translate('Referral link copied to clipboard') }}');
        }
    </script>
@endsection
