    @extends('seller.layouts.app')
    
    @section('panel_content')
    
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
        
        <div class="col-md-6 col-6">
        <h1 class="h3 text-primary">Credit Wallet</h1>
        </div>
        
        <div class="col-md-6 col-6 text-right">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCreditModal">
        <i class="las la-plus"></i> Add Credit
        </button>
        </div>
        
        </div>
        </div>
        
        
        
        @if(session('success'))
        <div class="alert alert-success">
        {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger">
        {{ session('error') }}
        </div>
        @endif
        
        
        
        <div class="row">
        
        <div class="col-lg-4 col-md-6 col-12">
        
        <div class="card shadow-sm">
        <div class="card-body text-center">
        
        <h6 class="text-muted">Remaining Credit</h6>
        
        <h2 class="text-primary fw-700 mb-0">
        ₹ {{ number_format($remainingCredit,2) }}
        </h2>
        
        <p class="text-muted small mt-2">
        Available wallet balance
        </p>
        
        </div>
        </div>
        
        </div>
        
        </div>
        
        
        
        <div class="card mt-4">
        <div class="card-header">
        <h5 class="mb-0">Transaction History</h5>
        </div>
        
        <div class="card-body table-responsive">
        
        <table class="table table-bordered table-striped">
        
        <thead>
        <tr>
        <th>ID</th>
        <th>Amount</th>
        <th>Type</th>
        <th>Date</th>
        </tr>
        </thead>
        
        <tbody>
        
        @forelse($history as $row)
        
        <tr>
        
        <td>{{ $row->id }}</td>
        
        <td>₹ {{ number_format($row->amount,2) }}</td>
        
        <td>
        
        @if($row->type == 'credit')
       <span class="badge bg-success text-white px-3 py-1">
        Credit
        </span>
        @else
        <span class="badge badge-danger">
        Debit
        </span>
        @endif
        
        </td>
        
        <td>{{ $row->created_at->format('d M Y H:i') }}</td>
        
        </tr>
        
        @empty
        
        <tr>
        <td colspan="4" class="text-center text-muted">
        No Transactions Found
        </td>
        </tr>
        
        @endforelse
        
        </tbody>
        
        </table>
        
        </div>
        </div>
        
        
        
        
        <!-- Add Credit Modal -->
        
        <div class="modal fade" id="addCreditModal">
        <div class="modal-dialog modal-dialog-centered">
        
        <div class="modal-content">
        
        <form action="{{ route('seller.credit.add') }}" method="POST">
        @csrf
        
        <div class="modal-header">
        
        <h5 class="modal-title">Add Credit</h5>
        
        <button type="button" class="close" data-dismiss="modal">
        ×
        </button>
        
        </div>
        
        
        
        <div class="modal-body">
        
        <div class="form-group">
        
        <label>Enter Amount</label>
        
        <input
        type="number"
        name="amount"
        class="form-control"
        placeholder="Minimum ₹1100"
        min="1100"
        required
        >
        
        <small class="text-muted">
        Minimum recharge ₹1100
        </small>
        
        @error('amount')
        <div class="text-danger">
        {{ $message }}
        </div>
        @enderror
        
        </div>
        
        </div>
        
        
        
        <div class="modal-footer">
        
        <button type="submit" class="btn btn-primary btn-block">
        Proceed to Payment
        </button>
        
        </div>
        
        </form>
        
        </div>
        
        </div>
    </div>
    
    
    @endsection