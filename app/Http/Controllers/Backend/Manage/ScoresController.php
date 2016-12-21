<?php

namespace App\Http\Controllers\Backend\Manage;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Http\Requests\ScoreRequest;
use App\Models\Championship\Score;

class ScoresController extends Controller
{
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
     * @param  \App\Http\Requests\ScoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $score = new Score($request->all());
            $score->save();
            DB::commit();
            return redirect()->action('Backend\Manage\ScoresController@show', ['scores' => $score->id])
                ->with('success','Score created successfully!');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()
                ->with('error', $ex->getMessage())
                ->withInput();
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
     * @param  \Illuminate\Http\Request $request
     * @param Score $scores
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Score $scores)
    {
        //
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
