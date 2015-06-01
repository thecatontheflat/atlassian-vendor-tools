<?php

namespace AppBundle\Command;

use AppBundle\Entity\DrillRegisteredEvent;
use AppBundle\Entity\License;
use AppBundle\Service\MandrillMessage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mandrill;

class SendScheduledEventsCommand extends ContainerAwareCommand
{
    /** @var $input InputInterface */
    private $input;
    private $output;

    protected function configure()
    {
        $this->setName('app:events:send');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;

        $mandrill = $this->getContainer()->get('app.mandrill');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $licenseRepo = $em->getRepository('AppBundle:License');
        $drillRegisteredEventRepo = $em->getRepository('AppBundle:DrillRegisteredEvent');

        $eventsToSend = $drillRegisteredEventRepo->findEventsToSendToday();
        foreach ($eventsToSend as $eventToSend) {
            $registeredSchema = $eventToSend->getDrillRegisteredSchema();
            $license = $licenseRepo->findOneBy([
                'licenseId' => $registeredSchema->getLicenseId(),
                'addonKey' => $registeredSchema->getAddonKey()
            ]);

            $recipients = $this->getRecipients($license);
            $bcc = $this->getContainer()->getParameter('vendor_email');
            $event = $eventToSend->getDrillSchemaEvent();

            $message = MandrillMessage::prepareMessage($license, $event, $recipients, $bcc);

            try {
                $response = $mandrill->messages->send($message, true);
                print_r($response);

                $eventToSend->setStatus('sent');
                $output->writeln(sprintf('[%s] %s - sent', $registeredSchema->getLicenseId(), $registeredSchema->getAddonKey()));
            } catch (\Exception $e) {
                $eventToSend->setStatus('error');

                $output->writeln($e->getMessage());
            }

            $em->persist($eventToSend);
            $em->flush();
        }

        $output->writeln('Done');
    }

    private function getRecipients(License $license)
    {
        $recipients = [];
        if ($this->input->getOption('env') == 'prod') {
            $recipients[] = [
                'email' => $license->getTechContactEmail(),
                'name' => $license->getTechContactName()
            ];

            if ($license->getTechContactEmail() != $license->getBillingContactEmail()) {
                $recipients[] = [
                    'email' => $license->getBillingContactEmail(),
                    'name' => $license->getBillingContactName()
                ];
            }
        } else {
            $recipients[] = ['email' => $this->getContainer()->getParameter('vendor_email')];
        }

        return $recipients;
    }
}