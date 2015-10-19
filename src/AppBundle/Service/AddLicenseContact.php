<?php

namespace AppBundle\Service;


use AppBundle\Entity\License;
use GuzzleHttp\Client;
use Exception;
use GuzzleHttp\Exception\ClientException;

class AddLicenseContact
{
    private $apiDc;
    private $apiKey;
    private $listMap;

    public function __construct($apiKey, $listMap)
    {
        $this->apiKey = $apiKey;

        $keyParts = explode('-', $this->apiKey);
        $this->apiDc = $keyParts[count($keyParts)-1];

        $listMapTmp = explode(';', $listMap);

        $listMaps = array(count($listMapTmp));

        for ($i = 0; $i < count($listMapTmp); $i++) {
            $listMaps[$i] = explode(':',$listMapTmp[$i]);

            if (count($listMaps[$i]) != 2) {
                throw new Exception('Badly formed list_map in parameters.yml.');
            }
        }

        $this->listMap = $listMaps;
    }

    public function addFrom(License $license)
    {
        $keyParts = explode('-', $this->apiKey);
        $dc = $keyParts[count($keyParts) - 1];

        foreach ($this->listMap as $map) {
            if ($license->getAddonKey() == $map[0]) {
                $this->addMember($map[1], $license->getTechContactEmail(), $license->getTechContactName(), '');
            }
        }
    }

    private function addMember($listId, $email, $firstName, $lastName)
    {
        try {
            $client = new Client();
            $res = $client->post('https://' . $this->apiDc . '.api.mailchimp.com/3.0/lists/' . $listId . '/members', [
                'auth' => ['', $this->apiKey],
                'body' => '{"email_address": "' . $email . '","status": "subscribed","merge_fields": '
                    . '{"FNAME": "' . $firstName . '","LNAME": "' . $lastName . '"}'
                    . '}'
            ]);
        } catch (ClientException $e) {
            // just ignore these for now
        }
    }
}