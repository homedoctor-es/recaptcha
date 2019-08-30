# Recaptcha

A reCAPTCHA Validator for Laravel 5.

This is a forked version of the [reCaptcha package](https://github.com/greggilbert/recaptcha) for same functionality but with support for reCaptcha Version 3.

> (Looking for a Laravel 4 version? Pull the latest 1.x tag. For Laravel 5.0, pull the latest 2.0 tag.)

> For reCaptcha v3 support use `dev-master` from this repository.

## Installation

Add the following line to the `require` section of `composer.json`:

```json
{
    "require": {
        "kuttumiah/recaptcha": "dev-master",
    }
}
```

or run the command below in terminal

```bash
$ composer require "kuttumiah/recaptcha:dev-master"
```

## Setup

1. If you are using **Laravel 5.5+** and using package auto-discovery, you can skip this step. For older versions or if you have
   disabled package auto-discovery continue with this step.

   In `/config/app.php`, add the following to `providers`:
   ```php
   Kuttumiah\Recaptcha\RecaptchaServiceProvider::class,
   ```

   and the following to `aliases`:

   ```php
   'Recaptcha' => Kuttumiah\Recaptcha\Facades\Recaptcha::class,
   ```

2. Run `php artisan vendor:publish --provider="Kuttumiah\Recaptcha\RecaptchaServiceProvider"`.

3. In `/config/recaptcha.php`, enter your reCAPTCHA public and private keys.
   * If you are not using the most recent version of reCAPTCHA, set `version` to 2 or 1.
   * If you are upgrading to v3 of reCAPTCHA, note that your keys from the previous version will not work, and you need to generate a new set in [the reCAPTCHA admin](https://www.google.com/recaptcha/admin).

4. The package ships with a default validation message, but if you want to customize it, add the following line into `resources/lang/[lang]/validation.php`:

   ```php
       'recaptcha' => 'The :attribute field is not correct.',
   ```

### Migrating from `greggilbert/recaptcha` package

1. In `/config/app.php`, remove the following from `providers`:

   ```php
   Greggilbert\Recaptcha\RecaptchaServiceProvider::class,
   ```

   and the following from `aliases`:

   ```php
   'Recaptcha' => Greggilbert\Recaptcha\Facades\Recaptcha::class,
   ```
2. Remove the following line from the `require` section of `composer.json`:

   ```json
   {
       "require": {
           "greggilbert/recaptcha": "dev-master",
       }
   }
   ```

4. Run the command below in terminal

   ```bash
   $ composer update
   ```

5. Follow the [Installation](#installation) and [Setup](#setup) steps.

**Note:** If you face any issue error while migrating please check the [Troubleshoot](#troubleshoot) section.

## Usage

### v2 (No Captcha)
1. In your form, use `{!! Recaptcha::render() !!}` to echo out the markup.
2. In your validation rules, add the following:

```php
    $rules = [
        // ...
        'g-recaptcha-response' => 'required|recaptcha',
    ];
```

### v1 (Legacy)
1. In your form, use `{!! Recaptcha::render() !!}` to echo out the markup.
2. In your validation rules, add the following:

```php
    $rules = [
        // ...
        'recaptcha_response_field' => 'required|recaptcha',
    ];
```

It's also recommended to add `required` when validating.

## Customization

reCAPTCHA v2 allows for customization of the widget through a number of options, listed [at the official documentation](https://developers.google.com/recaptcha/docs/display). You can configure the output of the captcha through six allowed keys: `theme`, `type`, `lang`, `callback`, `tabindex` and `expired-callback`.

In the config file, you can create an `options` array to set the default behavior. For example:

```php
    // ...
    'options' => [
		'lang' => 'ja',
	],
```

would default the language in all the reCAPTCHAs to Japanese. If you want to further customize, you can pass options through the render option:

```php
echo Recaptcha::render([ 'lang' => 'fr' ]);
```

Options passed into `Recaptcha::render` will always supercede the configuration.

### Language

To change the language of the captcha, simply pass in a language as part of the options:

```php
    'options' => [
        'lang' => 'fr',
	],
```

For a list of valid language codes, consulting [the official documentation](https://developers.google.com/recaptcha/docs/language).

### Custom template

Alternatively, if you want to set a default template instead of the standard one, you can use the config:

```php
    // ...
    'template' => 'customCaptcha',
```

or you can pass it in through the Form option:

```php
echo Recaptcha::render([ 'template' => 'customCaptcha' ]);
```

## Troubleshoot
While migrating from `greggilbert/recaptcha` package you might end up raising an error like below


> \> @php artisan package:discover
>
> In ProviderRepository.php line 208:
>
> &nbsp;&nbsp;&nbsp;&nbsp;Class 'Greggilbert\Recaptcha\RecaptchaServiceProvider' not found<br/><br/>
>
>
>  <span style="background:red; color:white">Script @php artisan package:discover handling the post-autoload-dump event returned with
>  error code 1</span>

To resolve the issue I found a helpful resource on [Stack Overflow](https://stackoverflow.com/a/53705743/2117868) which can fix this issue. Attaching the solution here for convenience.

Go to your `project > bootstrap > cache > config.php` file. Remove the provider and aliases from the cached array manually.

Or simply remove the file and generate again by running the command below,

```bash
$ php artisan config:cache
```

### v1 customization

For the v1 customization options, consult [the old documentation](https://developers.google.com/recaptcha/old/docs/customization) and apply accordingly.

## Limitation

Because of Google's way of displaying the reCAPTCHA, this package won't work if you load your form from an AJAX call.
If you need to do it, you should use one of [the alternate methods provided by Google](https://developers.google.com/recaptcha/docs/display?csw=1).
