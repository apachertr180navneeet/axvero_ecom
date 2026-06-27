@extends('frontend.layouts.app')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10 col-12">

            <!-- Messages -->
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h4 class="text-center mb-4">Membership</h4>

                    <form action="{{ route('agent.payment.initiate') }}" method="POST">
                        @csrf

                        <div class="row">

                            <!-- Amount -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Amount</label>
                                <input type="text" class="form-control" value="₹999" readonly>
                            </div>

                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name"
                                       class="form-control"
                                       value="{{ $user->name ?? '' }}" >
                            </div>

                            <!-- Mobile -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile"
                                       class="form-control"
                                       value="{{ $user->phone ?? '' }}">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                       class="form-control"
                                       value="{{ $user->email ?? '' }}">
                            </div>

                        </div>

                        <hr>

                        <h6 class="mb-3">Referred Person Details</h6>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <input type="text" name="referred_person_name"
                                       class="form-control"
                                       placeholder="Referred Person Name">
                            </div>

                            <div class="col-md-6 mb-3">
                                <input type="text" name="referred_person_mobile"
                                       class="form-control"
                                       placeholder="Referred Person Mobile">
                            </div>

                            <div class="col-md-12 mb-3">
                                <input type="email" name="referred_person_email"
                                       class="form-control"
                                       placeholder="Referred Person Email">
                            </div>

                        </div>

                        <hr>

                        <h6 class="mb-3">Manager/RM Details</h6>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <input type="text" name="manager_name"
                                       class="form-control"
                                       placeholder="Manager Name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <input type="email" name="manager_company_email"
                                       class="form-control"
                                       placeholder="Manager Company Email ID" required>
                            </div>

                        </div>

 <h6 class="mb-3">Sales Manager Details</h6>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <input type="text" name="sales_name"
                                       class="form-control"
                                       placeholder="Sales Manager Name" >
                            </div>

                            <div class="col-md-6 mb-3">
                                <input type="email" name="sales_Email"
                                       class="form-control"
                                       placeholder="Sales Manager Company Email ID" >
                            </div>

                        </div>


                        <!-- Hidden fields for PayU -->
                        <input type="hidden" name="phone" value="{{ $user->phone ?? '' }}">
                        <input type="hidden" name="email" value="{{ $user->email ?? '' }}">

                        {{-- Terms & Conditions Checkbox --}}
                        <div class="mb-3 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsCheckbox">
                                <label class="form-check-label" for="termsCheckbox">
                                    I have read and agree to the
                                    <a href="{{ url('/Membershiptermscondition') }}" target="_blank" class="text-primary fw-semibold">
                                        Terms & Conditions
                                    </a>
                                    of Membership.
                                </label>
                            </div>
                            <div id="termsError" class="text-danger small mt-1" style="display:none;">
                                Please accept the Terms & Conditions to proceed.
                            </div>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="payBtn">
                                Proceed to Pay ₹999
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('payBtn').addEventListener('click', function (e) {
        const checkbox = document.getElementById('termsCheckbox');
        const errorMsg = document.getElementById('termsError');

        if (!checkbox.checked) {
            e.preventDefault();
            errorMsg.style.display = 'block';
        } else {
            errorMsg.style.display = 'none';
        }
    });
</script>

@endsection