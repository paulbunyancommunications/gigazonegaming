<?php
return [
    'branding' => '&nbsp;',
    'lol-team-sign-up' => [
        'sender' => 'Thanks for signing up your team to play League of Legends! This is a summary of the form you submitted. A copy of this form will be forwarded to :recipient.',
        'recipient' => 'A new League of Legends team has signed up at :time from :domain.',
        'subject' => [
            'recipient' => 'A new League of Legends team has signed up',
            'sender' => 'Thanks for signing up your team to play League of Legends',
        ]
    ],
    'lol-individual-sign-up' => [
        'sender' => 'Thanks for signing up to play League of Legends! We will try and fit you into a team as best we can. This is a summary of the form you submitted. A copy of this form will be forwarded to :recipient.',
        'recipient' => 'A new League of Legends individual player has signed up at :time from :domain.',
        'subject' => [
            'recipient' => 'A new League of Legends individual player has signed up',
            'sender' => 'Thanks for signing up to play League of Legends',
        ]
    ],
    'contact-us' => [
        'sender' => 'Thanks for filling out the :form form,  we will get back to you as soon as possible! This is a summary of the form you submitted. A copy of this form will be forwarded to :recipient.',
        'recipient' => 'A new response from the :form was submitted at :time from :domain.',
        'subject' => [
            'recipient' => 'A new response from the :form',
            'sender' => 'Thanks for filling out the :form form',
        ]
    ],
    'manage' => [
        'player' => [
            'new' => [
                'recipient' => 'You are now signed up as a player with :url',
                'sender' => 'Manager created a new player &#8220;:username&#8221; on :form at :time from :url. <a href="https://:url/app/manage/player/edit/:id">Edit this player</a>',
                'subject' => [
                    'recipient' => 'You have been added as a player on :url',
                    'sender' => 'A new player ":username" created on :url',
                ]
            ]
        ]
    ]
];
