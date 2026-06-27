<?php

namespace App\Exports;

use App\Models\Shop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SellersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Shop::with('user')->get()->map(function ($shop) {
            return [
                $shop->name,
                $shop->user->phone,
                $shop->user->email,
                $shop->user->products->count(),
                $shop->admin_to_pay,
                $shop->commission_percentage,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'Email',
            'Products',
            'Due To Seller',
            'Commission'
        ];
    }
}