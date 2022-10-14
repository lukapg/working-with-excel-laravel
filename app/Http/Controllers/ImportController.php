<?php

namespace App\Http\Controllers;

use App\Imports\OrdersImport;
use App\Models\Country;
use App\Models\ItemType;
use App\Models\Order;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportController extends Controller
{
    public function laravelExcel(Request $request)
    {
        if (!$request->file('demo_file')) {
            return response()->json('You need to upload an excel file!', 400);
        }

        Excel::import(new OrdersImport(), $request->file('demo_file'));

        return response()->json('Import done!', 200);
    }

    public function fastExcel(Request $request)
    {
        if (!$request->file('demo_file')) {
            return response()->json('You need to upload an excel file!', 400);
        }

        $fileName = $request->file('demo_file')->store('/excel');
        $storagePath = storage_path('app/' . $fileName);

        $orders = (new FastExcel)->import($storagePath, function ($row) {
            $region = Region::firstOrCreate(
                [
                    'name' => $row['Region'],
                ],
                [
                    'name' => $row['Region'],
                ]
            )->id;

            $country = Country::firstOrCreate(
                [
                    'name' => $row['Country'],
                ],
                [
                    'name' => $row['Country'],
                    'region_id' => $region,
                ]
            )->id;

            $itemType = ItemType::firstOrCreate(
                [
                    'name' => $row['Item Type'],
                ],
                [
                    'name' => $row['Item Type'],
                ]
            )->id;

            $orderDate = Carbon::createFromFormat('m/d/Y', $row['Order Date'])->format('Y-m-d');
            $shipDate = Carbon::createFromFormat('m/d/Y', $row['Ship Date'])->format('Y-m-d');

            return Order::create([
                'country_id' => $country,
                'item_type_id' => $itemType,
                'sales_channel' => $row['Sales Channel'],
                'order_priority' => $row['Order Priority'],
                'order_date' => $orderDate,
                'order_id' => $row['Order ID'],
                'ship_date' => $shipDate,
                'units_sold' => floatval($row['Units Sold']),
                'unit_price' => floatval($row['Unit Price']),
                'unit_cost' => floatval($row['Unit Cost']),
                'total_revenue' => floatval($row['Total Revenue']),
                'total_cost' => floatval($row['Total Cost']),
                'total_profit' => floatval($row['Total Profit']),
            ]);
        });

        return response()->json('Import done!', 200);
    }

    public function simpleExcelReader(Request $request)
    {
        if (!$request->file('demo_file')) {
            return response()->json('You need to upload an excel file!', 400);
        }

        $orders = SimpleExcelReader::create($request->file('demo_file'), 'csv')->getRows();

        $orders->each(function (array $row) {
            $region = Region::firstOrCreate(
                [
                    'name' => $row['Region'],
                ],
                [
                    'name' => $row['Region'],
                ]
            )->id;

            $country = Country::firstOrCreate(
                [
                    'name' => $row['Country'],
                ],
                [
                    'name' => $row['Country'],
                    'region_id' => $region,
                ]
            )->id;

            $itemType = ItemType::firstOrCreate(
                [
                    'name' => $row['Item Type'],
                ],
                [
                    'name' => $row['Item Type'],
                ]
            )->id;

            $orderDate = Carbon::createFromFormat('m/d/Y', $row['Order Date'])->format('Y-m-d');
            $shipDate = Carbon::createFromFormat('m/d/Y', $row['Ship Date'])->format('Y-m-d');

            return Order::create([
                'country_id' => $country,
                'item_type_id' => $itemType,
                'sales_channel' => $row['Sales Channel'],
                'order_priority' => $row['Order Priority'],
                'order_date' => $orderDate,
                'order_id' => $row['Order ID'],
                'ship_date' => $shipDate,
                'units_sold' => floatval($row['Units Sold']),
                'unit_price' => floatval($row['Unit Price']),
                'unit_cost' => floatval($row['Unit Cost']),
                'total_revenue' => floatval($row['Total Revenue']),
                'total_cost' => floatval($row['Total Cost']),
                'total_profit' => floatval($row['Total Profit']),
            ]);
        });

        return response()->json('Import done!', 200);
    }
}