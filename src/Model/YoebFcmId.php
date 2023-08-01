<?php
namespace Yoeb\Notifications\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YoebFcmId extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "user_id",
        "fcm_id",
    ];

    public function user_detail() {
        return $this->hasOne(\App\Models\User::class, "id", "user_id");
    }
}
