<?php

namespace Bookeen\ETLWorkflow\Workflow;

use Bookeen\ETLWorkflow\Context\ContextInterface;
use Bookeen\ETLWorkflow\Event\WorkflowEvent;
use Bookeen\ETLWorkflow\Extractor\ExtractorAbstract;
use Bookeen\ETLWorkflow\Loader\LoaderInterface;
use Bookeen\ETLWorkflow\Transformer\TransformerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class Workflow
{
    private ExtractorAbstract $extractor;

    private TransformerInterface $transformer;

    private LoaderInterface $loader;

    private ContextInterface$context;

    private EventDispatcherInterface $dispatcher;

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    public function setContext(ContextInterface $context)
    {
        $this->context = $context;
    }

    public function getExtractor(): ExtractorAbstract
    {
        return $this->extractor;
    }

    public function setExtractor(ExtractorAbstract $extractor)
    {
        $this->extractor = $extractor;
    }

    public function getLoader(): LoaderInterface
    {
        return $this->loader;
    }

    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function getTransformer(): TransformerInterface
    {
        return $this->transformer;
    }

    public function setTransformer(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function process()
    {
        if ($this->dispatcher !== null) {
            $this->dispatcher->dispatch(new GenericEvent($this->context), WorkflowEvent::WORKFLOW_START);
        }

        while (null !== $extracted = $this->extractor->extract($this->context)) {
            $transformed = $this->transformer->transform($extracted, $this->context);
            $this->loader->load($transformed, $this->context);

            if ($this->dispatcher !== null) {
                $this->dispatcher->dispatch(new GenericEvent($this->context), WorkflowEvent::WORKFLOW_ADVANCE);
            }
        }

        $this->loader->flush($this->context);
        $this->extractor->purge($this->context);

        if ($this->dispatcher !== null) {
            $this->dispatcher->dispatch(new GenericEvent($this->context), WorkflowEvent::WORKFLOW_FINISH);
        }
    }
}
