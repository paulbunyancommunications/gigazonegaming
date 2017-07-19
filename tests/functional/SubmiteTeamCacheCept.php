<?php 
$I = new FunctionalTester($scenario);

exec('php artisan test:fill');

$I->wantTo('Click the Submit Cache button and assert that there is the correct data cached');

$I->selectOption('#Tournament', 'gigazone-gaming-2016-league-of-legends');
$I->selectOption('#Team', 'Team Awesome');
$I->selectOption('#Team-1', 'Team Awesome');
$I->selectOption('#Color', 'Red');
$I->selectOption('#Color-1', 'Blue');
$I->click('Submit');



exec('php artisan migrate:refresh');