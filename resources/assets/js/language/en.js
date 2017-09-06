export default {
    global: {
        tweet: ''
    },
    option: {
        cost: 'Cost',
        motivation: 'Motivation',
        attachments: 'Attached documents'
    },
    error: {
        heading: 'Error',
        challenge: 'If you think this is a mistake, you can contact tech support at',
        back: 'Go back'
    },
    booth_identification: {
        heading: 'Identification',
        subheading: 'You can vote if you are over <strong>{min_age} years</strong> of age and currently reside in <strong>{municipality}</strong>',
        label: 'DNI, NIE or Passport',
        tooltip: 'Passport only for foreign residents',
        button: 'Vote',
        anonymous_voting: 'Your vote is anonymous and will be encrypted'
    },
    booth_option: {
        more_info: 'More info',
    },
    verify_summary: {
        edit: 'Edit ballot',
        blank: 'Blank vote'
    },
    verify_phone: {
        heading: 'Verify your ballot',
        phone_label: 'Mobile',
        code_label: 'SMS Code',
        code_tooltip: 'Numeric code',
        phone_subheading: 'Enter your mobil number. You will receive an SMS code to authenticate your vote.',
        code_subheading: 'Type in the code you have received on your mobile.',
        code_smalltext: 'The SMS may take some minutes to arrive. Ensure your phone is on and has signal.',
        request_sms_button: 'Text me the code',
        cast_ballot_button: 'Cast my ballot',
        country_code: 'Country dial code',
    },
    verify_in_person: {
        button: 'Cast your ballot'
    },
    verify_flags: {
        SMS_already_sent: 'Use the code that was sent to this mobile phone on <strong>{time}</strong>',
        SMS_exceeded: 'You have exceeded the limit of <strong>{sms_max_attempts} SMSs per voter</strong>. Enter the code that you received on <strong>{last_number}</strong> on <strong>{time}</strong>'
    },
    booth_receipt: {
        heading: 'Thanks for your participation',
        success: 'Your ballot has been cast successfully',
        social: 'Invite your friends to participate. Let\'s make a better {municipality} together ;)',
        back_to_council: 'Back to council\'s frontpage',
        back_to_booth: 'Cast another ballot'
    },
    option_modal: {
        select_button: 'Select this option',
        deselect_button: 'Deselect this option',
        dismiss_button: 'Close'
    }
};
