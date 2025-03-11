<?php

declare(strict_types=1);

/**
 * This file is part of Angelov phpunit-vcr.
 *
 * (c) Angelov <https://angelovdejan.me>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Angelov\PHPUnitPHPVcr\Subscribers;

use PHPUnit\Event\TestRunner\ExecutionStarted;
use PHPUnit\Event\TestRunner\ExecutionStartedSubscriber;
use VCR\VCR;

class ConfigureRecorder implements ExecutionStartedSubscriber
{
    /**
     * @param list<string>|null $libraryHooks
     * @param list<string>|null $requestMatchers
     * @param list<string>|null $whitelistedPaths
     * @param list<string>|null $blacklistedPaths
     */
    public function __construct(
        private readonly ?string $cassettesPath,
        private readonly ?string $storage,
        private readonly ?array $libraryHooks,
        private readonly ?array $requestMatchers,
        private readonly ?array $whitelistedPaths,
        private readonly ?array $blacklistedPaths,
        private readonly ?string $mode,
    ) {
    }

    public function notify(ExecutionStarted $event): void
    {
        $configuration = VCR::configure();

        if ($this->cassettesPath) {
            $configuration->setCassettePath($this->cassettesPath);
        }

        if ($this->storage) {
            $configuration->setStorage($this->storage);
        }

        if ($this->libraryHooks) {
            $configuration->enableLibraryHooks($this->libraryHooks);
        }

        if ($this->requestMatchers) {
            $configuration->enableRequestMatchers($this->requestMatchers);
        }

        if ($this->whitelistedPaths) {
            $configuration->setWhiteList($this->whitelistedPaths);
        }

        if ($this->blacklistedPaths) {
            $configuration->setBlackList($this->blacklistedPaths);
        }

        if ($this->mode) {
            $configuration->setMode($this->mode);
        }
    }
}
