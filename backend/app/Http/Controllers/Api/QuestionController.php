<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Forms;
use Illuminate\Support\Facades\Auth;
use App\Models\Questions;

class QuestionController extends Controller
{
    public function create_question(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'choice_type' => 'required|in:short answer,paragraph,date,multiple choice,dropdown,checkboxes',
            'choices' => 'required_if:choice_type,in,multiple choice,dropdown,checkboxes'
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

        if($formslug->creator_id !== Auth::id()){
            return response()->json([
            'message' => 'Forbidden access'
            ], 403);
        }

        $choices = null;

        if($request->choice_type === 'multiple choice' || $request->choice_type === 'dropdown' || $request->choice_type === 'checkboxes'){
            $choices = implode(',',$request->choices);
        }

        $question = Questions::create([
            'name' => $request->name,
            'choice_type' => $request->choice_type,
            'choices' => $choices,
            'is_required' => $request->is_required,
            'form_id' => $formslug->id
        ]);
    
        return response()->json([
            'message' => 'Add question success',
            'question' => $question
        ], 200);
    }

    public function delete_question(Request $request, $slug , $id)
    {
        
        $formslug = Forms::where('slug', $slug)->first();

        if(!$formslug)
        {
            return response()->json([
            'message' => 'Form not found'
            ], 404);
        }

        $questionid = Questions::where('id', $id)->first();

        if(!$questionid)
        {
            return response()->json([
            'message' => 'Question not found'
            ], 404); 
        }

        if($formslug->creator_id !== Auth::id()){
            return response()->json([
            'message' => 'Forbidden access'
            ], 403);
        }

        $questionid->delete();

        return response()->json([
        'message' => 'Remove question success'
        ], 200);

    }
    
}
