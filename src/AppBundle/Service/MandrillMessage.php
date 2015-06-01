<?php

namespace AppBundle\Service;

use AppBundle\Entity\DrillSchemaEvent;
use AppBundle\Entity\License;

class MandrillMessage
{
    public static function prepareMessage(License $license, DrillSchemaEvent $event, $recipients, $bcc = null)
    {
        $html = $event->getEmailTemplate();
        $subject = $event->getEmailSubject();

        self::replaceTemplateVariables($html, $license);
        self::replaceTemplateVariables($subject, $license);

        $message = [
            'subject' => $subject,
            'from_email' => $event->getEmailFromEmail(),
            'from_name' => $event->getEmailFromName(),
            'to' => $recipients,
            'html' => $html
        ];

        if ($bcc) {
            $message['bcc'] = $bcc;
        }

        return $message;
    }

    protected static function replaceTemplateVariables(&$html, License $license)
    {
        $mapping = [
            '%_TECH_CONTACT_%' => $license->getTechContactName(),
            '%_ADDON_NAME_%' => $license->getAddonName(),
            '%_ADDON_KEY_%' => $license->getAddonKey(),
            '%_LICENSE_ID_%' => $license->getLicenseId(),
            '%_ADDON_URL_%' => self::buildAddonURL($license->getAddonKey()),
            '%_LICENSE_START_DATE_%' => $license->getStartDate()->format('Y-m-d'),
            '%_LICENSE_END_DATE_%' => $license->getEndDate()->format('Y-m-d'),
        ];

        foreach ($mapping as $token => $replacement) {
            $html = str_replace($token, $replacement, $html);
        }
    }

    protected static function buildAddonUrl($addonKey)
    {
        $base = 'https://marketplace.atlassian.com/plugins/';
        $addonKey = str_replace('.ondemand', '', $addonKey);
        $url = $base.$addonKey;

        return $url;
    }
}