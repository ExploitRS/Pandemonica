<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
    ];

    public function tasks(): belongsToMany
    {
        return $this->belongsToMany(Task::class);
    }
}
