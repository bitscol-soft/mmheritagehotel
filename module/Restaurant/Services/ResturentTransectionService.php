<?php

namespace Module\Restaurant\Services;

use Module\Hotel\Models\InvoiceGenerate;

class ResturentTransectionService
{



    public function getInvoiceNo($type = 'Restaurant Sale'): string
    {

        $date  = date('Y-m');

        $nextId = optional(InvoiceGenerate::query()
            ->where('type', $type)
            ->where('year', $date)
            ->first())->next_id;

        if ($nextId == null)

            $nextId = InvoiceGenerate::query()
                ->create([
                    'type' => $type,
                    'year' => $date,
                    'next_id' => 1,
                ])->next_id;

        $nextId = $date . '-' . str_pad($nextId, 4, "0", STR_PAD_LEFT);

        return $nextId;
    }

    public function getPurInvoiceNo($type = 'RestMaterial Purchase'): string
    {

        $date  = date('Y-m');

        $nextId = optional(InvoiceGenerate::query()
            ->where('type', $type)
            ->where('year', $date)
            ->first())->next_id;

        if ($nextId == null)

            $nextId = InvoiceGenerate::query()
                ->create([
                    'type' => $type,
                    'year' => $date,
                    'next_id' => 1,
                ])->next_id;

        $nextId = $date . '-' . str_pad($nextId, 4, "0", STR_PAD_LEFT);

        return $nextId;
    }

    public function setNextInvoiceNo($type, $time)
    {
        $invoice_no = InvoiceGenerate::query()
            ->firstOrCreate([
                'type' => $type,
                'year' => $time,
            ]);

        $invoice_no->increment('next_id');
        $invoice_no->save();
    }
}
