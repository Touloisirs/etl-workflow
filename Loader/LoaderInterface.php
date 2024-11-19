<?php

namespace Touloisirs\ETLWorkflow\Loader;

use Touloisirs\ETLWorkflow\Context\ContextInterface;

interface LoaderInterface
{
    /**
     * @param array<mixed> $params
     */
    public function prepare(array $params = []): void;

    public function load(mixed $data, ContextInterface $context): void;

    public function flush(ContextInterface $context): void;

    public function clear(ContextInterface $context): void;
}
