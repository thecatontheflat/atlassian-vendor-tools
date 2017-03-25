<?php

namespace AppBundle\Service;

use AppBundle\Entity\Transaction;

class SaleMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    private $baseUrl;
    private $from;
    private $to;

    public function __construct(\Swift_Mailer $mailer, $baseUrl, $from, $to)
    {
        $this->baseUrl = $baseUrl;
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
    }

    public function sendEmail(Transaction $transaction)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('MPCRM - New Sale!')
            ->setFrom($this->from, 'MPCRM')
            ->setTo($this->to)
            ->setBody($this->getHTML($transaction), 'text/html');

        $this->mailer->send($message);
    }

    private function getHTML(Transaction $transaction)
    {
        $url = $this->baseUrl.'/license/'.$transaction->getLicense()->getLicenseId();

        $html = '<h1>Congrats!</h1>';
        $html .= '<p>Yet another license has been sold for <strong>$%s</strong></p>';
        $html .= '<p>License details:</p>';
        $html .= '<table>'.
            '<tr><td>Customer</td><td>%s</td></tr>'.
            '<tr><td>Type</td><td>%s</td></tr>'.
            '<tr><td>Size</td><td>%s</td></tr>'.
            '<tr><td>License ID</td><td>%s</td></tr>'.
            '<tr><td>Add-On</td><td><a href="%s">%s</a></td></tr>'.
            '<tr><td>Date</td><td>%s</td></tr>'.
            '<tr><td>Valid until</td><td>%s</td></tr>'.
            '</table>';

        return sprintf(
            $html,
            $transaction->getVendorAmount(),
            $transaction->getLicense()->getCompany()->getCompany(),
            $transaction->getSaleTypeStr(),
            $transaction->getTier(),
            $transaction->getLicense()->getLicenseId(),
            $url,
            $transaction->getLicense()->getAddon()->getAddonName(),
            $transaction->getSaleDate()->format('Y-m-d'),
            $transaction->getMaintenanceEndDate()->format('Y-m-d')
        );
    }
}