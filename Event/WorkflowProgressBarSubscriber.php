<?php

namespace Bookeen\ETLWorkflow\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Contracts\EventDispatcher\Event;

class WorkflowProgressBarSubscriber implements EventSubscriberInterface
{
    private $progressBar;

    public function __construct(OutputInterface &$output, $count = 0)
    {
        $this->progressBar = new ProgressBar($output, $count);
        $this->progressBar->setFormat('debug');
    }

    static public function getSubscribedEvents(): array
    {
        return array(
            WorkflowEvent::WORKFLOW_START => array('onStart', 1),
            WorkflowEvent::WORKFLOW_ADVANCE => array('onAdvance', 0),
            WorkflowEvent::WORKFLOW_FINISH => array('onFinish', 0)
        );
    }

    public function onStart(Event $event): void
    {
        $this->progressBar->start();
    }

    public function onAdvance(Event $event): void
    {
        $this->progressBar->advance();
    }

    public function onFinish(Event $event): void
    {
        $this->progressBar->finish();
    }
}
