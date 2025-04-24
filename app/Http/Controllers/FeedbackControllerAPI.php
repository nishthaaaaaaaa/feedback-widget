<?php

namespace App\Http\Controllers;

use App\Models\feedback;
use Illuminate\Http\Request;

class FeedbackControllerAPI extends Controller
{
    //
    public function add(request $request)
    {
        $feedback = new feedback();
        $feedback->name = $request->input('name');
        $feedback->email = $request->input('email');
        $feedback->rate = $request->input('rate');
        $feedback->comment = $request->input('comment');
        $feedback->save();

        if ($feedback->save()) {
            return ["result" => "Feedback added successfully"];
        } else {
            return ["result" => "Feedback not added"];
        }
    }
    public function show()
    {
        $feedback = feedback::all();
        if ($feedback) {
            return ["result" => "Feedback retrieved successfully", "data" => $feedback];
        } else {
            return ["result" => "Feedback not found"];
        }
    }
}
