<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convention extends Model
{
    protected $fillable = [
        'client_id',
        'name',
    ];

    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
