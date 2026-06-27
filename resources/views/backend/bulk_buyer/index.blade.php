@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Bulk Buyers')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('bulk_buyers.customers') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Bulk Buyer')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <form class="" id="sort_bulk_buyers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('All Bulk Buyers')}}</h5>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search" @isset($search) value="{{ $search }}" @endisset placeholder="{{ translate('Type email or name & Enter') }}">
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Name')}}</th>
                        <th>{{translate('Email')}}</th>
                        <th>{{translate('Phone')}}</th>
                        <th>{{translate('Bulk Buyer Since')}}</th>
                        <th>{{translate('Total Orders')}}</th>
                        <th>{{translate('Advance Paid')}}</th>
                        <th>{{translate('COD Pending')}}</th>
                        <th>{{translate('COD Received')}}</th>
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bulk_buyers as $key => $bulk_buyer)
                        <tr>
                            <td>{{ ($key+1) + ($bulk_buyers->currentPage() - 1) * $bulk_buyers->perPage() }}</td>
                            <td>{{ $bulk_buyer->name }}</td>
                            <td>{{ $bulk_buyer->email }}</td>
                            <td>{{ $bulk_buyer->phone }}</td>
                            <td>{{ date('d M Y', strtotime($bulk_buyer->bulk_buyer_since)) }}</td>
                            <td>{{ $bulk_buyer->orders()->where('is_split_payment', 1)->count() }}</td>
                            <td>{{ single_price($bulk_buyer->bulk_buyer_total_advance) }}</td>
                            <td>{{ single_price($bulk_buyer->bulk_buyer_total_pending) }}</td>
                            <td>{{ single_price($bulk_buyer->bulk_buyer_total_cod_received) }}</td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('bulk_buyers.show', ['id' => $bulk_buyer->id]) }}" title="{{ translate('View Details') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                <a class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('bulk_buyers.remove', $bulk_buyer->id) }}" title="{{ translate('Remove Bulk Buyer') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $bulk_buyers->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="confirm-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Do you really want to remove bulk buyer status from this customer?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                <form method="POST" class="delete-form">
                    @csrf
                    <button type="submit" class="btn btn-danger">{{translate('Remove')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        function sort_bulk_buyers(el){
            $('#sort_bulk_buyers').submit();
        }

        $(document).on("click", ".confirm-delete", function() {
            var url = $(this).data("href");
            $(".delete-form").attr("action", url);
            $("#confirm-delete").modal("show");
        });
    </script>
@endsection


