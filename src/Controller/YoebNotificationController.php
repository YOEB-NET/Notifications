<?php
namespace Yoeb\Notifications\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yoeb\Notifications\Notification;

class YoebNotificationController {

    public function readEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            "image"                     => "required|string",
            "user_id"                   => "required|int",
            "notification_detail_id"    => "required|string",
        ]);

        $filds = $validator->valid();

        Notification::readEmailWithDetail($filds["user_id"], $filds["notification_detail_id"]);

        return redirect($filds["image"]);
    }

}

?>
