<?php

use App\Models\Championship\Tournament;
use Illuminate\Database\Migrations\Migration;
use Pbc\Bandolier\Type\Numbers;

class Add2017OverwatchTournament extends Migration
{
    protected $game = 'overwatch';
    protected $name = 'gigazone-gaming-2017-{{ name }}';
    protected $title = 'Gigazone Gaming Championship 2017 {{ title }} Tournament';
    protected $maxPlayers = 3;
    protected $maxTeams = 32;
    protected $signUpOpen = "2017-09-05 16:00:00";
    protected $signUpClose = "2017-09-28 11:59:59";
    protected $occurring = "2017-09-30 12:00:00";
    protected $newLine = ',';
    protected $delimiter = '|';


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $m = new Mustache_Engine();
        $slug = new \Cocur\Slugify\Slugify();
        $getGame = \App\Models\Championship\Game::where('name', $this->game)->first();
        if (!Tournament::where('name', $m->render($this->name, $getGame))->exists()) {
            $tournament = new Tournament();
            $tournament->name = $m->render($this->name, $getGame);
            $tournament->title = $m->render($this->title, $getGame);
            $tournament->max_players = $this->maxPlayers;
            $tournament->max_teams = $this->maxTeams;
            $tournament->game_id = $getGame->id;
            $tournament->sign_up_open = $this->signUpOpen;
            $tournament->sign_up_close = $this->signUpClose;
            $tournament->occurring = $this->occurring;
            $tournament->occurring = $this->occurring;

            // create store for form fields
            $form = [
                'update-recipient' => ['update-recipient', '', 'hidden', 'yes'],
                'participate' => ['participate', '', 'hidden', 'yes'],
                'tournament' => [
                    'tournament',
                    'required|exists:mysql_champ.tournaments,name',
                    'hidden',
                    $tournament->name
                ],
                'team-name' => [
                    'Team Name',
                    'required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##',
                    'text',
                    ''
                ],
                'name' => [
                    'Team Captain',
                    'required',
                    'text',
                    ''
                ],
                'email' => [
                    'Team Captain Email',
                    'required|email',
                    'email',
                    ''
                ],
                'phone' => [
                    'Team Captain Phone',
                    'required',
                    'tel',
                    ''
                ]
            ];

            // create rules for the other players
            for ($i = 1; $i < $this->maxPlayers; $i++) {
                $nameKey = 'teammate ' . Numbers::toWord($i) . ' name';
                $nameRules = 'required|different:name';
                $emailKey = 'teammate ' . Numbers::toWord($i) . ' email';
                $emailRules = 'required|email|different:email';
                foreach (range(1, $this->maxPlayers - 1) as $dif) {
                    if ($dif === $i) {
                        continue;
                    }
                    $nameRules .= '|different:teammate-' . Numbers::toWord($dif) . '-name';
                    $emailRules .= '|different:teammate-' . Numbers::toWord($dif) . '-email';
                }

                $form[$slug->slugify($nameKey)] = [\Pbc\Bandolier\Type\Strings::formatForTitle($nameKey), $nameRules, 'text', ''];
                $form[$slug->slugify($emailKey)] = [\Pbc\Bandolier\Type\Strings::formatForTitle($emailKey), $emailRules, 'email', ''];
            };
            $tournament->sign_up_form = json_encode($form);

            // Create shortcode for wordpress from the form fields
            //[build-form name="overwatch-three-v-three-sign-up" new_line="," delimiter="|", expires="",
            // questions="tournament|hidden|gigazone-gaming-2017-overwatch,Team Name,update-recipient|hidden|yes,participate|hidden|yes,Team Captain,Team Captain Overwatch Player Name,Team Captain Email Address|email,Team Captain Phone|tel,Teammate One Overwatch Player Name,Teammate One Email Address|email,Teammate Two Overwatch Player Name,Teammate Two Email Address|email"
            // inputs="team-captain|name,team-captain-email-address|email,team-captain-phone|phone,teammate-one-overwatch-player-name|player-one-name,teammate-one-email-address|player-one-email,teammate-two-overwatch-player-name|player-two-name,teammate-two-email-address|player-two-email"
            // headings="Team Info|team-name,Team Captain|team-captain,Team Members|teammate-one-overwatch-player-name"]
            $shortcode = new \App\Helpers\Frontend\ShortCode();
            $tournament->sign_up_form_shortcode = $shortcode->generateTournamentSignUpFormShortCode([
                'tournament-name' => $tournament->name,
                'fields' => $form,
                'sign-up-open' => $tournament->sign_up_open,
                'sign-up-close' => $tournament->sign_up_close,
            ]);

                // finally save this tournament to the db
            $tournament->update();

                // replace the id from above in the form array
            $tournament->sign_up_form = str_replace('##id##', $tournament->id, $tournament->sign_up_form);

            $tournament->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $m = new Mustache_Engine();
        $slug = new \Cocur\Slugify\Slugify();
        $getGame = \App\Models\Championship\Game::where('name', $this->game)->first();
        if (Tournament::where('name', $m->render($this->name, $getGame))->exists()) {
            Tournament::where('name', $m->render($this->name, $getGame))->first()->delete();
        }
    }
}
