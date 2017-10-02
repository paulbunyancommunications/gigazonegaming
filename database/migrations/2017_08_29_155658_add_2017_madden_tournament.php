<?php

use App\Models\Championship\Tournament;
use Illuminate\Database\Migrations\Migration;
use Pbc\Bandolier\Type\Numbers;


class Add2017MaddenTournament extends Migration
{
    protected $game = 'madden-nfl-18';
    protected $name = 'gigazone-gaming-2017-{{ name }}';
    protected $title = 'Gigazone Gaming Championship 2017 {{ title }} Tournament';
    protected $maxPlayers = 1;
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

        if(
            Schema::connection('mysql_champ')->hasTable('games') and
            Schema::connection('mysql_champ')->hasTable('tournaments')
        ) {
            $m = new Mustache_Engine();
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
                    ]];
                $tournament->sign_up_form = json_encode($form);

                // create tne form shortcode
                $shortCode = new \App\Helpers\Frontend\ShortCode();

                $tournament->sign_up_form_shortcode = $shortCode->generateTournamentSignUpFormShortCode([
                    'tournament_name' => $tournament->name,
                    'fields' => $form,
                    'sign-up-open' => $tournament->sign_up_open,
                    'sign-up-close' => $tournament->sign_up_close,
                ]);
                $tournament->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(
            Schema::connection('mysql_champ')->hasTable('games') and
            Schema::connection('mysql_champ')->hasTable('tournaments')
        ) {
            $m = new Mustache_Engine();
            $getGame = \App\Models\Championship\Game::where('name', $this->game)->first();
            if (Tournament::where('name', $m->render($this->name, $getGame))->exists()) {
                Tournament::where('name', $m->render($this->name, $getGame))->first()->delete();
            }
        }
    }
}
