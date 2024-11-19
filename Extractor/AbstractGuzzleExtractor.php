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
     * @return array<string>
     */
    abstract protected function prepareData(string $data): array;

    public function extract(ContextInterface $context): mixed
    {
        $apiData = $this->guzzle->request('GET', $this->apiUrl)->getBody()->getContents();
        $preparedData = $this->prepareData($apiData);

        if (empty($preparedData)) {
            return null;
        }

        return array_shift($preparedData);
    }
}
