<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProjectStatus;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
