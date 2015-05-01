<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScheduleEventsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:events:schedule');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $events = $this->getContainer()->getParameter('events');
        $repository = $this->getContainer()->get('doctrine')->getRepository('AppBundle:License');

        foreach ($events as $event) {
            foreach ($repository->findByEvent($event) as $license) {

                $message = sprintf('%s: %s scheduled', $license->getLicenseId(), $event['name']);

                $output->writeln($message);
            }
        }

        $output->writeln('Done');
    }
}