<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScoreRequest;
use App\Models\Championship\Score;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class ScoresController extends Controller
{

    /**
     * @return GuzzleClient
     */
    protected static function GuzzleClient()
    {
        return new GuzzleClient(['timeout' => 10]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game.score');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('game.score');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ScoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $score = new Score($request->all());
            $score->save();
            DB::commit();
            $message = trans('score.store', [
                'player' => $score->player->name,
                'tournament' => $score->tournament->name,
                'score' => $score->score
            ]);
            if ($request->ajax()) {
                return Response::json(
                    [
                        'success' => [$message],
                        'id' => [$score->id],
                        'redirect' => [action('Backend\Manage\ScoresController@edit', [$score->id])],
                        'model' => self::GuzzleClient()->get(route('api.manage.score.find', ['id', $score->id]))->json()
                    ],
                    200
                );
            }
            return redirect()->action('Backend\Manage\ScoresController@show', ['scores' => $score->id])
                ->with('success', $message);
        } catch (\Exception $ex) {
            DB::rollback();
            if ($request->ajax()) {
                return Response::json(['error' => $ex->getMessage()], 400);
            } else {
                return redirect()->back()
                    ->with('error', $ex->getMessage())
                    ->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Score $scores
     * @return \Illuminate\Http\Response
     */
    public function show(Score $scores)
    {
        return View::make('game.score')->with('score', $scores);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Score $scores
     * @return \Illuminate\Http\Response
     */
    public function edit(Score $scores)
    {
        return View::make('game.score')->with('score', $scores);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ScoreRequest $request
     * @param Score $scores
     * @return \Illuminate\Http\Response
     */
    public function update(ScoreRequest $request, Score $scores)
    {
        DB::beginTransaction();
        try {
            $scores->score = $request->input('score');
            $scores->save();
            DB::commit();
            if ($request->ajax()) {
                return Response::json(
                    [
                        'success' => [trans('score.update')],
                        'id' => [$scores->id],
                        'redirect' => [action('Backend\Manage\ScoresController@edit', [$scores->id])],
                        'model' => self::GuzzleClient()->get(route('api.manage.score.find', ['id', $scores->id]))->json()
                    ],
                    200
                );
            }
            return redirect()->action('Backend\Manage\ScoresController@edit', ['scores' => $scores->id])
                ->with('success', trans('score.update'));
        } catch (\Exception $ex) {
            DB::rollback();
            if ($request->ajax()) {
                return Response::json(['error' => $ex->getMessage()], 400);
            } else {
                return redirect()->back()
                    ->with('error', $ex->getMessage())
                    ->withInput();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Score $scores
     * @return \Illuminate\Http\Response
     * @internal param Score $scores
     */
    public function destroy(Score $scores)
    {
        //
    }
}
