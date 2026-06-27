@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Make Bulk Buyer')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('bulk_buyers.index') }}" class="btn btn-circle btn-primary">
                <span>{{translate('View All Bulk Buyers')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <form class="" id="sort_customers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('Select Customers to Make Bulk Buyers')}}</h5>
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
                        <th>{{translate('Email Verification')}}</th>
                        <th class="text-right">{{translate('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $key => $customer)
                        <tr>
                            <td>{{ ($key+1) + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>
                                @if($customer->email_verified_at != null)
                                    <span class="badge badge-inline badge-success">{{translate('Verified')}}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{translate('Not Verified')}}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <form action="{{ route('bulk_buyers.make', $customer->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-soft-success btn-sm">
                                        {{translate('Make Bulk Buyer')}}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $customers->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        function sort_customers(){
            $('#sort_customers').submit();
        }
    </script>
@endsection

