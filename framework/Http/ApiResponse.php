<?php

namespace Framework\Http;

class ApiResponse
{
    public function ok($data = null): array
    {
        return $this->response($data, true);
    }

    /**
     * @param array|string $data
     * @return array
     */
    public function error($data = [], ?int $code = null)
    {
        return $this->response($data, false, $code);
    }

    public function response($data, ?bool $success = null, ?int $code = null)
    {
        Response::asJson();

        if (is_bool($data)) {
            $success = $data;
            $data = [];
        } elseif (is_string($data)) {
            $data = ['msg' => $data];
        }

        if ($code) {
            Response::setStatusCode($code);
        }

        return array_merge(compact('success'), $data);
    }
}
