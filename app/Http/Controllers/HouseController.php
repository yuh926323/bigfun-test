<?php

namespace App\Http\Controllers;

use App\Http\Requests\HouseSearch;
use App\Http\Requests\HouseSearchAround;
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

    public function search(HouseSearch $request)
    {
        $sanitized = $request->validated();

        $house_service = new HouseServices();
        $results = $house_service->search($sanitized);

        return response()->json([
            'status' => 0,
            'message' => 'Search house info successfully.',
            'results' => $results->toArray(),
        ], 200);
    }

    public function searchAround(HouseSearchAround $request)
    {
        $sanitized = $request->validated();

        $house_service = new HouseServices();
        $results = $house_service->searchAround($sanitized);

        return response()->json([
            'status' => 0,
            'message' => 'Search house info successfully.',
            'results' => $results->toArray(),
        ], 200);
    }
}
