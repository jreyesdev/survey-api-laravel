<?php

namespace App\Http\Controllers;

use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\SurveyResource
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Survey\StoreSurveyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyRequest $request): SurveyResource
    {
        $survey = Survey::create($request->validated());
        return SurveyResource::make($survey);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey, Request $request): SurveyResource
    {
        $user = $request->user();
        if ($survey->user_id !== $user->id) {
            return abort(403, 'Unauthorized action');
        }
        return SurveyResource::make($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Survey\UpdateSurveyRequest  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveyRequest $request, Survey $survey): SurveyResource
    {
        $survey->update($request->validated());
        return SurveyResource::make($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey, Request $request): Response
    {
        $user = $request->user();
        if ($survey->user_id !== $user->id) {
            return abort(403, 'Unauthorized action');
        }
        $survey->delete();
        return response('', 204);
    }
}
