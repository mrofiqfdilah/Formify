<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\allowed_domains;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Forms;
use Illuminate\Support\Facades\Auth;
use App\Models\Questions;
use App\Models\Responses;
use App\Models\User;
use App\Models\Answer;

class ResponseController extends Controller
{
    public function submit_response(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
        'answers' => 'array',
        'answers.*.question_id' => 'required|exists:questions,id',
        'answers.*.value' => 'required_if:answers.*.question_id,true'
        ]);

        if($validator->fails())
        {
            return response()->json([
            'message' => 'Invalid field',
            'errors' => $validator->errors()
            ], 422);
        }

        $formslug = Forms::where('slug', $slug)->first();

        if(!$formslug)
        {
            return response()->json([
            'message' => 'Form not found'
            ], 404);
        }

        $allowedDomains = Allowed_domains::where('form_id', $formslug->id)->pluck('domain');
        $userEmailDomain = explode('@', Auth::user()->email)[1];

        if (!$allowedDomains->contains($userEmailDomain)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if($formslug->limit_one_response)
        {
            $limited = Responses::where('form_id', $formslug->id)->where('user_id', Auth::id())->first();

            if($limited)
            {
                return response()->json([
                'message' => 'You can not submit form twice'
                ], 422);
            }
        }

        $response = new Responses;
        $response->form_id = $formslug->id;
        $response->user_id = Auth::id();
        $response->date = now();
        $response->save();

       foreach($request->answers as $answers)
       Answer::create([
       'response_id' => $response->id,
       'question_id' => $answers['question_id'],
       'value' => $answers['value']
       ]);

       return response()->json([
    'message' => 'Submit response success'
       ], 200);
    }

    public function all_response(Request $request, $slug)
    {
        $form = Forms::where('slug', $slug)->first();

        if (!$form) 
        {
            return response()->json([
                'message' => 'Form not found'
            ], 404);
        }

        if ($form->creator_id !== Auth::id())
        {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        $responses = Responses::where('form_id', $form->id)->with('user', 'answers.question')->get();

        $formattedResponses = $responses->map(function ($response) {
            $formattedAnswers = [];
            foreach ($response->answers as $answer) {
                $formattedAnswers[$answer->question->name] = $answer->value;
            }
            return [
                'date' => $response->date,
                'user' => [
                    'id' => $response->user->id,
                    'name' => $response->user->name,
                    'email' => $response->user->email,
                    'email_verified_at' => $response->user->email_verified_at
                ],
                'answers' => $formattedAnswers
            ];
        });

        return response()->json([
            'message' => 'Get responses success',
            'responses' => $formattedResponses
        ], 200);
    }


}
