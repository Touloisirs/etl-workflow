<?php

namespace Touloisirs\ETLWorkflow\Transformer;

use Touloisirs\ETLWorkflow\Context\ContextInterface;

interface TransformerInterface
{
    public function transform(mixed $data, ContextInterface $context): mixed;
}
