<?php

declare(strict_types=1);

namespace ExtractorMpsv;

use Keboola\Component\Config\BaseConfig;

class Config extends BaseConfig
{
    public function getUrl(): string
    {
        return 'https://portal.mpsv.cz/sz/stat/nz/mes';
    }


    public function isFileInTimeInterval(string $filename): bool
    {
        preg_match('~([0-9]{4})-([0-9]{2})~', $filename, $matches);
        $fileDateTime = (new \DateTime())->setDate((int) $matches[1], (int) $matches[2], 1);
        if ($this->getFromDateTime() <= $fileDateTime && $this->getToDateTime() >= $fileDateTime) {
            return true;
        }
        return false;
    }


    private function getFromDateTime(): \DateTime
    {
        return (new \DateTime())->setDate($this->getFromYear(), $this->getFromMonth(), 1);
    }

    private function getToDateTime(): \DateTime
    {
        return (new \DateTime())->setDate($this->getToYear(), $this->getToMonth(), 1);
    }

    private function getFromMonth(): int
    {
        return $this->getValue(['parameters', 'fromMonth']);
    }

    private function getFromYear(): int
    {
        return $this->getValue(['parameters', 'fromYear']);
    }

    private function getToMonth(): int
    {
        return $this->getValue(['parameters', 'toMonth']);
    }

    private function getToYear(): int
    {
        return $this->getValue(['parameters', 'toYear']);
    }
}
