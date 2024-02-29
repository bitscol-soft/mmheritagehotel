<?php

    use Module\HRM\Models\News\TaskNotification;
    function task_notifications()
    {
        return $notifications = TaskNotification::all();
    }
