<?php
/**
 * Configuration for Form Mail
 */
return [

    /**
     * Custom branding string for email message.
     * It is inserted at the top of the
     * email message. If unneeded just leave blank
     */
    'branding' => '',

    /**
     * Global rules for form-mail validation
     */
    'rules' => [
        'email' => 'required|email',
        'name' => 'required',
        'fields' => 'required|array'
    ],

    /**
     * Route specific rules for form validation
     */
    'route_rules' => [],

    /**
     * Route specific mail recipient if empty the recipient will be auto generated
     */
    'recipient' => [],


    /**
     * Whether to queue the message and have it
     * sent out on the next cycle or send
     * out immediately
     */
    'queue' => true,

    /**
     * Whether or not to send back a confirmation
     * message back to sender
     */
    'confirmation' => true,

    /**
     * Delay to put on messages in queue
     */
    'delay' => [
        'send_message' => 30,
        'send_confirmation' => 45
    ]
];

