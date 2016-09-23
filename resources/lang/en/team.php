<?php
return [
    'player_already_exists' => 'It looks like you might have registered as a player before (:email). '. link_to_action('Auth\\AuthController@login', 'please click here to log into your player account.'),
];
