<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HospitalDrop extends Command
{
    protected $signature = 'hospital:drop';

    protected $description = 'Drops Hospital Related Tables';

    public function handle()
    {
        $tables = [
            'blood_groups',
            'service_categories',
            'hospital_wards',
            'hospital_cabins',
            'parties',
            'corporate_clients',
            'card_types',
            'weekdays',
            'assign_doctors',
            'assign_doctor_weekdays',
            'medicine_generics',
            'doctor_fees',
            'outdoor_shifts',
            'patient_details',
            'patient_prescriptions',
            'hospital_services',
            'referred_doctors',
            'commission_refers',
            'hospital_service_sales',
            'doctor_appointments',
            'service_templates',
            'commission_assigns',
            'hospital_service_sale_items',
            'inventory_brands',
            'inventory_units',
            'lab_material_units',
            'lab_materials',
            'service_sale_item_samples',
            'inventory_lab_product_sales',
            'lab_material_serves',
            'inventory_lab_purchases',
            'inventory_lab_product_sale_items',
            'prescriptions',
            'inventory_labs',
            'inventory_lab_purachase_items',
            'prescription_services',
            'drug_durations',
            'drug_advices',
            'prescription_medicines',
            'food_restrictions',
            'inventory_categories',
            'drug_doses',
            'prescription_food_restrictions',
            'medicine_types',
            'inventory_product_sales',
            'inventory_products',
            'inventory_product_barcodes',
            'inventory_product_sale_items',
            'inventory_product_purchases',
            'inventory_product_purchase_items',
            'patients',
            'card_holders',
            'card_holder_patients',
            'referred_doctors',
        ];

        foreach ($tables as $table) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists($table);
            Schema::enableForeignKeyConstraints();
            DB::table('migrations')->where('migration', 'like', '%_create_'.$table.'_table')->delete();

        }

        $this->info('Hospital tables dropped successfully!');
    }
}
