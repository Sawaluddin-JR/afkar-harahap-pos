<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Entities\Setting;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'company_name' => 'Afkar Harahap Pos',
            'company_email' => 'afkarhrpos@gmail.com',
            'company_phone' => '012345678901',
            'notification_email' => 'notification@test.com',
            'default_currency_id' => 1,
            'default_currency_position' => 'prefix',
            'footer_text' => 'Afkar Harahap Pos © 2024 || Developed by <strong><a target="_blank" href="#">Aliya Rohaya Siregar</a></strong>',
            'company_address' => 'Jakarta, Indonesia'
        ]);
    }
}
