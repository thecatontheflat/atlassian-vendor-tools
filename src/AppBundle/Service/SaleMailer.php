<?php

namespace AppBundle\Service;


use AppBundle\Entity\Sale;

class SaleMailer
{
    /**
     * @var \Mandrill
     */
    private $mandrill;
    private $email;
    private $baseUrl;

    public function __construct(\Mandrill $mandrill, $email, $baseUrl)
    {
        $this->mandrill = $mandrill;
        $this->email = $email;
        $this->baseUrl = $baseUrl;
    }

    public function sendEmail(Sale $sale)
    {
        $message = [
            'html' => $this->getHTML($sale),
            'subject' => 'MPCRM - New Sale!',
            'from_email' => $this->email,
            'to' => [['email' => $this->email]],
            'track_clicks' => false,
            'track_opens' => false
        ];

        $this->mandrill->messages->send($message, true);
    }

    private function getHTML(Sale $sale)
    {
        $url = $this->baseUrl.'/license/'.$sale->getLicenseId();

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
            $sale->getVendorAmount(),
            $sale->getOrganisationName(),
            $sale->getSaleType(),
            $sale->getLicenseSize(),
            $sale->getLicenseId(),
            $url,
            $sale->getPluginName(),
            $sale->getDate()->format('Y-m-d'),
            $sale->getMaintenanceEndDate()->format('Y-m-d')
        );
    }
}