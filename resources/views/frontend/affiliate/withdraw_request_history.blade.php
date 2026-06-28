@extends('frontend.layouts.user_panel')

@section('panel_content')

    <div class="card modern-card shadow-none rounded-0 border-0 mb-4">
        <div class="card-header pt-4 border-bottom-0 pb-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Withdraw Request History') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Amount') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th>{{ translate('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($affiliate_withdraw_requests as $key => $withdraw_request)
                        <tr>
                            <td>{{ $key + 1 + ($affiliate_withdraw_requests->currentPage() - 1) * $affiliate_withdraw_requests->perPage() }}</td>
                            <td>{{ single_price($withdraw_request->amount) }}</td>
                            <td>
                                @if ($withdraw_request->status == 1)
                                    <span class="badge modern-badge position-static border-0 badge-success px-2 py-1">{{ translate('Paid') }}</span>
                                @elseif ($withdraw_request->status == 2)
                                    <span class="badge modern-badge position-static border-0 badge-danger px-2 py-1">{{ translate('Rejected') }}</span>
                                @else
                                    <span class="badge modern-badge position-static border-0 badge-info px-2 py-1">{{ translate('Pending') }}</span>
                                @endif
                            </td>
                            <td>{{ $withdraw_request->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            <div class="aiz-pagination">
                {{ $affiliate_withdraw_requests->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <div class="card modern-card shadow-none rounded-0 border-0 mb-4">
        <div class="card-header pt-4 border-bottom-0 pb-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('New Withdraw Request') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('affiliate.withdraw_request.store') }}" method="POST">
                @csrf
                <div class="form-group row align-items-center">
                    <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Amount') }}</label>
                    <div class="col-md-9">
                        <input type="number" lang="en" class="modern-input" name="amount"
                            placeholder="{{ translate('Enter amount') }}" min="0" step="0.01" required>
                    </div>
                </div>
                <div class="form-group mb-0 text-right mt-4">
                    <button type="submit" class="btn modern-btn modern-btn-primary">{{ translate('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
