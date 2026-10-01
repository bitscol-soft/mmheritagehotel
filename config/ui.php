<?php

return [
    // Emergency rollback: set MM_ADMIN_SHELL=false and clear the config cache.
    // Employee, payroll-print and dedicated POS layouts keep their existing chrome.
    'admin_shell' => env('MM_ADMIN_SHELL', true),
];
