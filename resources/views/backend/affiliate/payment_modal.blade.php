<form class="form-horizontal" action="{{ route('affiliate_user.payment_store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title h6">{{ translate('Pay to Affiliate User') }}</h5>
        <button type="button" class="close" data-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="affiliate_user_id" value="{{ $affiliate_user->id }}">

        <div class="form-group row">
            <label class="col-sm-3 col-from-label" for="amount">{{ translate('Amount') }}</label>
            <div class="col-sm-9">
                <input type="number" lang="en" min="0" step="0.01" name="amount" id="amount"
                    class="form-control" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-from-label" for="payment_method">{{ translate('Payment Method') }}</label>
            <div class="col-sm-9">
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="">{{ translate('Select Payment Method') }}</option>
                    <option value="cash">{{ translate('Cash') }}</option>
                    <option value="bank_payment">{{ translate('Bank Payment') }}</option>
                    <option value="check">{{ translate('Check') }}</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-from-label" for="payment_details">{{ translate('Payment Details') }}</label>
            <div class="col-sm-9">
                <textarea name="payment_details" id="payment_details" class="form-control" rows="4"
                    placeholder="{{ translate('Payment Details') }}"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">{{ translate('Pay') }}</button>
        <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
    </div>
</form>
