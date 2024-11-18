<?php

namespace Touloisirs\ETLWorkflow\Extractor;

use Touloisirs\ETLWorkflow\Context\ContextInterface;

abstract class ExtractorAbstract
{
    protected bool $purge = false;

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
