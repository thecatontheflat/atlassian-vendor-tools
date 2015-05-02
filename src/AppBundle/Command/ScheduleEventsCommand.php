<?php

namespace AppBundle\Command;

use AppBundle\Entity\ScheduledEvent;
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
        $this->getContainer()->get('app.scheduler')->setOutput($output)->schedule();
    }
}