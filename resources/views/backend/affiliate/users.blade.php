@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Affiliate Users') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('User') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th class="text-right">{{ translate('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($affiliate_users as $key => $affiliate_user)
                        <tr>
                            <td>{{ $key + 1 + ($affiliate_users->currentPage() - 1) * $affiliate_users->perPage() }}</td>
                            <td>
                                @if ($affiliate_user->user != null)
                                    {{ $affiliate_user->user->name }}
                                    <br>
                                    <span class="text-muted small">{{ $affiliate_user->user->email }}</span>
                                @else
                                    {{ translate('User not found') }}
                                @endif
                            </td>
                            <td>
                                @if ($affiliate_user->status == 1)
                                    <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                @else
                                    <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($affiliate_user->status == 0)
                                    <a href="{{ route('affiliate_user.approve', $affiliate_user->id) }}"
                                        class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                        title="{{ translate('Approve') }}">
                                        <i class="las la-check"></i>
                                    </a>
                                    <a href="{{ route('affiliate_user.reject', $affiliate_user->id) }}"
                                        class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                        title="{{ translate('Reject') }}">
                                        <i class="las la-times"></i>
                                    </a>
                                @endif
                                <a href="{{ route('affiliate_users.show_verification_request', $affiliate_user->id) }}"
                                    class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                    title="{{ translate('Verification') }}">
                                    <i class="las la-id-card"></i>
                                </a>
                                <a href="{{ route('affiliate_user.payment_history', $affiliate_user->id) }}"
                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    title="{{ translate('Payment History') }}">
                                    <i class="las la-history"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $affiliate_users->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
