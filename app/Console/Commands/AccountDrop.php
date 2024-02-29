<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Models\AccountType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AccountDrop extends Command
{
    protected $signature = 'acc:drop';

    protected $description = 'Drops Accounting Related Tables';

    public function handle()
    {
        $tables = [
            'supplier_ledgers',
            'customer_ledgers',
            'acc_payments',
            'acc_collections',
            'acc_purchase_details',
            'acc_purchases',
            'acc_purchase_returns',
            'acc_purchase_returns',
            'acc_sale_details',
            'acc_sales',
            'acc_sale_return_details',
            'acc_sale_returns',
            'acc_suppliers',
            'acc_customers',
            'acc_stocks',
            'acc_stock_summaries',
            // 'product_stock_transections',
            // 'products',
            'acc_categories',
            'units',
            'fund_transfers',
            'banks',
            'daily_ledgers',
            'transactions',
            'voucher_details',
            'vouchers',
            'accounts',
            'account_subsidiaries',
            'account_controls',
            'account_setups',
            'account_groups',
        ];

        try {
            Schema::table('hotel_account_type', function (Blueprint $table) {
                $table->dropForeign(['account_id']);
    
            });

        } catch (\Throwable $th) {}

        AccountType::query()->update(['account_id'    => null]);

        DB::table('migrations')->where('migration', '2023_01_25_125440_hotel_account_type_id_migrate_to_hotel_account_type_table')->delete();
        DB::table('migrations')->where('migration', '2022_06_06_104353_add_description_into_acc_purchase_details_table')->delete();
        DB::table('migrations')->where('migration', '2022_09_25_153134_add_source_columns_in_acc_purchases_table')->delete();
        DB::table('migrations')->where('migration', '2022_06_06_104353_add_description_into_acc_sale_details_table')->delete();
        DB::table('migrations')->where('migration', '2022_06_06_104353_add_description_into_acc_products_table')->delete();
        DB::table('migrations')->where('migration', '2022_02_14_151700_set_nullable_amount_to_transactions_table')->delete();


        foreach ($tables as $table) {

            Schema::dropIfExists($table);

            DB::table('migrations')->where('migration', 'like', '%_create_'.$table.'_table')->delete();
        }

        $this->info('Account tables dropped successfully!');
    }
}
