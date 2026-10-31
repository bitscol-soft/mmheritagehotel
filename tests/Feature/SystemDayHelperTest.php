<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesAccSchema;
use Tests\TestCase;

/**
 * BUGS #68: the "system day" helpers read `system_settings` rows that a fresh or
 * partially seeded deployment may not have. They used to take whole screens down
 * (New Booking, the booking board, night audits, today's reports) instead of
 * degrading to "no business-day rollover".
 */
class SystemDayHelperTest extends TestCase
{
    use CreatesAccSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();
        Carbon::setTestNow(Carbon::parse('2026-10-16 09:30:00', 'Asia/Dhaka'));
        \Illuminate\Support\Facades\Cache::flush(); // system_setting() caches forever
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_today_from_system_returns_today_when_the_setting_is_missing(): void
    {
        $this->assertNull(setting('date_start_end'));

        $this->assertSame('2026-10-16', today_from_system());
    }

    public function test_today_from_system_returns_today_when_the_setting_is_blank(): void
    {
        $this->seedSetting('date_start_end', '');

        $this->assertSame('2026-10-16', today_from_system());
    }

    public function test_today_from_system_returns_today_when_the_range_has_no_end(): void
    {
        $this->seedSetting('date_start_end', '4:00 AM');

        $this->assertSame('2026-10-16', today_from_system());
    }

    public function test_today_from_system_still_rolls_over_for_a_real_range(): void
    {
        // business day 4:00 AM - 3:59 AM: 09:30 AM is inside the open day, so
        // the accounting date is still the calendar date
        $this->seedSetting('date_start_end', '4:00 AM-3:59 AM');

        $this->assertSame('2026-10-16', today_from_system());
    }

    public function test_today_from_system_rolls_back_before_the_business_day_starts(): void
    {
        // 02:00 AM with a day that only starts at 4:00 AM belongs to yesterday
        Carbon::setTestNow(Carbon::parse('2026-10-16 02:00:00', 'Asia/Dhaka'));
        $this->seedSetting('date_start_end', '4:00 AM-3:59 AM');

        $this->assertSame('2026-10-15', today_from_system());
    }

    public function test_dashboard_settings_defaults_keep_the_blades_renderable(): void
    {
        // the dashboard blade dereferences these keys directly
        $settings = \App\Models\SystemSetting::withDashboardDefaults();

        $this->assertSame('0', (string) $settings->where('key', 'visible_booking_ui_dashboard')->first()->value);
        $this->assertSame('1', (string) $settings->where('key', 'dashboard')->first()->value);
    }

    public function test_dashboard_settings_defaults_do_not_shadow_saved_values(): void
    {
        $this->seedSetting('visible_booking_ui_dashboard', '1');

        $settings = \App\Models\SystemSetting::withDashboardDefaults();

        $this->assertSame('1', (string) $settings->where('key', 'visible_booking_ui_dashboard')->first()->value);
        $this->assertSame(1, $settings->where('key', 'visible_booking_ui_dashboard')->count());
    }

    private function seedSetting(string $key, string $value): void
    {
        DB::table('system_settings')->insert([
            'key' => $key, 'value' => $value, 'created_at' => now(), 'updated_at' => now(),
        ]);
        \Illuminate\Support\Facades\Cache::flush();
    }
}
