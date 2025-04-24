<?php

namespace App\Http\Controllers;

use App\Models\feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    //
    public function add(request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'rating' => 'required|integer',
            'comment' => 'required|string',
        ], [
            'name.required' => 'Name cannot be empty',
            'email.required' => 'Email cannot be empty',
            'email.email' => 'Email should be a valid email address',
            'rating.required' => 'Rating cannot be empty',
            'rating.integer' => 'Rating should be an integer',
            'comment.required' => 'Comment cannot be empty',
            'comment.string' => 'Comment should be a string',
        ]);

        feedback::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'rate' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'Feedback submitted successfully!');
    }
    public function change(request $request)
    {
        $id = $request->input('id');
        $feedback = feedback::findOrFail($id);
        $feedback->is_addressed = !$feedback->is_addressed;
        $feedback->save();
    }

    public function filter(request $request)
    {
        $state = $request->feedback;
        $filter = feedback::query();
        if ($request->has('feedback')) {
            $filter->where('is_addressed', $request->input('feedback'));
        } elseif ($request->has('feedback') && $request->input('feedback') == '') {
            $filter = feedback::query();
        }
        $data = $filter->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'rate' => $item->rate,
                'comment' => $item->comment,
                'is_addressed' => $item->is_addressed,
            ];
        });
        return response()->json($data);
    }

    public function download()
    {
        $fileName = 'feedback.csv';
        $headers = [
            'content-type' => 'text/csv',
            'content-disposition' => 'attachment; filename="' . $fileName . '"',
            'pragma' => 'no-cache',
            'cache-control' => 'must-revalidate,post-check=0,pre-check=0',
            'expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID',
                'Name',
                'Email',
                'Rating',
                'Comment',
                'Is Addressed',
                'Created At',
            ]);
            feedback::chunk(25, function ($feedback) use ($handle) {
                foreach ($feedback as $item) {
                    fputcsv($handle, [
                        $item->id,
                        $item->name,
                        $item->email,
                        $item->rate,
                        $item->comment,
                        $item->is_addressed ? 'Yes' : 'No',
                        $item->created_at,
                    ]);
                }
            });
            fclose($handle);
        }, 200, $headers);
    }
}
