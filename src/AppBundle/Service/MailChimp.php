<?php

namespace AppBundle\Service;

use AppBundle\Entity\License;
use GuzzleHttp\Client;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Console\Output\OutputInterface;

class MailChimp
{
    private $apiKey;
    private $lists;
    private $dc;
    private $enabled;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct($apiKey, $lists, $dc, $enabled)
    {
        $this->lists = $lists;
        $this->dc = $dc;
        $this->apiKey = $apiKey;
        $this->enabled = $enabled;
    }

    /**
     * Adds new subscriber to the mailchimp list
     *
     * Works only if the license is new (checks for the empty id)
     *
     * @param License $license
     */
    public function addToList(License $license)
    {
        if (!$this->enabled) {
            return;
        }

        foreach ($this->lists as $list) {
            if ($license->isNew() && $license->getAddonKey() == $list['addon_key']) {
                $this->add($list['list_id'], $license);
            }
        }
    }

    private function add($listId, License $license)
    {
        $body = [
            'email_address' => $license->getTechContactEmail(),
            'status' => 'subscribed',
            'merge_fields' => [
                'FNAME' => $license->getTechContactName()
            ]
        ];
        $headers = [
            'auth' => ['', $this->apiKey],
            'body' => json_encode($body)
        ];

        $url = 'https://' . $this->dc . '.api.mailchimp.com/3.0/lists/' . $listId . '/members';

        try {
            $client = new Client();
            $client->post($url, $headers);

            $this->output->writeln('Added '.$license->getTechContactEmail().' to mailchimp list '.$listId);
        } catch (ClientException $e) {
            $err = $e->getMessage();
            if ($e->hasResponse()) {
                $err = $e->getResponse()->getBody();
            }
            $this->output->writeln('Failed to add '.$license->getTechContactEmail().' to mailchimp list '.$listId.' due to error: '.$err);
        }
    }

    /**
     * @param OutputInterface $output
     * @return $this
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }
}