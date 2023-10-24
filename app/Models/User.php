<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    use HasFactory;

	public function rounds(): BelongsToMany
    {
        return $this->belongsToMany(Round::class, 'contestants');
    }
}
