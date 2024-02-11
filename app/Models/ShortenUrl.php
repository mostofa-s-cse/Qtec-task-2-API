<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortenUrl extends Model
{
    use HasFactory;

    protected $fillable = ['long_url', 'short_url', 'click_count'];
}
