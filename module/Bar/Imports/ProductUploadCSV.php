<?php

namespace Module\Bar\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Bar\Models\ProductUpload;

class ProductUploadCSV implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ProductUpload([

            'name'                      => $row['name'],
            'batch_number'              => $row['batch_number'] != '' ? $row['batch_number'] : null,
            'category'                  => $row['category'] != NULL ? $row['category'] : 'Default',
            'generic'                   => $row['generic'] != '' ? $row['generic'] : null,
            'medicine_type'             => $row['medicine_type'] != '' ? $row['medicine_type'] : 'None',
            'supplier'                  => $row['supplier'] != '' ? $row['supplier'] : null,
            'pack_size'                 => $row['pack_size'] != '' ? $row['pack_size'] : 1,
            'small_unit'                => $row['small_unit'],
            'big_unit'                  => $row['big_unit'],
            'purchase_price'            => $row['purchase_price'],
            'retail_purchase_price'     => $row['retail_purchase_price'] != '' ? $row['retail_purchase_price'] : 0,
            'sale_price'                => $row['sale_price'],
            'retail_sale_price'         => $row['retail_sale_price'] != '' ? $row['retail_sale_price'] : 0,
            'stock_limitation'          => $row['stock_limitation'],
            'big_quantity'              => $row['big_quantity'] ?? 0,
            'small_quantity'            => $row['small_quantity'] ?? 0,
        ]);
    }
}
