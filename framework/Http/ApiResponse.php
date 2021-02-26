<?php


namespace Framework\Http;


class ApiResponse
{
    /**
     * @param array|string $data
     * @return array
     */
    public function ok($data = [])
    {
        return $this->response($data, true);
    }

    /**
     * @param array|string $data
     * @return array
     */
    public function error($data = [])
    {
        return $this->response($data, false);
    }


    public function response($data, ?bool $success = null)
    {
        Response::asJson();

        if (is_bool($data)) {
            $success = $data;
            $data = [];
        } elseif (is_string($data)) {
            $data = ['msg' => $data];
        }

        return array_merge(compact('success'), $data);
    }
}
