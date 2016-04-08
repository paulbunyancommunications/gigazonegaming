<?php

namespace App\Http\Controllers\Frontend\Updates;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\UpdateRecipients;
use Illuminate\Http\Request;
use Validator;

class UpdatesController extends Controller
{

    public function store(Request $request)
    {
        $messages = array(
            'email.unique' => trans('UpdatesController.email-unique'),
        );
        $rules = [
            'email' => 'required|email|unique:update_recipients',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return json_encode(['error' => $validator->errors()->all()]);
        }
        try {
            $update = new UpdateRecipients();
            $update->email = $request->input('email');
            $update->participate = $request->input('participate');
            $update->save();
            return json_encode(['success' => trans('UpdatesController.store-success')]);
            // @codeCoverageIgnoreStart
        } catch (\Exception $ex) {
            return json_encode(['error' => $ex->getMessage()]);
            // @codeCoverageIgnoreEnd
        }
    }

}
