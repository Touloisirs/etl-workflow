<?php

namespace Touloisirs\ETLWorkflow\Extractor;

use GuzzleHttp\ClientInterface;
use Touloisirs\ETLWorkflow\Context\ContextInterface;

abstract class AbstractGuzzleExtractor extends AbstractExtractor
{
    public function __construct(private ClientInterface $guzzle, private readonly string $apiUrl)
    {
    }

    /**
     * @return array<mixed>
     */
    abstract protected function prepareData(string $data): array;

    public function prepare(array $params = []): void
    {
        $apiData = $this->guzzle->request('GET', $this->apiUrl)->getBody()->getContents();
        $this->data = $this->prepareData($apiData);
    }

    public function extract(ContextInterface $context): mixed
    {
        if (empty($this->data)) {
            return null;
        }

        return array_shift($this->data);
    }
}
