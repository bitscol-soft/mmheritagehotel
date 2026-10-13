<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\Rooms;

class HotelDemoRoomsAndGuestsSeeder extends Seeder
{
    /**
     * Seed 20 sample hotel rooms (#101-#403 across Floors 1-4) and
     * 20 corporate/VIP/diplomatic/family/airline sample guests.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Deluxe Double'      => ['short_code' => 'DD',  'rent' => 4200,  'beds' => 1, 'max_guests' => 2],
            'Deluxe King'        => ['short_code' => 'DK',  'rent' => 4500,  'beds' => 1, 'max_guests' => 2],
            'Executive Twin'     => ['short_code' => 'ET',  'rent' => 3600,  'beds' => 2, 'max_guests' => 2],
            'Superior Double'    => ['short_code' => 'SD',  'rent' => 3800,  'beds' => 1, 'max_guests' => 2],
            'Standard Single'    => ['short_code' => 'SS',  'rent' => 3200,  'beds' => 1, 'max_guests' => 1],
            'Heritage Suite'     => ['short_code' => 'HS',  'rent' => 6500,  'beds' => 2, 'max_guests' => 3],
            'Superior Twin'      => ['short_code' => 'ST',  'rent' => 4000,  'beds' => 2, 'max_guests' => 2],
            'Royal Family Suite' => ['short_code' => 'RFS', 'rent' => 8500,  'beds' => 2, 'max_guests' => 4],
            'Executive King'     => ['short_code' => 'EK',  'rent' => 5200,  'beds' => 1, 'max_guests' => 2],
            'Diplomatic Suite'   => ['short_code' => 'DS',  'rent' => 9200,  'beds' => 2, 'max_guests' => 3],
            'Presidential Suite' => ['short_code' => 'PS',  'rent' => 12500, 'beds' => 2, 'max_guests' => 4],
            'Family Suite'       => ['short_code' => 'FS',  'rent' => 7800,  'beds' => 2, 'max_guests' => 4],
            'Penthouse Suite'    => ['short_code' => 'PHS', 'rent' => 14000, 'beds' => 2, 'max_guests' => 4],
            'Skyview Executive'  => ['short_code' => 'SVE', 'rent' => 6800,  'beds' => 1, 'max_guests' => 2],
            'Grand Presidential' => ['short_code' => 'GPS', 'rent' => 18500, 'beds' => 3, 'max_guests' => 6],
        ];

        $categoryMap = [];
        foreach ($categories as $catName => $meta) {
            $category = RoomCategory::updateOrCreate(
                ['name' => $catName],
                [
                    'short_code' => $meta['short_code'],
                    'rent'       => $meta['rent'],
                ]
            );
            $categoryMap[$catName] = $category->id;
        }

        $rooms = [
            ['room_number' => '101', 'category' => 'Deluxe Double',      'rent' => 4200,  'beds' => 1, 'max_guests' => 2, 'rfid' => 'RF-101-A', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '102', 'category' => 'Deluxe King',        'rent' => 4500,  'beds' => 1, 'max_guests' => 2, 'rfid' => 'RF-102-A', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '103', 'category' => 'Deluxe King',        'rent' => 4500,  'beds' => 1, 'max_guests' => 2, 'rfid' => 'RF-103-A', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '104', 'category' => 'Executive Twin',     'rent' => 3600,  'beds' => 2, 'max_guests' => 2, 'rfid' => 'RF-104-A', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '105', 'category' => 'Superior Double',    'rent' => 3800,  'beds' => 1, 'max_guests' => 2, 'rfid' => 'RF-105-A', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '106', 'category' => 'Standard Single',    'rent' => 3200,  'beds' => 1, 'max_guests' => 1, 'rfid' => 'RF-106-A', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '201', 'category' => 'Heritage Suite',     'rent' => 6500,  'beds' => 2, 'max_guests' => 3, 'rfid' => 'RF-201-B', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '202', 'category' => 'Heritage Suite',     'rent' => 6500,  'beds' => 2, 'max_guests' => 3, 'rfid' => 'RF-202-B', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '203', 'category' => 'Superior Twin',      'rent' => 4000,  'beds' => 2, 'max_guests' => 2, 'rfid' => 'RF-203-B', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '204', 'category' => 'Royal Family Suite', 'rent' => 8500,  'beds' => 2, 'max_guests' => 4, 'rfid' => 'RF-204-B', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '205', 'category' => 'Deluxe Double',      'rent' => 4800,  'beds' => 1, 'max_guests' => 2, 'rfid' => 'RF-205-B', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '206', 'category' => 'Executive King',     'rent' => 5200,  'beds' => 1, 'max_guests' => 2, 'rfid' => 'RF-206-B', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '301', 'category' => 'Diplomatic Suite',   'rent' => 9200,  'beds' => 2, 'max_guests' => 3, 'rfid' => 'RF-301-C', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '302', 'category' => 'Presidential Suite', 'rent' => 12500, 'beds' => 2, 'max_guests' => 4, 'rfid' => 'RF-302-C', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '303', 'category' => 'Executive Twin',     'rent' => 4600,  'beds' => 2, 'max_guests' => 2, 'rfid' => 'RF-303-C', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '304', 'category' => 'Executive King',     'rent' => 5400,  'beds' => 1, 'max_guests' => 2, 'rfid' => 'RF-304-C', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '305', 'category' => 'Family Suite',       'rent' => 7800,  'beds' => 2, 'max_guests' => 4, 'rfid' => 'RF-305-C', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '401', 'category' => 'Penthouse Suite',    'rent' => 14000, 'beds' => 2, 'max_guests' => 4, 'rfid' => 'RF-401-D', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '402', 'category' => 'Skyview Executive',  'rent' => 6800,  'beds' => 1, 'max_guests' => 2, 'rfid' => 'RF-402-D', 'status' => 1, 'smoking' => 'Non-Smoking'],
            ['room_number' => '403', 'category' => 'Grand Presidential', 'rent' => 18500, 'beds' => 3, 'max_guests' => 6, 'rfid' => 'RF-403-D', 'status' => 1, 'smoking' => 'Non-Smoking'],
        ];

        foreach ($rooms as $r) {
            Rooms::updateOrCreate(
                ['room_number' => $r['room_number']],
                [
                    'name'           => $r['category'],
                    'room_category'  => $categoryMap[$r['category']] ?? 1,
                    'f_r_id_card'    => $r['rfid'],
                    'status'         => $r['status'],
                    'smoking_status' => $r['smoking'],
                    'rent'           => $r['rent'],
                    'beds'           => $r['beds'],
                    'max_guests'     => $r['max_guests'],
                ]
            );
        }

        $guests = [
            ['name' => 'Tanvir Ahmed Chowdhury',     'phone_no' => '01711948210', 'nid_no' => 'BD-8894021562', 'email' => 'tanvir.chowdhury@orionpharma.com.bd', 'gender' => 'Male',   'profession' => 'Director, Orion Pharma Ltd.',        'address' => 'Gulshan-2, Dhaka'],
            ['name' => 'Nadia Islam',                'phone_no' => '01713220491', 'nid_no' => 'BD-7741029384', 'email' => 'nadia.islam@acmeglobal.com',           'gender' => 'Female', 'profession' => 'VP Operations, Acme Global Ltd.',    'address' => 'Banani, Dhaka'],
            ['name' => 'Dr. Farhana Rahman',         'phone_no' => '01819334812', 'nid_no' => 'A09481204',     'email' => 'farhana.rahman@squarehospital.com',    'gender' => 'Female', 'profession' => 'Senior Consultant, Square Hospital', 'address' => 'Dhanmondi, Dhaka'],
            ['name' => 'Rafiqul Islam Bhuiyan',      'phone_no' => '01911458920', 'nid_no' => 'BD-5521904811', 'email' => 'rafiq.bhuiyan@beximco.com',            'gender' => 'Male',   'profession' => 'GM, Beximco Group',                 'address' => 'Uttara Sector 4, Dhaka'],
            ['name' => 'Tahmina Akter',              'phone_no' => '01611883920', 'nid_no' => 'BD-6630192845', 'email' => 'tahmina.akter@bracbank.com',           'gender' => 'Female', 'profession' => 'Head of Treasury, BRAC Bank PLC',    'address' => 'Tejgaon, Dhaka'],
            ['name' => 'Syed Kamrul Hasan',          'phone_no' => '01715609281', 'nid_no' => 'BD-4489201932', 'email' => 'kamrul.hasan@grameenphone.com',        'gender' => 'Male',   'profession' => 'Enterprise Lead, Grameenphone',      'address' => 'Bashundhara R/A, Dhaka'],
            ['name' => 'Nusrat Jahan',               'phone_no' => '01819552104', 'nid_no' => 'BG-0192837',    'email' => 'nusrat.jahan@unicef.org',              'gender' => 'Female', 'profession' => 'Program Specialist, UNICEF',         'address' => 'Baridhara Diplomatic Zone, Dhaka'],
            ['name' => 'Engr. Tariqul Alam',         'phone_no' => '01712990412', 'nid_no' => 'BD-3391028475', 'email' => 'tariqul.alam@summitpower.com',         'gender' => 'Male',   'profession' => 'Chief Engineer, Summit Power Int.',  'address' => 'Kawran Bazar, Dhaka'],
            ['name' => 'Shabnam Mustari',            'phone_no' => '01714772109', 'nid_no' => 'BP-4491023',    'email' => 'shabnam.mustari@biman.com',            'gender' => 'Female', 'profession' => 'Flight Purser, Biman Bangladesh',    'address' => 'Kurmitola, Dhaka'],
            ['name' => 'Brig. Gen. Mahmudul Hasan',  'phone_no' => '01711004921', 'nid_no' => 'DP-0049182',    'email' => 'mahmud.hasan@defense.gov.bd',          'gender' => 'Male',   'profession' => 'Defense Delegation Officer',         'address' => 'Dhaka Cantonment'],
            ['name' => 'Ashraful Alam Siddiqui',     'phone_no' => '01817203948', 'nid_no' => 'BD-9182736450', 'email' => 'ashraful@waltonbd.com',                'gender' => 'Male',   'profession' => 'Executive Director, Walton Hi-Tech', 'address' => 'Motijheel, Dhaka'],
            ['name' => 'Barrister Zaima Rahman',     'phone_no' => '01716839201', 'nid_no' => 'A10293847',     'email' => 'zaima.rahman@chambers.bd',             'gender' => 'Female', 'profession' => 'Advocate, Supreme Court Chambers',   'address' => 'Kakrail, Dhaka'],
            ['name' => 'Amb. Kenji Takahashi',       'phone_no' => '01711889900', 'nid_no' => 'JP-8839201',    'email' => 'k.takahashi@embassy-jp.org',           'gender' => 'Male',   'profession' => 'Diplomatic Envoy, Embassy of Japan', 'address' => 'Baridhara, Dhaka'],
            ['name' => 'Capt. Jonathan Vance',       'phone_no' => '01713774411', 'nid_no' => 'UK-5549201',    'email' => 'j.vance@horizon-aviation.uk',          'gender' => 'Male',   'profession' => 'Captain, Horizon Aviation UK',       'address' => 'London / Dhaka'],
            ['name' => 'Meher Afroz Shaon',          'phone_no' => '01912665432', 'nid_no' => 'BD-2201938476', 'email' => 'meher.shaon@bergerbd.com',             'gender' => 'Female', 'profession' => 'Brand Director, Berger Paints BD',   'address' => 'Uttara Sector 7, Dhaka'],
            ['name' => 'Salma Khatun',               'phone_no' => '01718554321', 'nid_no' => 'BD-1192837465', 'email' => 'salma.khatun@prangroup.com',           'gender' => 'Female', 'profession' => 'Export Head, PRAN-RFL Group',        'address' => 'Badda, Dhaka'],
            ['name' => 'Imran Hossain & Family',     'phone_no' => '01811902384', 'nid_no' => 'BD-7788990011', 'email' => 'imran.hossain@citygroup.com.bd',       'gender' => 'Male',   'profession' => 'Director, City Group Ltd.',          'address' => 'Chattogram / Dhaka'],
            ['name' => 'Dr. Ahsan Habib',            'phone_no' => '01711332211', 'nid_no' => 'A88392019',     'email' => 'ahsan.habib@icddrb.org',               'gender' => 'Male',   'profession' => 'Lead Scientist, icddr,b',            'address' => 'Mohakhali, Dhaka'],
            ['name' => 'Elena Rostova',              'phone_no' => '01719445566', 'nid_no' => 'WB-6647382',    'email' => 'e.rostova@worldbank.org',              'gender' => 'Female', 'profession' => 'Senior Economist, World Bank',       'address' => 'Agargaon, Dhaka'],
            ['name' => 'Rubana Huq',                 'phone_no' => '01711223344', 'nid_no' => 'A77482910',     'email' => 'rubana.huq@mohammadi.group',           'gender' => 'Female', 'profession' => 'Managing Director, Mohammadi Group', 'address' => 'Gulshan-1, Dhaka'],
        ];

        foreach ($guests as $g) {
            Guest::updateOrCreate(
                ['phone_no' => $g['phone_no']],
                [
                    'name'       => $g['name'],
                    'email'      => $g['email'],
                    'nid_no'     => $g['nid_no'],
                    'gender'     => $g['gender'],
                    'profession' => $g['profession'],
                    'address'    => $g['address'],
                    'is_stuff'   => 0,
                ]
            );
        }
    }
}
