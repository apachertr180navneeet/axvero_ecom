@extends('frontend.layouts.user_panel')

@section('panel_content')

    @php
        $min_withdrawal = \App\Models\AffiliateConfig::where('type', 'minimum_withdrawal_amount')->first()?->value;
        $max_withdrawal = \App\Models\AffiliateConfig::where('type', 'maximum_withdrawal_amount')->first()?->value;
        $frequency = \App\Models\AffiliateConfig::where('type', 'withdrawal_frequency')->first()?->value;
        $payment_settings = \App\Models\AffiliatePaymentSetting::where('user_id', auth()->user()->id)->first();
    @endphp

    <!-- Wallet Balance Header Cards -->
    <div class="row gutters-16 mb-4">
        <div class="col-md-6 col-sm-12 mb-3">
            <div class="card text-white bg-success text-center py-4 mb-0 border-0 shadow-sm">
                <div class="fs-24 fw-700">{{ single_price($wallet->available_balance) }}</div>
                <div class="fs-14 text-uppercase opacity-80">{{ translate('Available Balance') }}</div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 mb-3">
            <div class="card text-white bg-info text-center py-4 mb-0 border-0 shadow-sm">
                <div class="fs-24 fw-700">{{ single_price($wallet->pending_balance) }}</div>
                <div class="fs-14 text-uppercase opacity-80">{{ translate('Pending Balance') }}</div>
            </div>
        </div>
    </div>

    <!-- Payout request history table -->
    <div class="card modern-card shadow-sm border mb-4">
        <div class="card-header pt-4 border-bottom-0 pb-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Withdraw Request History') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Amount') }}</th>
                        <th>{{ translate('Method') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th>{{ translate('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($affiliate_withdraw_requests as $key => $withdraw_request)
                        <tr>
                            <td>{{ $key + 1 + ($affiliate_withdraw_requests->currentPage() - 1) * $affiliate_withdraw_requests->perPage() }}</td>
                            <td class="fw-600 text-dark">{{ single_price($withdraw_request->amount) }}</td>
                            <td>
                                @if ($withdraw_request->payment_method)
                                    <span class="text-capitalize">{{ str_replace('_', ' ', $withdraw_request->payment_method) }}</span>
                                @else
                                    <span class="text-muted">{{ translate('Default') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($withdraw_request->status == 1)
                                    <span class="badge badge-inline badge-success px-2 py-1">{{ translate('Paid') }}</span>
                                @elseif ($withdraw_request->status == 2)
                                    <span class="badge badge-inline badge-danger px-2 py-1">{{ translate('Rejected') }}</span>
                                @else
                                    <span class="badge badge-inline badge-info px-2 py-1">{{ translate('Pending') }}</span>
                                @endif
                            </td>
                            <td>{{ $withdraw_request->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">{{ translate('No withdrawal requests found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
            <div class="aiz-pagination mt-3">
                {{ $affiliate_withdraw_requests->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <!-- Create Withdraw Request Card -->
    <div class="card modern-card shadow-sm border mb-4">
        <div class="card-header pt-4 border-bottom-0 pb-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('New Withdraw Request') }}</h5>
        </div>
        <div class="card-body">
            <!-- Display constraints and alerts -->
            <div class="alert alert-info py-2 px-3 fs-13 mb-4" role="alert">
                <ul class="mb-0 pl-3">
                    @if ($min_withdrawal)
                        <li>{{ translate('Minimum withdrawal limit') }}: <span class="fw-600">{{ single_price($min_withdrawal) }}</span></li>
                    @endif
                    @if ($max_withdrawal)
                        <li>{{ translate('Maximum withdrawal limit') }}: <span class="fw-600">{{ single_price($max_withdrawal) }}</span></li>
                    @endif
                    @if ($frequency)
                        <li>{{ translate('Withdrawal request frequency') }}: <span class="fw-600">{{ translate('Once every') }} {{ $frequency }} {{ translate('days') }}</span></li>
                    @endif
                </ul>
            </div>

            <form action="{{ route('affiliate.withdraw_request.store') }}" method="POST">
                @csrf
                <!-- Amount -->
                <div class="form-group row align-items-center">
                    <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Amount') }} <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input type="number" lang="en" class="form-control" name="amount"
                            placeholder="{{ translate('Enter amount') }}" min="0" step="0.01" required
                            max="{{ $wallet->available_balance }}">
                        <span class="fs-12 text-muted">{{ translate('Available for withdraw') }}: <span class="fw-600 text-success">{{ single_price($wallet->available_balance) }}</span></span>
                    </div>
                </div>

                <!-- Payment Method selection -->
                <div class="form-group row align-items-center">
                    <label class="col-md-3 col-form-label fs-14 fw-600" for="payment_method">{{ translate('Payment Method') }} <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <select name="payment_method" id="payment_method" class="form-control" required onchange="togglePaymentFields(this)">
                            <option value="">{{ translate('Select Payment Method') }}</option>
                            <option value="bank_transfer">{{ translate('Bank Transfer') }}</option>
                            <option value="upi">{{ translate('UPI') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Bank Transfer Details Container -->
                <div class="form-group row d-none" id="bank_details_container">
                    <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Bank Account Details') }}</label>
                    <div class="col-md-9">
                        @if ($payment_settings && $payment_settings->bank_name)
                            <div class="p-3 bg-light border rounded">
                                <p class="mb-1"><strong>{{ translate('Bank Name') }}:</strong> {{ $payment_settings->bank_name }}</p>
                                <p class="mb-1"><strong>{{ translate('Account Name') }}:</strong> {{ $payment_settings->bank_acc_name }}</p>
                                <p class="mb-1"><strong>{{ translate('Account Number') }}:</strong> {{ $payment_settings->bank_acc_no }}</p>
                                <p class="mb-1"><strong>{{ translate('IBAN') }}:</strong> {{ $payment_settings->bank_iban ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>{{ translate('Routing No') }}:</strong> {{ $payment_settings->bank_routing_no ?? 'N/A' }}</p>
                            </div>
                            <input type="hidden" name="account_details" value="Bank Name: {{ $payment_settings->bank_name }} | Acc Name: {{ $payment_settings->bank_acc_name }} | Acc No: {{ $payment_settings->bank_acc_no }}">
                            <span class="fs-12 text-muted mt-1 d-block"><a href="{{ route('affiliate.payment_settings') }}" target="_blank">{{ translate('Edit payment settings') }}</a></span>
                        @else
                            <div class="alert alert-warning py-2 px-3 fs-13 mb-0">
                                {{ translate('Please configure your bank details in payment settings first.') }} 
                                <a class="alert-link ml-1" href="{{ route('affiliate.payment_settings') }}">{{ translate('Go to Payment Settings') }}</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- UPI Input Container -->
                <div class="form-group row d-none" id="upi_details_container">
                    <label class="col-md-3 col-form-label fs-14 fw-600" for="upi_id">{{ translate('UPI ID') }} <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="upi_id" id="upi_id"
                            placeholder="username@bankname">
                        <span class="fs-12 text-muted mt-1 d-block">{{ translate('Please enter a valid UPI ID where you want to receive payments.') }}</span>
                    </div>
                </div>

                <div class="form-group mb-0 text-right mt-4">
                    <button type="submit" class="btn btn-primary px-4">{{ translate('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function togglePaymentFields(select) {
            var val = select.value;
            var bankContainer = document.getElementById('bank_details_container');
            var upiContainer = document.getElementById('upi_details_container');
            var upiInput = document.getElementById('upi_id');

            // Reset containers
            bankContainer.classList.add('d-none');
            upiContainer.classList.add('d-none');
            upiInput.removeAttribute('required');

            if (val === 'bank_transfer') {
                bankContainer.classList.remove('d-none');
            } else if (val === 'upi') {
                upiContainer.classList.remove('d-none');
                upiInput.setAttribute('required', 'required');
            }
        }
        
        // Before submit, if UPI is selected, assign UPI ID to account_details
        document.querySelector('form').addEventListener('submit', function(e) {
            var method = document.getElementById('payment_method').value;
            if (method === 'upi') {
                var upiVal = document.getElementById('upi_id').value;
                // Add dynamically a hidden input for account_details with the UPI ID
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'account_details';
                hiddenInput.value = 'UPI ID: ' + upiVal;
                this.appendChild(hiddenInput);
            }
        });
    </script>
@endsection
