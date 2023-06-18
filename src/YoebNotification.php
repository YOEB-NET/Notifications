<?php
namespace Yoeb\Notifications;

use Yoeb\Firebase\FBNotification;
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
    ){
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

        foreach ($userIds as $key => $value) {
            ModelYoebNotification::create([
                "user_id"                   => $userIds,
                "notification_detail_id"    => $notificationDetail->id,
            ]);
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

        if($sendEmail){
            $email = YoebFcmId::whereIn("user_id", $userIds)->pluck("email");
            
            
        }
        
    }

}

?>