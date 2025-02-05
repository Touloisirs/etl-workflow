<?php

namespace Touloisirs\ETLWorkflow\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Touloisirs\ETLWorkflow\Workflow\Workflow;

class ETLCommand extends Command
{
    public function __construct(
        protected Workflow $workflow,
        protected EventDispatcherInterface $eventDispatcher,
        string $name,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('Starting import');

        $params = [];

        $this->runWorkflow($params);

        $symfonyStyle->success('Import completed successfully');

        return Command::SUCCESS;
    }

    /**
     * @param array<string, mixed> $params
     */
    private function runWorkflow(array $params): void
    {
        $this->workflow->getExtractor()?->prepare($params);
        $this->workflow->getLoader()?->prepare($params);
        // if (method_exists($this->workflow->getContext(), 'prepare')) {
        //     $this->workflow->getContext()->prepare($params);
        // }

        // // Display the progress bar only in dev
        // if ('prod' != $this->appEnv) {
        //     $this->eventDispatcher->addSubscriber(new WorkflowProgressBarSubscriber($output));
        //     $this->workflow->setDispatcher($this->eventDispatcher);
        // }

        $this->workflow->process();
        if ($this->workflow->getContext()) {
            $this->workflow->getLoader()?->clear($this->workflow->getContext());
        }
    }
}
