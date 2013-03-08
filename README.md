Icu Component
=============

Gives access to the data of the ICU library.

The ICU data is located in several 'resource bundles'. You can access a PHP
wrapper of these bundles through the static Icu class.

Languages and Scripts
---------------------

The translations of language and script names can be found in the language
bundle.

    $languages = Icu::getLanguageBundle()->getLanguageNames('en');
    // => array('ab' => 'Abkhazian', ...)

    $language = Icu::getLanguageBundle()->getLanguageName('en', 'de');
    // => 'German'

    $language = Icu::getLanguageBundle()->getLanguageName('en', 'de', 'AT);
    // => 'Austrian German'

    $scripts = Icu::getLanguageBundle()->getScriptNames('en');
    // => array('Arab' => 'Arabic', ...)

    $script = Icu::getLanguageBundle()->getScriptName('en', 'Hans');
    // => 'Simplified'

Countries
---------

The translations of country names can be found in the region bundle.

    $countries = Icu::getRegionBundle()->getCountryNames('en');
    // => array('AF' => 'Afghanistan', ...)

    $country = Icu::getRegionBundle()->getCountryName('en', 'GB');
    // => 'United Kingdom'

Locales
-------

The translations of locale names can be found in the locale bundle.

    $locales = Icu::getLocaleBundle()->getLocaleNames('en');
    // => array('af' => 'Afrikaans', ...)

    $locale = Icu::getLocaleBundle()->getLocaleName('en', 'zh_Hans_MO');
    // => 'Chinese (Simplified, Macau SAR China)'

Currencies
----------

The translations of currency names and other currency-related information can
be found in the currency bundle.

    $currencies = Icu::getCurrencyBundle()->getCurrencyNames('en');
    // => array('AFN' => 'Afghan Afghani', ...)

    $currency = Icu::getCurrencyBundle()->getCurrencyNames('en', 'INR');
    // => 'Indian Rupee'

    $symbol = Icu::getCurrencyBundle()->getCurrencyNames('en', 'INR');
    // => '₹'

    $fractionDigits = Icu::getCurrencyBundle()->getFractionDigits('INR');
    // => 2

    $roundingIncrement = Icu::getCurrencyBundle()->getRoundingIncrement('INR');
    // => 0

Resources
---------

You can run the unit tests with the following command:

    $ cd path/to/Symfony/Component/Icu/
    $ composer.phar install --dev
    $ phpunit
