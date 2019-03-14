<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use OnEventsTrait;

    protected $fillable = [
        // One-to-One relations
        'address_id',
        'project_id',

        // Attributes
        'title',
        'description',
        'start_at',
        'duration',
        'capacity',
    ];

    protected $hidden = [
        'address',
        'client',
        'project',
        'applications',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public static function findByProject($project_id)
    {
        return Project::find($project_id)->missions;
    }

    public static function findByProjectName($project_name)
    {
        return Project::findByName($project_name)->missions;
    }

    public function client()
    {
        return $this->project->client();
    }

    public static function findByClient($client_id)
    {
        return Client::find($client_id)->missions;
    }

    public static function findByClientName($client_name)
    {
        return Client::findByName($client_name)->missions;
    }

    /**
     * To be realised just after an mission is deleted
     */
    public static function onDeleted(self $mission)
    {
        // Delete all related models
        $mission->address()->delete();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function waitingApplications()
    {
        return $this->applications()
            ->where('status','=', Application::WAITING);
    }

    public function acceptedApplications()
    {
        return $this->applications()
            ->where('status','=', Application::ACCEPTED);
    }

    public function refusedApplications()
    {
        return $this->applications()
            ->where('status','=', Application::REFUSED);
    }
}