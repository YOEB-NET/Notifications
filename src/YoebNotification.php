<?php
namespace Yoeb\Notifications;

use App\Mail\test;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Yoeb\Firebase\FBNotification;
use Yoeb\Notifications\Mail\YoebMail;
use Yoeb\Notifications\Model\YoebFcmId;
use Yoeb\Notifications\Model\YoebNotification as ModelYoebNotification;
use Yoeb\Notifications\Model\YoebNotificationDetail;

class YoebNotification {

    // Firebase Cloud Message ID
    public static function addFcmId($userId, $fcmId){
        $data = YoebFcmId::create([
            "user_id"   => $userId,
            "fcm_id"    => $fcmId,
        ]);
        return $data;
    }

    public static function listFcmId($userId = null){
        $data = YoebFcmId::query();
        if(!empty($userId)){
            $data = $data->where("user_id", $userId);
        }
        $data = $data->get();
        return $data;
    }

    public static function updateFcmId($id, $fcmId){
        $data = YoebFcmId::where("id", $id)->update([
            "fcm_id"    => $fcmId,
        ]);
        return $data;
    }

    public static function deleteFcmId($id){
        $data = YoebFcmId::where("id", $id)->forceDelete();
        return $data;
    }

    public static function softDeleteFcmId($id){
        $data = YoebFcmId::where("id", $id)->delete();
        return $data;
    }


    // Notification Send
    public static function send(
        $userIds, 
        $title = null,
        $brief = null,
        $description = null,
        $image = null,
        $extra = null,
        $priority = "HIGH",
        $restrictedPackageName = null,
        $channelId = null,
        $icon = null,
        $color = null,
        $sound = null,
        $tag = null,
        $localOnly = false,
        $notificationCount = 0,
        $link = null,
        $analyticsLabel = null,
        $sendNotification = true,
        $sendImageNotification = true,
        $sendEmail = true,
        $mailPrefix = null,
        $mailView = null,
        $mailExtra = null,
        $addDb = true,
    ){
        if($addDb){
            $notificationDetail = YoebNotificationDetail::create([
                "title"         => $title,
                "brief"         => $brief,
                "description"   => $description,
                "image"         => $image,
                "extra"         => $extra,
            ]);
            if(empty($notificationDetail)){
                return false;
            }
    
            foreach ($userIds as $value) {
                ModelYoebNotification::create([
                    "user_id"                   => $value,
                    "notification_detail_id"    => $notificationDetail->id,
                ]);
            }
            
        }

        if($sendNotification){
            $tokens = YoebFcmId::whereIn("user_id", $userIds)->pluck("fcm_id");
            FBNotification::send(
                $tokens,
                $title,
                $brief,
                $sendImageNotification ? $image : null,
                $extra,
                $priority,
                $restrictedPackageName,
                $channelId,
                $icon,
                $color,
                $sound,
                $tag,
                $localOnly,
                $notificationCount,
                $link,
                $analyticsLabel,
            );
        }

        if($sendEmail && Schema::hasColumn('users', 'email')){
            $emails = YoebFcmId::whereIn("user_id", $userIds)->pluck("email");
            foreach ($emails as $email) {
                Mail::to($email)->send(new YoebMail($title, $brief, $image, $mailPrefix, !empty($mailExtra) ? $mailExtra : $extra, $mailView));
            }
        }

    }

}

?>