@extends('frontend.layouts.user_panel')

@section('panel_content')

    <!-- Dashboard Summary Cards -->
    <div class="card shadow-sm mb-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0 fs-18 fw-600 text-dark">{{ translate('Affiliate Dashboard') }}</h5>
        </div>
        <div class="card-body">
            <div class="row gutters-16">
                <!-- Available Balance -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card text-white bg-success text-center py-4 mb-0 shadow-sm border-0">
                        <div class="fs-22 fw-700">{{ single_price($wallet->available_balance) }}</div>
                        <div class="fs-12 text-uppercase opacity-80">{{ translate('Available Balance') }}</div>
                    </div>
                </div>
                <!-- Pending Balance -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card text-white bg-info text-center py-4 mb-0 shadow-sm border-0">
                        <div class="fs-22 fw-700">{{ single_price($wallet->pending_balance) }}</div>
                        <div class="fs-12 text-uppercase opacity-80">{{ translate('Pending Balance') }}</div>
                    </div>
                </div>
                <!-- Total Earned -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card text-white bg-primary text-center py-4 mb-0 shadow-sm border-0">
                        <div class="fs-22 fw-700">{{ single_price($wallet->total_earned) }}</div>
                        <div class="fs-12 text-uppercase opacity-80">{{ translate('Total Earned') }}</div>
                    </div>
                </div>
                <!-- Total Withdrawn -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card text-white bg-secondary text-center py-4 mb-0 shadow-sm border-0">
                        <div class="fs-22 fw-700">{{ single_price($wallet->total_withdrawn) }}</div>
                        <div class="fs-12 text-uppercase opacity-80">{{ translate('Total Withdrawn') }}</div>
                    </div>
                </div>
                <!-- Total Referrals -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card text-dark bg-light text-center py-4 mb-0 shadow-sm border">
                        <div class="fs-22 fw-700 text-primary">{{ $total_referrals }}</div>
                        <div class="fs-12 text-uppercase text-muted">{{ translate('Total Referrals') }}</div>
                    </div>
                </div>
                <!-- Total Orders -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card text-dark bg-light text-center py-4 mb-0 shadow-sm border">
                        <div class="fs-22 fw-700 text-info">{{ $total_orders }}</div>
                        <div class="fs-12 text-uppercase text-muted">{{ translate('Total Orders') }}</div>
                    </div>
                </div>
                <!-- Clicks -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card text-dark bg-light text-center py-4 mb-0 shadow-sm border">
                        <div class="fs-22 fw-700 text-warning">{{ $affiliate_stats ? $affiliate_stats->no_of_click : 0 }}</div>
                        <div class="fs-12 text-uppercase text-muted">{{ translate('Clicks Count') }}</div>
                    </div>
                </div>
                <!-- Items Sold -->
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card text-dark bg-light text-center py-4 mb-0 shadow-sm border">
                        <div class="fs-22 fw-700 text-success">{{ $affiliate_stats ? $affiliate_stats->no_of_item_sold : 0 }}</div>
                        <div class="fs-12 text-uppercase text-muted">{{ translate('Items Sold') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Link and QR Code -->
    <div class="row gutters-16">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header border-bottom">
                    <h5 class="mb-0 fs-16 fw-600 text-dark">{{ translate('Referral Management') }}</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <p class="text-muted fs-13 mb-3">{{ translate('Share this referral link with your friends and audience to earn commissions on purchases!') }}</p>
                    <div class="form-group row align-items-center">
                        <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Referral Link') }}</label>
                        <div class="col-md-9">
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
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-header border-bottom text-left">
                    <h5 class="mb-0 fs-16 fw-600 text-dark">{{ translate('Your QR Code') }}</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <div id="qrcode_container" class="p-2 border bg-white rounded shadow-sm">
                        <div id="qrcode"></div>
                    </div>
                    <div class="fs-12 text-muted mt-2">{{ translate('Scan QR Code to visit') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission History Logs & Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="mb-0 fs-18 fw-600 text-dark">{{ translate('Affiliate Commission History') }}</h5>
            
            <!-- Status Filter -->
            <form action="" method="GET" class="mb-0" id="filter_form">
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0 fs-13 text-muted min-w-fit-content">{{ translate('Filter by Status') }}:</label>
                    <select name="status" class="form-control form-control-sm rounded" onchange="document.getElementById('filter_form').submit()">
                        <option value="">{{ translate('All Statuses') }}</option>
                        <option value="pending" @if(request('status') == 'pending') selected @endif>{{ translate('Pending') }}</option>
                        <option value="approved" @if(request('status') == 'approved') selected @endif>{{ translate('Approved') }}</option>
                        <option value="rejected" @if(request('status') == 'rejected') selected @endif>{{ translate('Rejected') }}</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Order Code') }}</th>
                            <th>{{ translate('Customer Name') }}</th>
                            <th>{{ translate('Rate / Value') }}</th>
                            <th>{{ translate('Commission Amount') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($affiliate_logs as $key => $log)
                            <tr>
                                <td>{{ $key + 1 + ($affiliate_logs->currentPage() - 1) * $affiliate_logs->perPage() }}</td>
                                <td>
                                    @if ($log->order != null)
                                        <span class="fw-600 text-dark">{{ $log->order->code }}</span>
                                    @else
                                        <span class="text-muted">{{ translate('N/A') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->customer != null)
                                        {{ $log->customer->name }}
                                    @else
                                        <span class="text-muted">{{ translate('N/A') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($log->commission_value)
                                        {{ $log->commission_type == 'percent' ? $log->commission_value . '%' : single_price($log->commission_value) }}
                                    @else
                                        <span class="text-muted">{{ translate('N/A') }}</span>
                                    @endif
                                </td>
                                <td class="fw-600 text-success">{{ single_price($log->referral_amount) }}</td>
                                <td>
                                    @if ($log->status == 'approved')
                                        <span class="badge badge-inline badge-success px-2 py-1">{{ translate('Approved') }}</span>
                                    @elseif ($log->status == 'rejected')
                                        <span class="badge badge-inline badge-danger px-2 py-1">{{ translate('Rejected') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-info px-2 py-1">{{ translate('Pending') }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">{{ translate('No commission logs found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="aiz-pagination mt-3">
                {{ $affiliate_logs->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- QR Code Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script type="text/javascript">
        function copyReferralLink() {
            var copyText = document.getElementById('referral_link');
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand('copy');
            AIZ.plugins.notify('success', '{{ translate('Referral link copied to clipboard') }}');
        }

        // Render QR Code on page load
        window.addEventListener('DOMContentLoaded', (event) => {
            var referralLink = document.getElementById('referral_link').value;
            if (referralLink) {
                new QRCode(document.getElementById("qrcode"), {
                    text: referralLink,
                    width: 120,
                    height: 120,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }
        });
    </script>
@endsection
