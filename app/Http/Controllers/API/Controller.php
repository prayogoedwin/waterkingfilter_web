<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    protected function ok($data = null, string $message = 'Berhasil memuat data', int $status = 200)
    {
        return $this->responseJson($data, $message, $status);
    }

    protected function created($data, string $message = 'Berhasil menyimpan data', int $status = 201)
    {
        return $this->responseJson($data, $message, $status);
    }

    protected function notFound(string $message = 'Data tidak ditemukan')
    {
        return $this->responseJson(null, $message, 404);
    }

    protected function error($message, int $status = 500, array $errors = [])
    {
        return response()->json([
            'success'   => false,
            'code'      => $status,
            'message'   => $message,
            'errors'    => $errors
        ], $status);
    }

    private function responseJson($data, $message, $status)
    {
        return response()->json(["message" => $message, "success" => true, "code" => $status, "data" => $data], $status);
    }
}
