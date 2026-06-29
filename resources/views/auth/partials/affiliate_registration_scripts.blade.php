@if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('reg-form').addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'register'}).then(function(token) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', 'g-recaptcha-response');
                    input.setAttribute('value', token);
                    e.target.appendChild(input);

                    e.target.submit();
                });
            });
        });
    </script>
@endif
<script>
    const createBtn   = $('#createAccountBtn');
    const termsCheckbox = $('input[name="checkbox_example_1"]');
    function toggleCreateBtn() {
        const termsChecked = termsCheckbox.is(':checked');
        createBtn.prop('disabled', !termsChecked);
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleCreateBtn();
        termsCheckbox.on('change', toggleCreateBtn);
    });
</script>
