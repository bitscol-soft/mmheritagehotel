<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $guarded = ['id'];

    /**
     * The dashboard blades read these keys straight off the collection
     * (`$settings->where('key', ...)->first()->value`), so a deployment that
     * never saved them took the whole dashboard down (docs/BUGS.md #68). The
     * defaults are supplied in memory instead of writing rows.
     */
    public const DASHBOARD_DEFAULTS = [
        'dashboard'                     => '1',
        'visible_booking_ui_dashboard'  => '0',
        'visible_weather_dashboard'     => '0',
    ];

    /**
     * A single settings row by key. A fresh deployment may not have saved the row
     * the screen reads yet, and the views do `$systemSetting->value != null ? ... :
     * 'Reference'` - handing them an empty, unsaved model keeps that fallback
     * working instead of 500ing on a null (docs/BUGS.md #73).
     */
    public static function row(string $key): self
    {
        return static::where('key', $key)->first() ?? new static(['key' => $key]);
    }

    public static function withDashboardDefaults()
    {
        $settings = static::get();

        foreach (static::DASHBOARD_DEFAULTS as $key => $default) {
            if (!$settings->where('key', $key)->count()) {
                $settings->push(new static(['key' => $key, 'value' => $default]));
            }
        }

        return $settings;
    }
}
