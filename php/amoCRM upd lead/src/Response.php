<?php

class Response
{
    const FAIL = 'fail';
    const SUCCESS = 'success';
    public static function json($status, $message)  {
        return json_encode(['status' => $status, 'message' => $message]);
    }
}