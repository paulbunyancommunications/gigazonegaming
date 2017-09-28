<?php

use App\Models\Championship\Tournament;
use Illuminate\Database\Migrations\Migration;
use Pbc\Bandolier\Type\Numbers;

class Add2016LeagueOfLegendsTournament extends Migration
{
    protected $game = 'league-of-legends';
    protected $name = 'gigazone-gaming-2016-{{ name }}';
    protected $title = 'Gigazone Gaming Championship 2016 {{ title }} Tournament';
    protected $maxPlayers = 5;
    protected $maxTeams = 32;
    protected $signUpOpen = "2016-05-01 16:00:00";
    protected $signUpClose = "2016-07-01 11:59:59";
    protected $occurring = "2016-11-30 12:00:00";
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
        $name = $m->render($this->name, $getGame);
        if (!Tournament::where('name', $name)->exists()) {
            $tournament = new Tournament();
            $tournament->name = $m->render($this->name, $getGame);
            $tournament->title = $m->render($this->title, $getGame);
            $tournament->max_players = $this->maxPlayers;
            $tournament->max_teams = $this->maxTeams;
            $tournament->game_id = $getGame->id;
            $tournament->sign_up_open = $this->signUpOpen;
            $tournament->sign_up_close = $this->signUpClose;
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
                    'required|uniqueWidth:mysql_champ.teams,=name,tournament_id>{{tournament_id}}',
                    'text',
                    ''
                ],
                'name' => [
                    'Team Captain',
                    'required',
                    'text',
                    ''
                ],
                'team-captain-lol-summoner-name' => [
                    'Team Captain LoL Summoner Name',
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
                'team-captain-phone' => [
                    'Team Captain Phone',
                    'required',
                    'tel',
                    ''
                ]
            ];

            // create rules for the other players
            for ($i = 1; $i < $this->maxPlayers; $i++) {
                $nameKey = 'teammate-' . Numbers::toWord($i) . '-lol-summoner-id';
                $nameRules = 'required|different:team-captain-lol-summoner-name';
                $emailKey = 'teammate-' . Numbers::toWord($i) . '-email-address';
                $emailRules = 'required|email|different:email';
                foreach (range(1, $this->maxPlayers - 1) as $dif) {
                    if ($dif === $i) {
                        continue;
                    }
                    $nameRules .= '|different:teammate-' . Numbers::toWord($dif) . '-lol-summoner-id';
                    $emailRules .= '|different:temmate-' . Numbers::toWord($dif) . '-email-address';
                }

                $form[$nameKey] = [\Pbc\Bandolier\Type\Strings::formatForTitle($nameKey), $nameRules, 'text', ''];
                $form[$emailKey] = [\Pbc\Bandolier\Type\Strings::formatForTitle($emailKey), $emailRules, 'email', ''];
            };
            $tournament->sign_up_form = json_encode($form);

            // short code generator
            $shortCode = new \App\Helpers\Frontend\ShortCode();

            $tournament->sign_up_form_shortcode = $shortCode->generateTournamentSignUpFormShortCode([
                'tournament_name' => $tournament->name,
                'fields' => $form,
                'sign-up-open' => $tournament->sign_up_open,
                'sign-up-close' => $tournament->sign_up_close,
                'headings' => 'Team Info|team-name,Team Captain|team-captain,Teammates|teammate-one-lol-summoner-id'
            ]);

            // finally save this tournament to the db
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
        $name = $m->render($this->name, $getGame);
        if (Tournament::where('name', $this->name)->exists()) {
            Tournament::where('name', $this->name)->first()->delete();
        }
    }
}
