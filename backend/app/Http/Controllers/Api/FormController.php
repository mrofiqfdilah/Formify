<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Forms;
use App\Models\Allowed_domains;
use App\Models\Questions;

class FormController extends Controller
{
    public function create_form(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required|unique:forms',
        'allowed_domains' => 'array'
        ]);

        if($validator->fails())
        {
            return response()->json([
            'message' => 'Invalid field',
            'errors' => $validator->errors()
            ], 422);
        }

        $form = new Forms;
        $form->name = $request->name;
        $form->slug = $request->slug;
        $form->description = $request->description;
        $form->limit_one_response = $request->limit_one_response;
        $form->creator_id = Auth::id();
        $form->save();

        foreach($request->allowed_domains as $loopdomain)
        Allowed_domains::create([
        'form_id' => $form->id,
        'domain' => $loopdomain
        ]);

        return response()->json([
        'message' => 'Create form success',
        'form' => [
            'name' => $form->name,
            'slug' => $form->slug,
            'description' => $form->description,
            'limit_one_response' => $form->limit_one_response,
            'creator_id' => $form->creator_id,
            'id' => $form->id
        ],
        ], 200);
    }

    public function all_form(Request $request)
    {
        $userlogin = Auth::user();

        $form = Forms::where('creator_id', $userlogin->id)->get();

        return response()->json([
        'message' => 'Get all forms success',
        'form' => $form
        ], 200);
    }

    public function detail_form(Request $reques, $slug)
    {
        $formslug = Forms::where('slug', $slug)->first();

        if(!$formslug)
        {
            return response()->json([
            'message' => 'Form not found'
            ], 404);
        }

        $domain = Allowed_domains::where('form_id', $formslug->id)->pluck('domain');

        $question = Questions::where('form_id', $formslug->id)->get();

        $detailform = [
            'id' => $formslug->id,
            'name' => $formslug->name,
            'slug' => $formslug->slug,
            'description' => $formslug->description,
            'limit_one_response' => $formslug->limit_one_response,
            'creator_id' => $formslug->creator_id,
            'allowed_domains' => $domain,
            'questions' => $question
        ];
        
        return response()->json([
        'message' => 'Get form success',
        'form' => $detailform    
        ], 200);
    }
}
