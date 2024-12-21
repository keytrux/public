<?php
class Bitrix
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $hash;
    /**
     * @var string
     */
    private $domain;


    /**
     * Bitrix constructor.
     * @param string $domain
     * @param int $id
     * @param string $hash
     */
    public function __construct(string $domain, int $id, string $hash)
    {
        $this->domain = $domain;
        $this->id = $id;
        $this->hash = $hash;
    }

    /**
     * @param string $method
     * @param array|string $data
     * @return mixed
     */
    private function cUrl(string $method, $data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => "https://$this->domain/rest/$this->id/$this->hash/$method",
            CURLOPT_POSTFIELDS => $data,
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, true);
    }

    /**
     * @param $dealId
     * @return stdClass|null
     */
    public function getDeal($dealId)
    {
        $method = 'crm.deal.get';
        $queryData = http_build_query([
            'ID' => $dealId
        ]);
        return $this->cUrl($method, $queryData);
    }

    /**
     * @param $leadId
     * @return stdClass|null
     */
    public function getLead($leadId)
    {
        $method = 'crm.lead.get';
        $queryData = http_build_query([
            'ID' => $leadId
        ]);
        return $this->cUrl($method, $queryData);
    }

    public function getLineChat($token)
    {
        $method = 'im.chat.get';
        $queryData = http_build_query([
            'ENTITY_TYPE' => 'LINES',
            'ENTITY_ID' => $token
        ]);
        return $this->cUrl($method, $queryData);
    }

    public function getMessagesFromChat($chatId)
    {
        $method = 'im.dialog.messages.get';
        $queryData = http_build_query([
            'DIALOG_ID' => $chatId,
            'FIRST_ID' => 0
        ]);
        return $this->cUrl($method, $queryData);
    }

    public function updateLead(int $leadId, array $fields)
    {
        $method = 'crm.lead.update';
        $queryData = http_build_query([
            'id' => $leadId,
            'fields' => $fields,
            'params' => [
                'REGISTER_SONET_EVENT' => 'N'
            ],
        ]);
        return $this->cUrl($method, $queryData);
    }
}

?>