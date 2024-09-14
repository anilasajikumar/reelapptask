<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{
    use HasFactory;

    protected $fillable = ['file_id',
        'title',
        'original_filename',
        'file_path',
        'mime_type',
        'size',];
}
