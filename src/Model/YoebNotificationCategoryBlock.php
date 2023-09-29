<?php

namespace Yoeb\Notifications\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoebNotificationCategoryBlock extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "database",
        "notification",
        "email",
    ];

    protected $casts = [
        "database"      => "array",
        "notification"  => "array",
        "email"         => "array",
    ];
}
