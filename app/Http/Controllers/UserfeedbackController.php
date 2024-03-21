<?php

namespace App\Http\Controllers;

use App\Models\userfeedback;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class UserfeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data =  $request->json()->all();

        $validator = Validator::make($data['attributes'], [
            'feedback' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please enter your feedback.',
                'error' => $validator->errors(),
            ], 422);
        } else {
            $user = Auth::user();

            $input = $data;
            $input['user_id'] = $user->id;
            $feedback = userfeedback::create($input);

            return response()->json([
                'message' => 'Feedback submitted Successfully.',
                'data' => $feedback,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\userfeedback  $userfeedback
     * @return \Illuminate\Http\Response
     */
    public function show(userfeedback $userfeedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\userfeedback  $userfeedback
     * @return \Illuminate\Http\Response
     */
    public function edit(userfeedback $userfeedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\userfeedback  $userfeedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, userfeedback $userfeedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\userfeedback  $userfeedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(userfeedback $userfeedback)
    {
        //
    }
}
