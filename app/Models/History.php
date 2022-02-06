<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class History extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';

    public static function writeDataHistory($history)
    {
//
//        if(isset((array)$request->data['finish']))

        if(!empty($history['volume']) && !empty($history['compare_volume']) && empty($history['volume_difference']))
        {
            $history['volume_difference'] = $history['volume'] - $history['compare_volume'];
        }

        $response = json_encode(['warning' => true,'msg' => 'Был передан пустой массив']);
        if(!empty($history))
        {
            try
            {
                DB::table("histories")->insert($history);
//
//                $response = $this->sendHistoryToBot($history);

//                if($response['status'] === 'error')
//                {
//                    return $this->responseHelper($response['status'], $response['msg'], $response['error']);
//                }

                $history = json_encode($history);
                $response = "Передача прошла успешно, переданные данные: {$history}";
            }
            catch (Exception $e)
            {
                $response = json_encode(['error' => true, 'msg' => "При попытке записать данные в бд произошла ошибка: {$e}"]);
            }
        };
    }
}
