<?php

// use Module\HRM\Models\News\Notice;

function news_notifications()
    {
        $user_id = auth()->id();

        // $notifications = Notice::where('publish_at', '<=', fdate(now(), 'd-m-Y H:i:s'))
        // ->where('expire_at', '>', fdate(now(), 'd-m-Y H:i:s'))->whereDoesntHave('all_views', function ($q) use($user_id) {
        //     $q->where('user_id', $user_id);
        // })->count();

        // return $notifications;
    }
