<?php

namespace Module\GeneralStore\Services\Export;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Module\GeneralStore\Models\Item;

/**
 * Item list CSV export (referenced by ItemController::export(), previously missing).
 */
class ExportItemCSV implements FromQuery, WithHeadings, ShouldAutoSize
{
    public function query(): Builder
    {
        return Item::query()
            ->select(
                'items.id',
                'items.name',
                'items.opening_balance',
                'items.rate',
                'items.current_stock',
                'items.remaining_quantity',
                'items.average_rate',
                'items.created_at'
            );
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Opening Balance',
            'Rate',
            'Current Stock',
            'Remaining Quantity',
            'Average Rate',
            'Created At',
        ];
    }
}
