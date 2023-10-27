<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translation extends Model
{
    use HasFactory;

	protected $fillable = ['title', 'description', 'book_id', 'language_id'];

	public function tags(): BelongsTo
	{
		return $this->belongsTo(Book::class);
	}
}
