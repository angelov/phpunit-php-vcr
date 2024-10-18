<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr\Subscribers;

use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber;
use VCR\VCR;

class FinishRecording implements FinishedSubscriber
{
    use AttributeResolverTrait;

    public function notify(Finished $event): void
    {
        $test = $event->test()->id();

        if (!$this->needsRecording($test)) {
            return;
        }

        VCR::eject();
        VCR::turnOff();
    }
}
