<?php

namespace Touloisirs\ETLWorkflow\Extractor;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Touloisirs\ETLWorkflow\Context\ContextInterface;

abstract class AbstractRestExtractor extends AbstractExtractor
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $httpMethod = 'GET',
    ) {
        $this->httpMethod = mb_strtoupper($httpMethod);
    }

    /** @return array<mixed> */
    abstract protected function prepareData(string $data): array;

    public function prepare(array $params = []): void
    {
        $options = match ($this->httpMethod) {
            'GET', 'DELETE' => ['query' => $params],
            default => ['body' => $params],
        };

        $apiData = $this->httpClient->request($this->httpMethod, '', $options)->getContent();
        $this->data = $this->prepareData($apiData);
    }

    public function extract(?ContextInterface $context = null): mixed
    {
        if (empty($this->data)) {
            return null;
        }

        return array_shift($this->data);
    }
}
