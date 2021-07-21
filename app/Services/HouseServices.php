<?php

namespace App\Services;

use Throwable;
use App\Libraries\CurlLib;
use App\Models\House;
use Illuminate\Support\Facades\DB;

class HouseServices
{
    public function syncHouseInfo()
    {
        try {
            $url = 'https://www.hms.gov.taipei/api/BigData/project';

            list($code, $body, $url, $msg) = CurlLib::get($url);

            if (200 != $code) {
                throw new Throwable('Http status error. Code is [' . $code . ']', 0);
            }

            $results = json_decode($body, true);
            $json_error_code = json_last_error();
            if ($json_error_code !== JSON_ERROR_NONE) {
                throw new Throwable('JSON decode failed. Code is [' . $json_error_code . ']', 1);
            }

            // TODO: 因為沒有比較有效率的比對方式，且資料數不多，因此直接採用全刪 DB 來確保更新的資料是最新的，之後更新有其他問題再另行處理。
            DB::table('houses')->truncate();
            array_walk($results, function (&$result) {
                $datetime = date('Y-m-d H:i:s');
                $result = array_merge($result, [
                    'created_at' => $datetime,
                    'updated_at' => $datetime,
                ]);
            });
            House::insert($results);

            return [
                'status' => 2,
                'message' => 'Get house data and insert into database `houses` successfully.',
            ];
        } catch (Throwable $e) {
            return [
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public function search($params)
    {
        $query = House::query();

        if (isset($params['distict']) and $params['distict']) {
            $query->Like('distict', $params['distict']);
        }

        return $query->get()->map(function ($house) {
            return $house->makeHidden(['created_at', 'updated_at']);
        });
    }
}
