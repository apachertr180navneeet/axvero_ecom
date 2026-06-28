@extends('frontend.layouts.user_panel')

@section('panel_content')

    <div class="card shadow-none rounded-0 border">
        <div class="card-header pt-4 border-bottom-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Withdraw Request History') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
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
                                    <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                @elseif ($withdraw_request->status == 2)
                                    <span class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                @else
                                    <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                @endif
                            </td>
                            <td>{{ $withdraw_request->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $affiliate_withdraw_requests->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <div class="card shadow-none rounded-0 border">
        <div class="card-header pt-4 border-bottom-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('New Withdraw Request') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('affiliate.withdraw_request.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-md-3 col-form-label fs-14">{{ translate('Amount') }}</label>
                    <div class="col-md-9">
                        <input type="number" lang="en" class="form-control rounded-0" name="amount"
                            placeholder="{{ translate('Enter amount') }}" min="0" step="0.01" required>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary rounded-0">{{ translate('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
