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

    public function searchAround($params)
    {
        list($min_lat, $max_lat, $min_lng, $max_lng) = $this->getMinAndMaxLatLng($params['lat'], $params['lng'], 1000);
        $query = House::where('lat', '>', $min_lat)
            ->where('lng', '>', $min_lng)
            ->where('lat', '<', $max_lat)
            ->where('lng', '<', $max_lng);

        return $query->get()->map(function ($house) {
            return $house->makeHidden(['created_at', 'updated_at']);
        });
    }

    /**
     * 取得以該經緯度為中心，指定半徑之最大最小經緯度。
     * refs: https://www.cnblogs.com/hellocjr/p/4340374.html
     *
     * @param double $lat (緯度)
     * @param double $lng (經度)
     * @param integer $radiue 半徑(公尺)
     *
     * @return \Illuminate\Http\Response
     */
    private function getMinAndMaxLatLng($lat, $lng, $raidus)
    {
        // 取得度數
        $degree = 24901 * 1609 / 360; // (地球周長 * 地球半徑 / 360度)

        // 計算緯度
        $dpm_lat = 1 / $degree;
        $radius_lat = $dpm_lat * $raidus;
        $min_lat = $lat - $radius_lat;
        $max_lat = $lat + $radius_lat;

        // 計算經度
        $mpd_lng = $degree * cos($lat * (pi() / 180)); // 緯度的餘弦
        $dpm_lng = 1 / $mpd_lng;
        $radius_lng = $dpm_lng * $raidus;
        $min_lng = $lng - $radius_lng;
        $max_lng = $lng + $radius_lng;

        return [
            $min_lat,
            $max_lat,
            $min_lng,
            $max_lng,
        ];
    }
}
