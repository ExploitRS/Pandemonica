<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
    ];

    protected $dates = [
        'due_date',
    ];

    public function categories(): belongToMany
    {
        return $this->belongsToMany(Category::class);

    }
}
