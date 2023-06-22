<?php
namespace Yoeb\Notifications\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yoeb\Notifications\Model\YoebNotification;

class YoebNotificationController {

    public function readEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            "image"                     => "required|string",
            "user_id"                   => "required|int",
            "notification_detail_id"    => "required|string",
        ]);

        $filds = $validator->valid();

        YoebNotification::where("user_id", $filds["user_id"])->where("notification_detail_id", $filds["notification_detail_id"])->update([
            "read_email" => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return redirect($filds["image"]);
    }
    
}

?>
