<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $status - указание статуса для отправки
     * @param $msg - сообщение для отправки
     * @param $additional - массив для дополнительных полей
     *
     * @return array ['status' => $status, 'msg' => $msg, 'additionalX1' => $additionalX1, 'additionalXn' => $additionalXn]
     */
    protected function responseHelper($status, $msg, $additional = [])
    {
        return array_merge(['status' => $status, 'msg' => $msg], $additional);
    }
}
