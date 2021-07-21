<?php

namespace App\Http\Controllers;

use App\Services\HouseServices;

class HouseController extends Controller
{
    /**
     * Sync house data from api(https://data.taipei/#/dataset/detail?id=659c3565-df41-4f80-915f-95e83071bdcd).
     *
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
        $house_service = new HouseServices();
        $result = $house_service->syncHouseInfo();

        return response()->json($result, 200);
    }
}
