<?php
namespace AppBundle\EventListener;

// http://symfony.com/doc/current/console/logging.html
// http://stackoverflow.com/questions/9307498/symfony-2-logging-exceptions-in-console

use AppBundle\Command\ImportLicenseCommand;
use AppBundle\Service\Status;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;

class ConsoleExceptionListener
{
    private $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function onConsoleException(ConsoleExceptionEvent $event)
    {
        $command = $event->getCommand();
        $exception = $event->getException();
        if($command instanceof ImportLicenseCommand) {
            $this->status->importLicenseException($exception);
        }elseif($command instanceof ImportLicenseCommand) {
            $this->status->importTransactionException($exception);
        }
    }
}
