<?php
namespace Yoeb\Notifications\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YoebNotification extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "user_id",
        "notification_detail_id",
        "read_notification",
        "read_email",
    ];

    protected $casts = [
        "read_notification"     => "datetime",
        "read_email"            => "datetime",
    ];

    public function detail()
    {
        return $this->belongsTo(YoebNotificationDetail::class, 'notification_detail_id');
    }

    public function user_detail() {
        return $this->hasOne(\App\Models\User::class, "id", "user_id");
    }
}
