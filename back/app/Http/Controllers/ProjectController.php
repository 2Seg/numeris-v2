<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index()
    {
        $this->authorize('index', Project::class);

        $this->validate(request(), [
            'page'      => 'integer|min:1',
            'status'    => 'string|in:' . implode(',', Project::steps()),
            'from'      => 'date|string',
            'to'        => 'date|string',
        ]);

        if (request()->page) {
            return ProjectResource::collection(
                Project::filtered(
                    request()->step,
                    [request()->minDate, request()->maxDate]
                )->withCount('missions', 'users')->with('client')->orderBy('start_at', 'desc')->paginate(10)
            );
        } else {
            return response()->json(
                ProjectResource::collection(Project::all()->sortByDesc('start_at')
            ));
        }
    }

    /**
     * Display a listing of the steps with their translation.
     *
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function indexStep()
    {
        $this->authorize('index-step', Project::class);

        return response()->json(Project::stepTranslations());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ProjectRequest $request)
    {
        $this->authorize('store', Project::class);

        $project = Project::create(array_merge($request->all(), ['step' => Project::HIRING]));

        return response()->json(new ProjectResource($project), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param $project_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($project_id)
    {
        $project = Project::withCount('missions', 'users')
            ->findOrFail($project_id);
        $this->authorize('show', $project);

        $project->load(['client', 'convention', 'missions', 'invoice']);

        return response()->json(ProjectResource::make($project));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectRequest $request
     * @param $project_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ProjectRequest $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('update', $project);

        // Add eventual mission users to project users if project was public
        // and is modified to private
        if (! $project->is_private && $request->get('is_private')) {
            foreach($project->missions as $mission) {
                foreach($mission->applications as $application) {
                    $project->addUser($application->user);
                }
            }
        }

        $project->update($request->all());

        return response()->json(ProjectResource::make($project), JsonResponse::HTTP_CREATED);
    }

    /**
     * Update step.
     *
     * @param ProjectRequest $request
     * @param $project_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateStep(ProjectRequest $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('update-step', $project);

        $project->update($request->only('step'));

        return response()->json(ProjectResource::make($project), JsonResponse::HTTP_CREATED);
    }

    /**
     * Update payment (when the project has been paid).
     *
     * @param ProjectRequest $request
     * @param $project_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updatePayment(ProjectRequest $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('update-payment', $project);

        $project->update($request->only('money_received_at'));

        return response()->json(ProjectResource::make($project), JsonResponse::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $project_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('destroy', $project);

        // DISCLAIMER: The following line removes the project as well as its
        // related missions
        $project->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
