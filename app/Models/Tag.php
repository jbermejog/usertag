<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag'
    ];

    /**
     * The user that belong to the tag.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
