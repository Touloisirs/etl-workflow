<?php

namespace Touloisirs\ETLWorkflow\Workflow;

use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Touloisirs\ETLWorkflow\Context\ContextInterface;
use Touloisirs\ETLWorkflow\Event\WorkflowEvent;
use Touloisirs\ETLWorkflow\Extractor\AbstractExtractor;
use Touloisirs\ETLWorkflow\Loader\LoaderInterface;
use Touloisirs\ETLWorkflow\Transformer\TransformerInterface;

class Workflow
{
    private ?AbstractExtractor $extractor = null;

    private ?TransformerInterface $transformer = null;

    private ?LoaderInterface $loader = null;

    private ?ContextInterface $context = null;

    private ?EventDispatcherInterface $dispatcher = null;

    public function setDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }

    public function getContext(): ?ContextInterface
    {
        return $this->context;
    }

    public function setContext(ContextInterface $context): void
    {
        $this->context = $context;
    }

    public function getExtractor(): ?AbstractExtractor
    {
        return $this->extractor;
    }

    public function setExtractor(AbstractExtractor $extractor): void
    {
        $this->extractor = $extractor;
    }

    public function getLoader(): ?LoaderInterface
    {
        return $this->loader;
    }

    public function setLoader(LoaderInterface $loader): void
    {
        $this->loader = $loader;
    }

    public function getTransformer(): ?TransformerInterface
    {
        return $this->transformer;
    }

    public function setTransformer(TransformerInterface $transformer): void
    {
        $this->transformer = $transformer;
    }

    public function process(): void
    {
        if (null === $this->extractor) {
            throw new Exception('Extractor is not set');
        }

        if (null === $this->transformer) {
            throw new Exception('Transformer is not set');
        }

        if (null === $this->loader) {
            throw new Exception('Loader is not set');
        }

        if (null === $this->context) {
            throw new Exception('Context is not set');
        }

        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch(new GenericEvent($this->context), WorkflowEvent::WORKFLOW_START);
        }

        while (null !== $extracted = $this->extractor->extract($this->context)) {
            $transformed = $this->transformer->transform($extracted, $this->context);
            $this->loader->load($transformed, $this->context);

            if (null !== $this->dispatcher) {
                $this->dispatcher->dispatch(new GenericEvent($this->context), WorkflowEvent::WORKFLOW_ADVANCE);
            }
        }

        $this->loader->flush($this->context);
        $this->extractor->purge($this->context);

        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch(new GenericEvent($this->context), WorkflowEvent::WORKFLOW_FINISH);
        }
    }
}
