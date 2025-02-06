<?php

namespace Touloisirs\ETLWorkflow\Extractor;

use Touloisirs\ETLWorkflow\Context\ContextInterface;

interface ExtractorInterface
{
    /**
     * @param array<mixed> $params
     */
    public function prepare(array $params = []): void;

    public function extract(?ContextInterface $context = null): mixed;

    public function purge(ContextInterface $context): bool;
}
