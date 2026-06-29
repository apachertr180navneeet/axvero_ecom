<script type="text/javascript">
    function autoFillCustomer(){
        $('#email').val('customer@example.com');
        $('#password').val('123456');
    }
</script>

@if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_login') == 1)
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('user-login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'register'}).then(function(token) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', 'g-recaptcha-response');
                    input.setAttribute('value', token);
                    e.target.appendChild(input);

                    var actionInput = document.createElement('input');
                    actionInput.setAttribute('type', 'hidden');
                    actionInput.setAttribute('name', 'recaptcha_action');
                    actionInput.setAttribute('value', 'recaptcha_customer_login');
                    e.target.appendChild(actionInput);

                    e.target.submit();
                });
            });
        });
    </script>
@endif
