<?php


class Request
{
    private $error;
    public function validated($request) {
        if (!isset($request['leads']['add'][0]['id']) && !isset($request['message']['add'][0]['text'])) {
            $this->setError('this request not contain lead id or message text');
            return false;
        }
        return true;
    }

    private function setError($error) {
        $this->error = $error;
    }

    public function getError() {
        return $this->error;
    }
}