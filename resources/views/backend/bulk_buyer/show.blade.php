@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Bulk Buyer Details')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('bulk_buyers.index') }}" class="btn btn-circle btn-primary">
                <span>{{translate('Back to Bulk Buyers')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="row gutters-10">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{translate('Customer Info')}}</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="opacity-50 mb-1">{{translate('Name')}}</div>
                    <div class="fw-600">{{ $bulk_buyer->name }}</div>
                </div>
                <div class="mb-3">
                    <div class="opacity-50 mb-1">{{translate('Email')}}</div>
                    <div class="fw-600">{{ $bulk_buyer->email }}</div>
                </div>
                <div class="mb-3">
                    <div class="opacity-50 mb-1">{{translate('Phone')}}</div>
                    <div class="fw-600">{{ $bulk_buyer->phone ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <div class="opacity-50 mb-1">{{translate('Bulk Buyer Since')}}</div>
                    <div class="fw-600">{{ date('d M Y', strtotime($bulk_buyer->bulk_buyer_since)) }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="row gutters-10">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="las la-dollar-sign fs-40 text-success mb-3"></i>
                        <h3 class="fw-600">{{ single_price($bulk_buyer->bulk_buyer_total_advance) }}</h3>
                        <div class="opacity-60">{{translate('Total Advance Paid')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="las la-hourglass-half fs-40 text-warning mb-3"></i>
                        <h3 class="fw-600">{{ single_price($bulk_buyer->bulk_buyer_total_pending) }}</h3>
                        <div class="opacity-60">{{translate('COD Pending')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="las la-check-circle fs-40 text-success mb-3"></i>
                        <h3 class="fw-600">{{ single_price($bulk_buyer->bulk_buyer_total_cod_received) }}</h3>
                        <div class="opacity-60">{{translate('COD Received')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="las la-shopping-cart fs-40 text-info mb-3"></i>
                        <h3 class="fw-600">{{ $orders->total() }}</h3>
                        <div class="opacity-60">{{translate('Total Orders')}}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Split Payment Orders')}}</h5>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{translate('Order Code')}}</th>
                            <th>{{translate('Order Date')}}</th>
                            <th>{{translate('Grand Total')}}</th>
                            <th>{{translate('Advance Paid')}}</th>
                            <th>{{translate('Advance Status')}}</th>
                            <th>{{translate('COD Amount')}}</th>
                            <th>{{translate('COD Status')}}</th>
                            <th class="text-right">{{translate('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', encrypt($order->id)) }}" target="_blank">{{ $order->code }}</a>
                                </td>
                                <td>{{ date('d M Y', $order->date) }}</td>
                                <td>{{ single_price($order->grand_total) }}</td>
                                <td>{{ single_price($order->advance_payment_amount) }}</td>
                                <td>
                                    @if($order->advance_payment_status == 'paid')
                                        <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                        <br><small>{{ $order->advance_paid_at ? date('d M Y H:i', strtotime($order->advance_paid_at)) : '' }}</small>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{translate('Unpaid')}}</span>
                                    @endif
                                </td>
                                <td>{{ single_price($order->cod_amount) }}</td>
                                <td>
                                    @if($order->cod_payment_status == 'paid')
                                        <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                        <br><small>{{ $order->cod_paid_at ? date('d M Y H:i', strtotime($order->cod_paid_at)) : '' }}</small>
                                    @else
                                        <span class="badge badge-inline badge-warning">{{translate('Pending')}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($order->cod_payment_status == 'unpaid')
                                        <form action="{{ route('bulk_buyers.mark_cod_paid', $order->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-soft-success btn-sm" onclick="return confirm('{{translate('Are you sure COD payment has been received for this order?')}}')">
                                                {{translate('Mark COD Paid')}}
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge badge-inline badge-success">{{translate('Complete')}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $orders->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


