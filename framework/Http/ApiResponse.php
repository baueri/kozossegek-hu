<?php

namespace Framework\Http;

class ApiResponse
{
    public function ok($data = null): array
    {
        return $this->response($data, true);
    }

    public function error($data = null): array
    {
        return $this->response($data, false);
    }


    public function response($data, ?bool $success = null): array
    {
        Response::asJson();

        if (is_bool($data)) {
            $success = $data;
            $data = [];
        } elseif (is_string($data)) {
            $data = ['msg' => $data];
        }

        return array_merge(compact('success'), (array) $data);
    }
}
