<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr\Values;

use InvalidArgumentException;

readonly class TestMethodInfo
{
    public ?string $dataProvider;

    public function __construct(
        public string $method,
        ?string $dataProvider = null
    ) {
        $this->dataProvider = $this->normaliseDataProvider($dataProvider);
    }

    private function normaliseDataProvider(?string $dataProvider): ?string
    {
        if ($dataProvider === null) {
            return null;
        }

        $replaced = (string)preg_replace('/-+/', '-', (string)preg_replace('/\W+/', '-', $dataProvider));

        if ($replaced === '') {
            throw new InvalidArgumentException('Invalid data provider name: ' . $dataProvider);
        }

        return trim(strtolower($replaced));
    }
}
