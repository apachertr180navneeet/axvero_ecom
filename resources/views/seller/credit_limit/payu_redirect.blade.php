<form action="{{ env('PAYU_BASE_URL') }}" method="post" name="payuForm">

<input type="hidden" name="key" value="{{ $key }}" />
<input type="hidden" name="txnid" value="{{ $txnid }}" />
<input type="hidden" name="amount" value="{{ $amount }}" />
<input type="hidden" name="productinfo" value="{{ $productinfo }}" />
<input type="hidden" name="firstname" value="{{ $firstname }}" />
<input type="hidden" name="email" value="{{ $email }}" />

<input type="hidden" name="phone" value="{{ auth()->user()->phone }}">
<input type="hidden" name="service_provider" value="payu_paisa">

<input type="hidden" name="surl" value="{{ route('seller.credit.success') }}">
<input type="hidden" name="furl" value="{{ route('seller.credit.failed') }}">

<input type="hidden" name="hash" value="{{ $hash }}">

</form>

<script>
document.payuForm.submit();
</script>


