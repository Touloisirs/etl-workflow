<?php

namespace Bookeen\ETLWorkflow\Loader;

use Bookeen\ETLWorkflow\Context\ContextInterface;

interface LoaderInterface
{
    function load(mixed $data, ContextInterface $context): void;

    function flush(ContextInterface $context): void;

    function clear(ContextInterface $context): void;
}

