<?php

namespace Touloisirs\ETLWorkflow\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Throwable;
use Touloisirs\ETLWorkflow\Event\WorkflowProgressBarSubscriber;
use Touloisirs\ETLWorkflow\Workflow\Workflow;

class ETLCommand extends Command
{
    public function __construct(
        protected Workflow $workflow,
        protected EventDispatcherInterface $eventDispatcher,
        protected LoggerInterface $logger,
        string $name,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('Starting import');

        $params = [];

        try {
            $this->runWorkflow($params, $output);
        } catch (Throwable $e) {
            $symfonyStyle->newLine(2);
            $errorMessage = 'Import failed: '.$e->getMessage();
            $symfonyStyle->error($errorMessage);
            $this->logger->error($errorMessage, [
                'exception' => $e,
            ]);

            return Command::FAILURE;
        }

        $symfonyStyle->newLine(2);
        $symfonyStyle->success('Import completed successfully');

        return Command::SUCCESS;
    }

    /**
     * @param array<string, mixed> $params
     */
    private function runWorkflow(array $params, OutputInterface $output): void
    {
        $this->workflow->getExtractor()?->prepare($params);
        $this->workflow->getLoader()?->prepare($params);

        // Todo: update this
        // if (method_exists($this->workflow->getContext(), 'prepare')) {
        //     $this->workflow->getContext()->prepare($params);
        // }

        // Todo : handle progress bar
        $this->eventDispatcher->addSubscriber(new WorkflowProgressBarSubscriber($output));
        $this->workflow->setDispatcher($this->eventDispatcher);

        $this->workflow->process();
        if ($this->workflow->getContext()) {
            $this->workflow->getLoader()?->clear($this->workflow->getContext());
        }
    }
}
