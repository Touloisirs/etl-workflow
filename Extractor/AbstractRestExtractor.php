<?php

namespace Touloisirs\ETLWorkflow\Extractor;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Touloisirs\ETLWorkflow\Context\ContextInterface;

abstract class AbstractRestExtractor extends AbstractExtractor
{
    /** @param array<string> $apiParams */
    public function __construct(
        private HttpClientInterface $httpClient,
        private readonly string $apiUrl,
        private readonly array $apiParams = [],
    ) {
    }

    /** @return array<mixed> */
    abstract protected function prepareData(string $data): array;

    public function prepare(array $params = []): void
    {
        $apiData = $this->httpClient->request('GET', $this->apiUrl, ['query' => $this->apiParams])->getContent();
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
