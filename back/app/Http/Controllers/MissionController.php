<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\MissionRequest;
use App\Http\Requests\NewMissionsAvailableRequest;
use App\Http\Resources\MissionResource;
use App\Mail\NewMissionsAvailableMail;
use App\Models\Address;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class MissionController extends Controller
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
        $this->authorize('index', Mission::class);

        $this->validate(request(), [
            'page'      => 'integer|min:1',
            'isLocked'  => 'string',
            'from'      => 'date|string',
            'to'        => 'date|string',
        ]);

        $missions = Mission::filtered(
            request()->isLocked,
            [request()->minDate, request()->maxDate]
        )->withCount(['applications as accepted_applications_count' => function($query) {
            return $query->where('status', 'accepted');
        }])->with('project')->orderByDesc('start_at')->paginate(10);

        return MissionResource::collection($missions);
    }

    /**
     * Display a listing of the available missions.
     *
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function indexAvailable()
    {
        $this->authorize('index-available', Mission::class);

        $missions = Mission::available()->sortBy('start_at');

        return response()->json(MissionResource::collection(
            $missions->load('address', 'project', 'applications', 'user')
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MissionRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(MissionRequest $request)
    {
        $this->authorize('store', Mission::class);

        $mission_request = $request->except([
            'street', 'zip_code', 'city'
        ]);
        $address_request = $request->only(['address'])['address'];

        $mission = Mission::create($mission_request);
        $address = Address::create($address_request);
        $address->mission()->save($mission);

        return response()->json(new MissionResource($mission), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param $mission_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($mission_id)
    {
        $mission = Mission::withApplicationsCounts()->findOrFail($mission_id);
        $this->authorize('show', $mission);

        $mission->load([
            'address',
            'project',
            'user',
            'contact',
            'project.convention',
            'project.convention.rates',
            'applications',
            'applications.bills',
        ]);

        return response()->json(MissionResource::make($mission));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MissionRequest $request
     * @param $mission_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(MissionRequest $request, $mission_id)
    {
        $mission = Mission::findOrFail($mission_id);
        $this->authorize('update', $mission);

        $mission_request = $request->except([
            'street', 'zip_code', 'city'
        ]);
        $address_request = $request->only(['address'])['address'];

        $mission->update($mission_request);
        $mission->address()->update($address_request);

        return response()->json(MissionResource::make($mission), JsonResponse::HTTP_CREATED);
    }

    /**
     * Update the mission lock status resource in storage.
     *
     * @param MissionRequest $request
     * @param $mission_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateLock(MissionRequest $request, $mission_id)
    {
        $mission = Mission::findOrFail($mission_id);
        $this->authorize('update-lock', $mission);

        $mission->update($request->only(['is_locked']));

        return response()->json(MissionResource::make($mission), JsonResponse::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $mission_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($mission_id)
    {
        $mission = Mission::findOrFail($mission_id);
        $this->authorize('destroy', $mission);

        $mission->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Send pre-mission email
     *
     * @param EmailRequest $request
     * @param $mission_id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function sendPreMissionEmail(EmailRequest $request, $mission_id)
    {
        /** @var Mission $mission */
        $mission = Mission::findOrFail($mission_id);
        $this->authorize('sendEmail', $mission);

        $mission->sendPreMissionNotification(
            $request['subject'],
            $request['content']
        );

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Send email for new missions available
     * This include only public missions.
     *
     * @param NewMissionsAvailableRequest $request
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function notifyAvailability(NewMissionsAvailableRequest $request)
    {
        $this->authorize('notify-availability', Mission::class);

        $missions = collect();

        foreach (request()->missions as $mission_id) {
            $mission = Mission::findOrFail($mission_id);

            if (! $mission->isPrivate) {
                $missions->push(Mission::findOrFail($mission_id));
            }
        }

        $this->sendNewMissionsAvailableMail($missions);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param Collection $missions
     */
    private function sendNewMissionsAvailableMail(Collection $missions)
    {
        try {
            $users = User::active()->filter(function (User $user) {
                return $user->hasVerifiedEmail() && $user->preference->on_new_mission;
            });

            Mail::to($users)->send(new NewMissionsAvailableMail($missions));
        } catch (\Exception $exception) {}
    }
}
