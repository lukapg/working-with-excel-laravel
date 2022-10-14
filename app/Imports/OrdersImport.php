<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\ItemType;
use App\Models\Order;
use App\Models\Region;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrdersImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $region = Region::firstOrCreate(
            [
                'name' => $row['region'],
            ],
            [
                'name' => $row['region'],
            ]
        )->id;

        $country = Country::firstOrCreate(
            [
                'name' => $row['country'],
            ],
            [
                'name' => $row['country'],
                'region_id' => $region,
            ]
        )->id;

        $itemType = ItemType::firstOrCreate(
            [
                'name' => $row['item_type'],
            ],
            [
                'name' => $row['item_type'],
            ]
        )->id;

        $orderDate = Carbon::createFromFormat('m/d/Y', $row['order_date'])->format('Y-m-d');
        $shipDate = Carbon::createFromFormat('m/d/Y', $row['ship_date'])->format('Y-m-d');

        return new Order([
            'country_id' => $country,
            'item_type_id' => $itemType,
            'sales_channel' => $row['sales_channel'],
            'order_priority' => $row['order_priority'],
            'order_date' => $orderDate,
            'order_id' => $row['order_id'],
            'ship_date' => $shipDate,
            'units_sold' => $row['units_sold'],
            'unit_price' => $row['unit_price'],
            'unit_cost' => $row['unit_cost'],
            'total_revenue' => $row['total_revenue'],
            'total_cost' => $row['total_cost'],
            'total_profit' => $row['total_profit'],
        ]);
    }

    /**
     * Excel importer chunk size
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}