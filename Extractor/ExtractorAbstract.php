<?php

namespace Bookeen\ETLWorkflow\Extractor;

use Bookeen\ETLWorkflow\Context\ContextInterface;

abstract class ExtractorAbstract
{
    protected $purge = false;

    abstract public function extract(ContextInterface $context): mixed;

    public function purge(ContextInterface $context): bool
    {
        if ($this->purge) {
            return $this->doPurge($context);
        }

        return true;
    }

    protected function doPurge(ContextInterface $context): bool
    {
        return true;
    }
}
