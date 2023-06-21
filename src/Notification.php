<?php
namespace Yoeb\Notifications;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Yoeb\Firebase\FBNotification;
use Yoeb\Notifications\Mail\YoebMail;
use Yoeb\Notifications\Model\YoebFcmId;
use Yoeb\Notifications\Model\YoebNotification;
use Yoeb\Notifications\Model\YoebNotificationDetail;

class Notification{

    protected static $title = null;
    protected static $userIds = [];
    protected static $brief = null;
    protected static $description = null;
    protected static $image = null;
    protected static $extra = null;
    protected static $priority = "HIGH";
    protected static $restrictedPackageName = null;
    protected static $channelId = null;
    protected static $icon = null;
    protected static $color = null;
    protected static $sound = null;
    protected static $tag = null;
    protected static $localOnly = false;
    protected static $notificationCount = 0;
    protected static $link = null;
    protected static $analyticsLabel = null;
    protected static $sendNotification = true;
    protected static $sendImageNotification = true;
    protected static $sendEmail = true;
    protected static $mailPrefix = null;
    protected static $mailView = null;
    protected static $mailExtra = null;
    protected static $addDb = true;

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

    public static function addUserId($userId)
    {
        self::$userIds[] = $userId;
        return (new static);
    }

    public static function userIds($userIds)
    {
        self::$userIds = array_merge($userIds, self::$userIds);
        return (new static);
    }

    public static function title($title)
    {
        self::$title = $title;
        return (new static);
    }

    public static function brief($brief)
    {
        self::$brief = $brief;
        return (new static);
    }

    public static function description($description)
    {
        self::$description = $description;
        return (new static);
    }

    public static function image($image)
    {
        self::$image = $image;
        return (new static);
    }

    public static function extra($extra)
    {
        self::$extra = $extra;
        return (new static);
    }

    public static function priority($priority)
    {
        self::$priority = $priority;
        return (new static);
    }

    public static function restrictedPackageName($restrictedPackageName)
    {
        self::$restrictedPackageName = $restrictedPackageName;
        return (new static);
    }

    public static function channelId($channelId)
    {
        self::$channelId = $channelId;
        return (new static);
    }

    public static function icon($icon)
    {
        self::$icon = $icon;
        return (new static);
    }

    public static function color($color)
    {
        self::$color = $color;
        return (new static);
    }

    public static function sound($sound)
    {
        self::$sound = $sound;
        return (new static);
    }

    public static function tag($tag)
    {
        self::$tag = $tag;
        return (new static);
    }

    public static function localOnly($localOnly)
    {
        self::$localOnly = $localOnly;
        return (new static);
    }

    public static function notificationCount($notificationCount)
    {
        self::$notificationCount = $notificationCount;
        return (new static);
    }

    public static function link($link)
    {
        self::$link = $link;
        return (new static);
    }

    public static function analyticsLabel($analyticsLabel)
    {
        self::$analyticsLabel = $analyticsLabel;
        return (new static);
    }

    public static function sendNotification($sendNotification = true)
    {
        self::$sendNotification = $sendNotification;
        return (new static);
    }

    public static function sendImageNotification($sendImageNotification)
    {
        self::$sendImageNotification = $sendImageNotification;
        return (new static);
    }

    public static function sendEmail($sendEmail = true)
    {
        self::$sendEmail = $sendEmail;
        return (new static);
    }

    public static function mailPrefix($mailPrefix)
    {
        self::$mailPrefix = $mailPrefix;
        return (new static);
    }

    public static function mailView($mailView)
    {
        self::$mailView = $mailView;
        return (new static);
    }

    public static function mailExtra($mailExtra)
    {
        self::$mailExtra = $mailExtra;
        return (new static);
    }

    public static function addDb($addDb = true)
    {
        self::$addDb = $addDb;
        return (new static);
    }


    // Notification Send
    public static function send(
    ){
        if(self::$addDb){
            $notificationDetail = YoebNotificationDetail::create([
                "title"         => self::$title,
                "brief"         => self::$brief,
                "description"   => self::$description,
                "image"         => self::$image,
                "extra"         => self::$extra,
            ]);
            if(empty($notificationDetail)){
                return false;
            }

            foreach (self::$userIds as $value) {
                YoebNotification::create([
                    "user_id"                   => $value,
                    "notification_detail_id"    => $notificationDetail->id,
                ]);
            }

        }

        if(self::$sendNotification){
            $tokens = YoebFcmId::whereIn("user_id", self::$userIds)->pluck("fcm_id");
            FBNotification::send(
                $tokens,
                self::$title,
                self::$brief,
                self::$sendImageNotification ? self::$image : null,
                self::$extra,
                self::$priority,
                self::$restrictedPackageName,
                self::$channelId,
                self::$icon,
                self::$color,
                self::$sound,
                self::$tag,
                self::$localOnly,
                self::$notificationCount,
                self::$link,
                self::$analyticsLabel,
            );
        }

        if(self::$sendEmail && Schema::hasColumn('users', 'email') && !empty(env("MAIL_USERNAME", null))){
            $emails = User::whereIn("id", self::$userIds)->pluck("email");
            foreach ($emails as $email) {
                Mail::to($email)->send(new YoebMail(self::$title, self::$brief, self::$image, self::$mailPrefix, !empty(self::$mailExtra) ? self::$mailExtra : self::$extra, self::$mailView));
            }
        }

        self::$title = null;
        self::$userIds = [];
        self::$brief = null;
        self::$description = null;
        self::$image = null;
        self::$extra = null;
        self::$priority = "HIGH";
        self::$restrictedPackageName = null;
        self::$channelId = null;
        self::$icon = null;
        self::$color = null;
        self::$sound = null;
        self::$tag = null;
        self::$localOnly = false;
        self::$notificationCount = 0;
        self::$link = null;
        self::$analyticsLabel = null;
        self::$sendNotification = true;
        self::$sendImageNotification = true;
        self::$sendEmail = true;
        self::$mailPrefix = null;
        self::$mailView = null;
        self::$mailExtra = null;
        self::$addDb = true;
    }



    protected static $startDate = null;
    protected static $endDate = null;
    protected static $paginate = 0;
    protected static $orderByDesc = null;
    protected static $orderBy = null;

    public static function start($date)
    {
        self::$startDate = $date;
        return (new static);
    }

    public static function end($date)
    {
        self::$endDate = $date;
        return (new static);
    }

    public static function paginate($paginate = 10)
    {
        self::$paginate = $paginate;
        return (new static);
    }

    public static function orderByDesc($orderByDesc = "id")
    {
        self::$orderByDesc = $orderByDesc;
        return (new static);
    }

    public static function orderBy($orderBy = "id")
    {
        self::$orderBy = $orderBy;
        return (new static);
    }

    public static function list($userId = null)
    {
        $notificationIds = YoebNotification::query();
        if(!empty($userId)){
            $notificationIds = $notificationIds->where("user_id", $userId);
        }
        $notificationIds = $notificationIds->pluck("notification_detail_id");
        $notificationIds->unique();

        $notificationDetail = YoebNotificationDetail::query();
        $notificationDetail = $notificationDetail->whereIn("id", $notificationIds);

        if(!empty(self::$startDate)){
            $notificationDetail = $notificationDetail->whereDate("created_at", ">", self::$startDate);
        }
        if(!empty(self::$endDate)){
            $notificationDetail = $notificationDetail->whereDate("created_at", "<", self::$endDate);
        }

        if(!empty(self::$orderByDesc)){
            $notificationDetail = $notificationDetail->orderByDesc(self::$orderByDesc);
        }

        if(!empty(self::$orderBy)){
            $notificationDetail = $notificationDetail->orderBy(self::$orderBy);
        }

        if(self::$paginate){
            $data = $notificationDetail->paginate(self::$paginate);
        }else{
            $data = $notificationDetail->get();
        }

        self::$startDate = null;
        self::$endDate = null;
        self::$paginate = 0;
        self::$orderByDesc = null;
        self::$orderBy = null;

        return $data;
    }


    public static function delete($id){
        $data = YoebNotificationDetail::where("id", $id)->first();
        YoebNotification::where("notification_detail_id", $data->id)->forceDelete();
        $data->forceDelete();
        return $data;
    }

    public static function softDelete($id){
        $data = YoebNotificationDetail::where("id", $id)->first();
        YoebNotification::where("notification_detail_id", $data->id)->delete();
        $data->delete();
        return $data;
    }

}

?>