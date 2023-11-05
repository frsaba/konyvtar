<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['language_id', 'tag_id', 'name', 'description'];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
