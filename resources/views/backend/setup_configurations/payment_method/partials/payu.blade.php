<form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
    @csrf

    <input type="hidden" name="payment_method" value="payu">

    {{-- PayU Merchant Key --}}
    <div class="form-group row">
        <input type="hidden" name="types[]" value="PAYU_MERCHANT_KEY">
        <div class="col-md-4">
            <label class="col-from-label">{{ translate('PayU Merchant Key') }}</label>
        </div>
        <div class="col-md-8">
            <input type="text"
                   class="form-control"
                   name="PAYU_MERCHANT_KEY"
                   value="{{ env('PAYU_MERCHANT_KEY') }}"
                   placeholder="{{ translate('PayU Merchant Key') }}"
                   required>
        </div>
    </div>

    {{-- PayU Merchant Salt --}}
    <div class="form-group row">
        <input type="hidden" name="types[]" value="PAYU_SALT">
        <div class="col-md-4">
            <label class="col-from-label">{{ translate('PayU Merchant Salt') }}</label>
        </div>
        <div class="col-md-8">
            <input type="text"
                   class="form-control"
                   name="PAYU_SALT"
                   value="{{ env('PAYU_SALT') }}"
                   placeholder="{{ translate('PayU Merchant Salt') }}"
                   required>
        </div>
    </div>

    {{-- PayU Sandbox Mode --}}
    <div class="form-group row">
        <div class="col-md-4">
            <label class="col-from-label">{{ translate('PayU Sandbox Mode') }}</label>
        </div>
        <div class="col-md-8">
            <label class="aiz-switch aiz-switch-success mb-0">
                <input value="1"
                       name="payu_sandbox"
                       type="checkbox"
                       @if (get_setting('payu_sandbox') == 1) checked @endif>
                <span class="slider round"></span>
            </label>
        </div>
    </div>

    {{-- Save Button --}}
    <div class="form-group mb-0 text-right">
        <button type="submit" class="btn btn-sm btn-primary">
            {{ translate('Save') }}
        </button>
    </div>
</form>
