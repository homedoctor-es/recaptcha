<?php
    $random = \Illuminate\Support\Str::random(10);
    $input_field_id = 'recaptcha-token-' . $random;
?>

<input type="hidden" id="{{ $input_field_id }}" class="g-recaptcha-response" name="g-recaptcha-response" value="" required>
<script src="https://www.google.com/recaptcha/api.js?render={{ $public_key }}"></script>
<script>
    var {{ 'recaptcha_token_' . $random }} = document.getElementById("{{ $input_field_id }}");
    var {{ 'form_' . $random }} = {{ 'recaptcha_token_' . $random }}.closest("form");

    {{ 'form_' . $random }}.onsubmit = function(event) {
        event.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute("{{ $public_key }}", {action: "submit"}).then(function(token) {
                {{ 'recaptcha_token_' . $random }}.value = token;
                {{ 'form_' . $random }}.submit();
            });
        });
    }
</script>
