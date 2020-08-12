<?php
    $random = \Illuminate\Support\Str::random(10);
    $input_field_id = 'recaptcha-token-' . $random;
?>

<input type="hidden" id="{{ $input_field_id }}" class="g-recaptcha-response" name="g-recaptcha-response" value="" required>
<script src="https://www.google.com/recaptcha/api.js?render={{ $public_key }}"></script>
<script>
    // Polyfill for 'closest' function
    // Source: https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
    if (!Element.prototype.matches) {
        Element.prototype.matches =
            Element.prototype.msMatchesSelector ||
            Element.prototype.webkitMatchesSelector;
    }

    if (!Element.prototype.closest) {
        Element.prototype.closest = function(s) {
            var el = this;

            do {
                if (Element.prototype.matches.call(el, s)) return el;
                el = el.parentElement || el.parentNode;
            } while (el !== null && el.nodeType === 1);
            return null;
        };
    }

    var {{ 'recaptcha_token_' . $random }} = document.getElementById("{{ $input_field_id }}");
    var {{ 'form_' . $random }} = {{ 'recaptcha_token_' . $random }}.closest("form");
    var {{ 'form_elements_' . $random }} = {{ 'form_' . $random }}.elements; // Get all form inputs`
    var {{ 'token_last_set_time_' . $random }};
    var {{ 'token_next_set_time_after' . $random }};
    var TIMER = null;
    var STAGGER = 500;

    function setRecaptchaToken (event) {
        // event = event || window.event;
        // var target = event.target || event.srcElement;
        var now = Date.now();
        var interval = 90 * 1000; // 90 seconds
        if ( {{ 'token_last_set_time_' . $random }} === undefined || now > {{ 'token_next_set_time_after' . $random }} ) {
            grecaptcha.ready(function() {
                grecaptcha.execute("{{ $public_key }}", {action: "homepage"}).then(function(token) {
                    {{ 'recaptcha_token_' . $random }}.value = token;
                    {{ 'token_last_set_time_' . $random }} = now;
                    {{ 'token_next_set_time_after' . $random }} = now + interval;
                });
            });
        }
    }

    // Note: reCAPTCHA tokens expire after two minutes.
    // If you're protecting an action with reCAPTCHA, make sure to call
    // execute when the user takes the action rather than on page load.
    // Source: https://developers.google.com/recaptcha/docs/v3
    {{ 'form_' . $random }}.addEventListener("mousedown", setRecaptchaToken, false);
    for (var i = 0; i < {{ 'form_elements_' . $random }}.length; i++) {
        {{ 'form_elements_' . $random }}[i].addEventListener("keydown", function (event) {
            if (TIMER) clearTimeout(TIMER);
            TIMER = setTimeout(function () {
                setRecaptchaToken(event);
            }, STAGGER);
        }, false);
    }
</script>
