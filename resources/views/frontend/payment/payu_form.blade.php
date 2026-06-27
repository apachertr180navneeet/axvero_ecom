<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Payment...</title>
</head>
<body>
    <p style="text-align:center; margin-top: 50px; font-family: sans-serif;">
        Please wait, redirecting to payment gateway...
    </p>

    {{-- NO @csrf here — PayU redirect form mein CSRF nahi chahiye --}}
    <form action="{{ env('PAYU_BASE_URL') }}" method="POST" name="payuForm" id="payuForm">

        <input type="hidden" name="key"         value="{{ $key }}" />
        <input type="hidden" name="txnid"       value="{{ $txnid }}" />
        <input type="hidden" name="amount"      value="{{ $amount }}" />
        <input type="hidden" name="productinfo" value="{{ $productinfo }}" />
        <input type="hidden" name="firstname"   value="{{ $firstname }}" />
        <input type="hidden" name="email"       value="{{ $email }}" />
        <input type="hidden" name="phone"       value="{{ auth()->user()->phone ?? '9999999999' }}" />

        {{-- UDF fields — empty rakhna zaroori hai (hash ke saath match karne ke liye) --}}
        <input type="hidden" name="udf1" value="" />
        <input type="hidden" name="udf2" value="" />
        <input type="hidden" name="udf3" value="" />
        <input type="hidden" name="udf4" value="" />
        <input type="hidden" name="udf5" value="" />

        <input type="hidden" name="surl" value="{{ route('agent.payment.success') }}" />
        <input type="hidden" name="furl" value="{{ route('agent.payment.fail') }}" />
        <input type="hidden" name="hash" value="{{ $hash }}" />

    </form>

    <script>
        window.onload = function () {
            document.getElementById('payuForm').submit();
        };
    </script>

</body>
</html>