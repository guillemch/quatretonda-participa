export default {
    global: {
        tweet: ''
    },
    option: {
        cost: 'Cost',
        motivation: 'Motivació',
        attachments: 'Documentació adjunta'
    },
    error: {
        heading: 'Error',
        challenge: 'Si penses que es tracta d\'un error o necessites ajuda, posa\'t en contacte amb',
        back: 'Torna'
    },
    booth_identification: {
        heading: 'Identificació',
        subheading: 'Pots votar si tens més de <strong>{min_age} anys</strong> i estàs empadronat a <strong>{municipality}</strong>',
        label: 'DNI, NIE o Passport',
        tooltip: 'Passaport només si eres resident extranger',
        button: 'Vota',
        anonymous_voting: 'El teu vot és anònim i serà encriptat'
    },
    booth_option: {
        more_info: 'Més info',
    },
    verify_summary: {
        edit: 'Edita papereta',
        blank: 'Vot en blanc'
    },
    verify_phone: {
        heading: 'Verifica la teua papereta',
        phone_label: 'Mòbil',
        code_label: 'Codi SMS',
        code_tooltip: 'Codi numèric de 6 digits',
        phone_subheading: 'Escriu el teu telèfon mòbil. T\'enviarem un codi per SMS per a autenticar el teu vot.',
        code_subheading: 'Introdueix a continuació el codi que has rebut al teu mòbil.',
        code_smalltext: 'L\'SMS pot tardar uns minuts en arribar. Assegura\'t que el teu mòbil està encés i té cobertura.',
        request_sms_button: 'Envia\'m el codi',
        cast_ballot_button: 'Confirma el meu vot',
        country_code: 'Prefixe internacional',
    },
    verify_in_person: {
        button: 'Confirma'
    },
    verify_flags: {
        SMS_already_sent: 'Introdueix el codi SMS ja es va enviar al mòbil introduit el <strong>{time}</strong>',
        SMS_exceeded: 'Has sobrepassat el límit de <strong>{sms_max_attempts} intents SMSs per votant</strong>. Introdueix el codi que vares rebre al número <strong>{last_number}</strong> el <strong>{time}</strong>'
    },
    booth_receipt: {
        heading: 'Gràcies per participar!',
        success: 'El teu vot ha estat emés correctament',
        social: 'Convida les teues amistats a participar i fem la [city_name] que volem entre tots i totes ;)',
        back_to_council: 'Ves a la pàgina de l\'Ajuntament',
        back_to_booth: 'Emet un altre vot'
    },
    option_modal: {
        select_button: 'Selecciona aquesta opció',
        deselect_button: 'Deselecciona aquesta opció',
        dismiss_button: 'Tanca'
    }
};
