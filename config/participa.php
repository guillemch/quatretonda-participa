<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Council details
    |--------------------------------------------------------------------------
    */

    /* The name of the city */
    'municipality' => 'Quatretonda',

    /* The name of the council */
    'council_name' => 'Ajuntament de Quatretonda',

    /* The council's home page */
    'council_url' => 'http://www.quatretonda.es',

    /* The council's logo / coat of arms */
    'council_logo' => 'council.png',

    /* The e-mail where users can contact for support */
    'contact_email' => 'incidencies@quatretondaparticipa.com',

    /* The council's address */
    'contact_address' => 'Ausias March, 5 - CP: 46837',

    /* The council's contact phone */
    'contact_phone' => '96 226 45 71',

    /* The council's facebook page and app ID */
    'facebook' => 'https://www.facebook.com/ajuntamentquatretonda',
    'facebook_app_id' => '180444172483336',

    /* The council's twitter account */
    'twitter' => 'ajQuatretonda',

    /*
    |--------------------------------------------------------------------------
    | Application settings
    |--------------------------------------------------------------------------
    */

    /* Main logo to display on all pages */
    'logo' => 'logo.png',

    /* Primary color of the application */
    'primary_color' => '#9E0057',

    /* Maximum votes a single IP may cast */
    'max_per_ip' => 20,

    /* Maxiumum times a single IP can fail an ID attempt */
    'max_failed_lookups' => 100,

    /* Maximum SMS a single voter ID may request */
    'sms_max_attempts' => 3,

    /* Whether voting should be anonymous or not */
    'anonymous_voting' => false,

    /* Whether admins may look up IDs to troubleshoot in-person voting */
    'enable_ID_lookup' => true,

    /* Minumum age at which citizens may participate */
    'min_age' => 16,

    /* Whether to display total census number on public result pages */
    'display_census_number' => true,

    /* Should SMS verification be required for online voting? */
    'disable_SMS_verification' => false,

    /* Set up Google Analytics tracking */
    'google_analytics_ID' => 'UA-106217417-1',

    /*
    |--------------------------------------------------------------------------
    | Languages
    |--------------------------------------------------------------------------
    |
    | A list of available locales for the application. This array will be
    | used to display the language switcher's dropdown menu
    |
    */

    'languages' => [
        'ca' => 'Valencià',
        'es' => 'Castellano'
    ]
];
