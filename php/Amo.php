<?php

class Amo
{
    private string $domain;
    private string $token;

    /**
     * @param $domain
     * @param $token
     */
    public function __construct($domain, $token)
    {
        $this->domain = $domain;
        $this->token = $token;
    }

    /**
     * @param string $url
     * @param string $method
     * @return string
     */
    public function sendRequest(string $url, string $method, array $data = null): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $this->token",
            ),
        ));

        if ($method === 'PATCH') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * @param int $id
     * @return void
     */
    public function getLeadById(int $id): array
    {
        $url = "https://$this->domain.amocrm.ru/api/v4/leads/$id?with=contacts";
        $response = $this->sendRequest($url, 'GET');
        return json_decode($response, true);
    }

    public function getContactById(int $id): array
    {
        $url = "https://$this->domain.amocrm.ru/api/v4/contacts/$id";
        $response = $this->sendRequest($url, 'GET');
        return json_decode($response, true);
    }

    /**
     * @param int $id
     * @param int $roistat
     * @return array
     */
    public function updateLead(int $id, int $roistat): array
    {
        $url = "https://$this->domain.amocrm.ru/api/v4/leads/$id";
        $data = [
            'custom_fields_values' => [
                [
                    'field_id' => 785423,
                    'values' => [
                        [
                            'value' => $roistat,
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->sendRequest($url, 'PATCH', $data);
        return json_decode($response, true);
    }

    public function updateLeadUrl(int $id, int $url): array
    {
        $url_main = "https://$this->domain.amocrm.ru/api/v4/leads/$id";
        $data = [
            'custom_fields_values' => [
                [
                    'field_id' => 930089,
                    'values' => [
                        [
                            'value' => $url,
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->sendRequest($url_main, 'PATCH', $data);
        return json_decode($response, true);
    }
}