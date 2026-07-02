@extends('frontend.layouts.user_panel')

@section('panel_content')

    <div class="card shadow-sm mb-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0 fs-18 fw-600 text-dark">{{ translate('Referred Customers') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Customer Name') }}</th>
                            <th>{{ translate('Registration Date') }}</th>
                            <th>{{ translate('Total Orders') }}</th>
                            <th>{{ translate('Total Purchases') }}</th>
                            <th>{{ translate('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($referred_customers as $key => $referred)
                            <tr>
                                <td>{{ $key + 1 + ($referred_customers->currentPage() - 1) * $referred_customers->perPage() }}</td>
                                <td>
                                    @if ($referred->customer)
                                        <span class="fw-600 text-dark">{{ $referred->customer->name }}</span>
                                    @else
                                        <span class="text-muted">{{ translate('N/A') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($referred->customer)
                                        {{ $referred->customer->created_at->format('Y-m-d') }}
                                    @else
                                        <span class="text-muted">{{ translate('N/A') }}</span>
                                    @endif
                                </td>
                                <td class="fw-600">{{ $referred->total_orders }}</td>
                                <td class="fw-600 text-success">{{ single_price($referred->total_spent) }}</td>
                                <td>
                                    @if ($referred->customer && $referred->customer->banned == 0)
                                        <span class="badge badge-inline badge-success px-2 py-1">{{ translate('Active') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger px-2 py-1">{{ translate('Banned/Inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ translate('No referred customers found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="aiz-pagination mt-3">
                {{ $referred_customers->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
