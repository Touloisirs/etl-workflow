<?php

namespace Bookeen\ETLWorkflow\Transformer;

use Bookeen\ETLWorkflow\Context\ContextInterface;

interface TransformerInterface
{
    function transform(mixed $data, ContextInterface $context): mixed;
}
