<?php

return [
    // Emergency rollback: set MM_ADMIN_SHELL=false and clear the config cache.
    // Employee, payroll-print and dedicated POS layouts keep their existing chrome.
    'admin_shell' => env('MM_ADMIN_SHELL', true),

    // Shown in the shell footer when set (for example "2026.10.1").
    'version' => env('MM_APP_VERSION'),

    // Optional help/support link shown in the shell footer.
    'support_url' => env('MM_SUPPORT_URL'),

    // Time zone for the footer server clock. The hotel business date helpers already use Asia/Dhaka.
    'timezone' => env('MM_UI_TIMEZONE', 'Asia/Dhaka'),
];
