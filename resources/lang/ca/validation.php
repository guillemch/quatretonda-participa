<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute ha de ser acceptat.',
        'active_url'           => ':attribute no és un URL vàlid.',
        'after'                => ':attribute ha de ser una data posterior a :date.',
        'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
        'alpha'                => ':attribute només pot contenir lletres.',
        'alpha_dash'           => ':attribute només por contenir lletres, números i guions.',
        'alpha_num'            => ':attribute només pot contenir lletres i números.',
        'array'                => ':attribute ha de ser un conjunt.',
        'before'               => ':attribute ha de ser una data anterior a :date.',
        'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
        'between'              => [
            'numeric' => ":attribute ha d'estar entre :min - :max.",
            'file'    => ':attribute ha de pesar entre :min - :max kilobytes.',
            'string'  => ':attribute ha de tenir entre :min - :max caràcters.',
            'array'   => ':attribute ha de tenir entre :min - :max ítems.',
        ],
        'boolean'              => ':attribute ha de ser veritat o fals',
        'confirmed'            => 'La confirmació de :attribute no coincideix.',
        'date'                 => ':attribute no és una data vàlida.',
        'date_format'          => ':attribute no correspon al format :format.',
        'different'            => ':attribute i :other han de ser diferents.',
        'digits'               => ':attribute ha de tenir :digits digits.',
        'digits_between'       => ':attribute ha de tenir entre :min i :max digits.',
        'dimensions'           => 'Les dimensions de la imatge :attribute no són vàlides.',
        'distinct'             => ':attribute té un valor duplicat.',
        'email'                => ':attribute no és un e-mail vàlid',
        'exists'               => ':attribute és invàlid.',
        'file'                 => ':attribute ha de ser un arxiu.',
        'filled'               => ':attribute és obligatori.',
        'image'                => ':attribute ha de ser una imatge.',
        'in'                   => ':attribute és invàlid',
        'in_array'             => ':attribute no existeix a :other.',
        'integer'              => ':attribute ha de ser un nombre enter.',
        'ip'                   => ':attribute ha de ser una adreça IP vàlida.',
        'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
        'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
        'json'                 => ':attribute ha de contenir una cadena JSON vàlida.',
        'max'                  => [
            'numeric' => ':attribute no ha de ser major a :max.',
            'file'    => ':attribute no ha de ser més gran que :max kilobytes.',
            'string'  => ':attribute no ha de ser més gran que :max characters.',
            'array'   => ':attribute no ha de tenir més de :max ítems.',
        ],
        'mimes'                => ':attribute ha de ser un arxiu amb format: :values.',
        'mimetypes'            => ':attribute ha de ser un arxiu amb format: :values.',
        'min'                  => [
            'numeric' => "El tamany de :attribute ha de ser d'almenys :min.",
            'file'    => "El tamany de :attribute ha de ser d'almenys :min kilobytes.",
            'string'  => ':attribute ha de contenir almenys :min caràcters.',
            'array'   => ':attribute ha de tenir almenys :min ítems.',
        ],
        'not_in'               => ':attribute és invàlid.',
        'numeric'              => ':attribute ha de ser numèric.',
        'present'              => ':attribute ha de ser present.',
        'regex'                => 'El format de :attribute és invàlid.',
        'required'             => ':attribute és obligatori.',
        'required_if'          => ':attribute és obligatori quan :other és :value.',
        'required_unless'      => ':attribute és obligatori a no ser que :other sigui a :values.',
        'required_with'        => ':attribute és obligatori quan :values és present.',
        'required_with_all'    => ':attribute és obligatori quan :values és present.',
        'required_without'     => ':attribute és obligatori quan :values no és present.',
        'required_without_all' => ':attribute és obligatori quan cap dels :values estan presents.',
        'same'                 => ':attribute i :other han de coincidir.',
        'size'                 => [
            'numeric' => 'El tamany de :attribute ha de ser :size.',
            'file'    => 'El tamany de :attribute ha de ser :size kilobytes.',
            'string'  => ':attribute ha de contenir :size caràcters.',
            'array'   => ':attribute ha de contenir :size ítems.',
        ],
        'string'               => ':attribute ha de ser una cadena de caràcters.',
        'timezone'             => ':attribute ha de ser una zona vàlida.',
        'unique'               => ':attribute ja ha estat registrat.',
        'uploaded'             => ':attribute ha fallat al pujar.',
        'url'                  => 'El format :attribute és invàlid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'SID' => [
            'on_census' => 'No hem trobat al cens l\'identificador introduit',
            'has_not_voted'=> 'L\'identificador introduit ja ha emès un vot.',
            'ip_limit' => 'Has sobrepassat el número de vots que una mateixa IP pot emetre',
        ],
        'ballot' => [
            'ballot_validity' => 'La papereta no és vàlida. Segueix les instruccions que es mostren a cada pregunta.',
            'ballot_max' => 'Has seleccionat més opcions de les que es permeten.',
            'ballot_min' => 'Has de seleccionar al menys una opció en la pregunta ":question"|Has de seleccionar com a mínim :min_options opcions en la pregunta ":question"',
        ],
        'phone' => [
            'phone_format' => 'El format del mòbil introduit no és correcte',
            'phone_not_used' => 'El mòbil introduit ja ha emès un vot.'
        ],
        'SMS_code' => [
            'sms_code' => 'El codi SMS introduit no és correcte.'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'SID' => 'L\'identificador',
        'ballot' => 'El camp papereta',
        'phone' => 'El teu número de mòbil',
        'country_code' => 'El prefix internacional',
        'SMS_code' => 'El codi SMS',
        'reason' => 'El camp justificació'
    ],

];
