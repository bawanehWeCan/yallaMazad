<?php
namespace App\Traits;

trait NotificationTrait
{

    public function send($content, $title, $token, $many = false)
    {

        $msg = array
            (
            'body' => $content,
            'title' => $title,
            // 'route_id'=>$route_id,
            // 'type'=>$type,
            'receiver' => 'Aya',
            'sound' => 'mySound', /*Default sound*/
        );
        if ($many) {
            $fields = array
                (
                'registration_ids' => $token,
                'notification' => $msg,

            );
        } else {
            $fields = array
                (
                'to' => $token,
                'notification' => $msg,

            );
        }

        $headers = array
            (
            'Authorization: key=' . env('FIREBASE_API_KEY'),
            'Content-Type: application/json',
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //dd($result);
        curl_close($ch);

        return true;
    }

    public function adNotificationSend($id, $status, $title, $content, $token)
    {
        $msg['title'] = $title;
        $msg['body'] = $content;
        $data = [
            'id' => $id,
            'advertisement' => $status,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        $fields = array
            (
            'to' => $token,
            'notification' => $msg,
            'data' => $data,
        );

        $headers = array
            (
            'Authorization: key=' . env('FIREBASE_API_KEY'),
            'Content-Type: application/json',
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        // dd(env('FIREBASE_API_KEY'));
        curl_close($ch);

        return true;
    }

    public function addNewNotificationSend($content, $token)
    {
        $msg['title'] = 'User Notification';
        $msg['body'] = $content;
        $data = [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        $fields = array
            (
            'to' => $token,
            'notification' => $msg,
            'data' => $data,
        );

        $headers = array
            (
            'Authorization: key=' . env('FIREBASE_API_KEY'),
            'Content-Type: application/json',
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        //dd($result);
        curl_close($ch);

        return true;
    }
}
