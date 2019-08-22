<?php
    $input_field_id = 'recaptcha-token-' . \Illuminate\Support\Str::random(10);
?>

<input type="hidden" id="{{ $input_field_id }}" class="g-recaptcha-response" name="g-recaptcha-response" value="" required>
<script src="https://www.google.com/recaptcha/api.js?render={{ $public_key }}"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute("{{ $public_key }}", {action: "homepage"}).then(function(token) {
            document.getElementById("{{ $input_field_id }}").value = token;
        });
    });
</script>
