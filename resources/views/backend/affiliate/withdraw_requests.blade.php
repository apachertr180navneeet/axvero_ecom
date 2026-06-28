@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Affiliate Withdraw Requests') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('User') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th class="text-right">{{ translate('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($affiliate_withdraw_requests as $key => $withdraw_request)
                        <tr>
                            <td>{{ $key + 1 + ($affiliate_withdraw_requests->currentPage() - 1) * $affiliate_withdraw_requests->perPage() }}</td>
                            <td>
                                @if ($withdraw_request->user != null)
                                    {{ $withdraw_request->user->name }}
                                    <br>
                                    <span class="text-muted small">{{ $withdraw_request->user->email }}</span>
                                @else
                                    {{ translate('User not found') }}
                                @endif
                            </td>
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
                            <td class="text-right">
                                @if ($withdraw_request->status == 0)
                                    <a href="javascript:void(0);"
                                        onclick="show_affiliate_withdraw_modal('{{ $withdraw_request->id }}');"
                                        class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                        title="{{ translate('Pay Now') }}">
                                        <i class="las la-money-bill"></i>
                                    </a>
                                    <a href="{{ route('affiliate.withdraw_request.reject', $withdraw_request->id) }}"
                                        class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                        title="{{ translate('Reject') }}">
                                        <i class="las la-times"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $affiliate_withdraw_requests->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
    <div class="modal fade" id="affiliate_withdraw_modal">
        <div class="modal-dialog">
            <div class="modal-content" id="affiliate-withdraw-modal-content"></div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_affiliate_withdraw_modal(id) {
            $.post('{{ route('affiliate_withdraw_modal') }}', {
                _token: '{{ @csrf_token() }}',
                id: id
            }, function(data) {
                $('#affiliate-withdraw-modal-content').html(data);
                $('#affiliate_withdraw_modal').modal('show', { backdrop: 'static' });
            });
        }
    </script>
@endsection
