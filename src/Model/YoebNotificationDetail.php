<?php
namespace Yoeb\Notifications\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YoebNotificationDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "title",
        "brief",
        "description",
        "image",
        "extra",
    ];

    protected $casts = [
        "extra" => "object",
    ];
}
