<?php

namespace Touloisirs\ETLWorkflow\Extractor;

use Touloisirs\ETLWorkflow\Context\ContextInterface;

abstract class AbstractExtractor implements ExtractorInterface
{
    protected bool $purge = false;
    /** @var array<mixed> */
    protected array $data = [];

    /** @param array<mixed> $params */
    abstract public function prepare(array $params = []): void;

    abstract public function extract(?ContextInterface $context = null): mixed;

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
