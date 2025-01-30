<?php

namespace Touloisirs\ETLWorkflow\Extractor;

use Touloisirs\ETLWorkflow\Context\ContextInterface;

abstract class AbstractCompositeExtractor extends AbstractExtractor
{
    /** @var ExtractorInterface[] */
    protected array $extractors = [];
    protected int $currentExtractorIndex = 0;

    public function addExtractor(ExtractorInterface $extractor): void
    {
        $this->extractors[] = $extractor;
    }

    /**
     * Called before preparing individual extractors.
     *
     * @param array<mixed> $params
     */
    protected function beforePrepare(array $params = []): void
    {
    }

    /**
     * Called after preparing individual extractors.
     *
     * @param array<mixed> $params
     */
    protected function afterPrepare(array $params = []): void
    {
    }

    /**
     * @param array<mixed> $params
     */
    public function prepare(array $params = []): void
    {
        $this->beforePrepare($params);

        foreach ($this->extractors as $extractor) {
            $extractor->prepare($params);
        }
        $this->currentExtractorIndex = 0;

        $this->afterPrepare($params);
    }

    /**
     * Called before extracting data from individual extractors.
     */
    protected function beforeExtract(ContextInterface $context): void
    {
    }

    /**
     * Called after extracting data from an individual extractor
     * Allows modifying the extracted data before returning it.
     */
    protected function afterExtract(mixed $data, ContextInterface $context): mixed
    {
        return $data;
    }

    public function extract(ContextInterface $context): mixed
    {
        $this->beforeExtract($context);

        while ($this->currentExtractorIndex < \count($this->extractors)) {
            $currentExtractor = $this->extractors[$this->currentExtractorIndex];
            $data = $currentExtractor->extract($context);

            if ($data !== null) {
                return $this->afterExtract($data, $context);
            }

            ++$this->currentExtractorIndex;
        }

        return null;
    }

    protected function doPurge(ContextInterface $context): bool
    {
        $result = true;
        foreach ($this->extractors as $extractor) {
            $result = $result && $extractor->purge($context);
        }

        return $result;
    }

    /**
     * @return ExtractorInterface[]
     */
    public function getExtractors(): array
    {
        return $this->extractors;
    }

    public function resetExtractors(): void
    {
        $this->extractors = [];
        $this->currentExtractorIndex = 0;
    }
}
