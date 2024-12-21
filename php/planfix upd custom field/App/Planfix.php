<?php

namespace App;

use App\Helpers\Request;

class Planfix
{
    private string $host = 'https://xxxx.planfix.ru/rest';
    private string $token;
    private int $fieldId = 29198;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function searchContactByPhone(string $phone)
    {
        $response = Request::send(
            "{$this->host}/contact/list",
            'POST',
            [
                'offset' => 0,
                'pageSize' => 10,
                'fields' => 'id',
                'filters' => [
                    [
                        'type' => 4003,
                        'operator' => 'equal',
                        'value' => $phone
                    ]
                ]
            ],
            'json',
            [],
            ["Authorization: Bearer {$this->token}"]
        );

        if ($response['result'] === 'success' && !empty($response['contacts'])) {
            return $response['contacts'][0];
        }

        return null;
    }

    public function getTaskComments(int $taskId)
    {
        $response = Request::send(
            "{$this->host}/task/{$taskId}/comments/list",
            'POST',
            [
                'offset' => 0,
                'pageSize' => 100,
                'fields' => 'id,description',
                'typeList' => 'Comments',
                'resultOrder' => 'Asc'
            ],
            'json',
            [],
            ["Authorization: Bearer {$this->token}"]
        );

        if ($response['result'] === 'success' && !empty($response['comments'])) {
            return $response['comments'];
        }

        return null;
    }

    public function setCounterParty(int $id, int $contactId): void
    {
        Request::send(
            "{$this->host}/task/{$id}",
            'POST',
            [
                'counterparty' => ['id' => $contactId]
            ],
            'json',
            ['silent' => true],
            ["Authorization: Bearer {$this->token}"]
        );
    }

    public function setVisit(int $id, string $visit): void
    {
        Request::send(
            "{$this->host}/task/{$id}",
            'POST',
            [
                'customFieldData' => [
                    [
                        'field' => ['id' => $this->fieldId],
                        'value' => $visit
                    ]
                ]
            ],
            'json',
            ['silent' => true],
            ["Authorization: Bearer {$this->token}"]
        );
    }
}