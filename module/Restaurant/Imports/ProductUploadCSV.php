<?php

namespace Module\Restaurant\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Restaurant\Models\ProductUpload;
use Module\Restaurant\Models\Product;

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
            'barcode'                   => $row['barcode'] != '' ? $row['barcode'] : null,
            'category_id'               => $row['category_id'],
            'unit_id'                   => $row['unit_id'] != '' ? $row['unit_id'] : null,
            'supplier_id'               => $row['supplier_id'] != '' ? $row['supplier_id'] : null,
            'unit_cost'                 => $row['unit_cost'] ?? 0,
            'sale_price'                => $row['sale_price'] ?? 0,
            'vat_amount'                => $row['vat_amount'] ?? 0,
            'opening_quantity'          => $row['opening_quantity'] ?? 0,
            'available_quantity'        => $row['available_quantity'] ?? 0,
            'stock_limit'               => $row['stock_limit'] ?? 0,
            'pack_size'                 => $row['pack_size'] != '' ? $row['pack_size'] : null,
            'pack_quantity'             => $row['pack_quantity'] != '' ? $row['pack_quantity'] : null,
            'is_bar'                    => $row['is_bar'] ?? 0,
            'pack_unit_id'              => $row['pack_unit_id'] != '' ? $row['pack_unit_id'] : null,
            'package_id'                => $row['package_id'] != '' ? $row['package_id'] : null,
            'is_matrial'                => $row['is_matrial'] != '' ? $row['is_matrial'] : 0,
            'status'                    => 1,
            'created_by'                => auth()->user()->id,
        ]);
    }
}
