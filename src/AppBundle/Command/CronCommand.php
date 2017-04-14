<?php

namespace AppBundle\Command;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends ContainerAwareCommand
{
    /** @var InputInterface */
    private $input;
    /** @var OutputInterface */
    private $output;

    protected function configure()
    {
        $this
            ->setName('app:cron')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $statusService = $this->getContainer()->get("app.status");
        // we get either prod or dev or test path
        $commitsHistory = file($this->getContainer()->getParameter("kernel.cache_dir")."/../commits.history");
        $client = new Client();
        // https://pages.github.com/
        $response = $client->get("https://thecatontheflat.github.io/atlassian-vendor-tools.json");
        $updates = [];
        $updatesJson = $response->json();
        foreach ($updatesJson as $hash=>$update) {
            if(!in_array($hash,$commitsHistory)) {
                $updates[] = $update;
            }
        }
        if(count($updates)) {
            $output->writeln("Located ".count($updates)." updates");
            $statusService->setAvailableUpdates($updates);
        }
        $statusService->cronDone();
    }
}