<?php

namespace App\Http\Controllers\Frontend\Updates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\UpdateRecipients;
use Validator;

class UpdatesController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:update_recipients',
        ]);

        if ($validator->fails()) {
            return json_encode(['error' => $validator->errors()->all()]);
        }
        try {
            $update = new UpdateRecipients();
            $update->email = $request->input('email');
            $update->participate = filter_var($request->input('participate'), FILTER_VALIDATE_BOOLEAN);
            $update->save();
            return json_encode(['success' => trans('UpdatesController.store-success')]);
        } catch (\Exception $ex) {
            return json_encode(['error' => $ex->getMessage()]);
        }
    }

}
