<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
	protected $fillable = ['isbn', 'thumbnail', 'publish_year'];

    use HasFactory;

	public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

	public function tags(): BelongsToMany
	{
		return $this->belongsToMany(Tag::class);
	}

	public function authors(): BelongsToMany
	{
		return $this->belongsToMany(Author::class);
	}
}
