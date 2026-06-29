@extends('frontend.layouts.user_panel')

@section('panel_content')

    <div class="card modern-card shadow-none rounded-0 border-0 mb-4">
        <div class="card-header pt-4 border-bottom-0 pb-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Payment Settings') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('affiliate.payment_settings_store') }}" method="POST">
                @csrf

                <div class="form-group row align-items-center">
                    <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Bank Name') }}</label>
                    <div class="col-md-9">
                        <input type="text" class="modern-input" name="bank_name"
                            value="{{ $payment_settings->bank_name }}" placeholder="{{ translate('Bank Name') }}">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Bank Account Name') }}</label>
                    <div class="col-md-9">
                        <input type="text" class="modern-input" name="bank_acc_name"
                            value="{{ $payment_settings->bank_acc_name }}"
                            placeholder="{{ translate('Bank Account Name') }}">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Bank Account Number') }}</label>
                    <div class="col-md-9">
                        <input type="text" class="modern-input" name="bank_acc_no"
                            value="{{ $payment_settings->bank_acc_no }}"
                            placeholder="{{ translate('Bank Account Number') }}">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Bank IBAN') }}</label>
                    <div class="col-md-9">
                        <input type="text" class="modern-input" name="bank_iban"
                            value="{{ $payment_settings->bank_iban }}" placeholder="{{ translate('Bank IBAN') }}">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-md-3 col-form-label fs-14 fw-600">{{ translate('Bank Routing Number') }}</label>
                    <div class="col-md-9">
                        <input type="text" class="modern-input" name="bank_routing_no"
                            value="{{ $payment_settings->bank_routing_no }}"
                            placeholder="{{ translate('Bank Routing Number') }}">
                    </div>
                </div>

                <div class="form-group mb-0 text-right mt-4">
                    <button type="submit" class="btn modern-btn modern-btn-primary">{{ translate('Update') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
