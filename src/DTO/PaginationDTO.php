<?php declare(strict_types = 1);

namespace App\DTO;

class PaginationDTO
{
    private int $entriesFrom;
    private int $entriesTo;
    private int $entriesTotal;
    private int $entriesSum;

    public function __construct(int $entriesFrom, int $entriesTo, int $entriesTotal, int $entriesSum)
    {
        $this->entriesFrom = $entriesFrom;
        $this->entriesTo = $entriesTo;
        $this->entriesTotal = $entriesTotal;
        $this->entriesSum = $entriesSum;
    }

    public function getEntriesFrom(): int
    {
        return $this->entriesFrom;
    }

    public function getEntriesTo(): int
    {
        return $this->entriesTo;
    }

    public function getEntriesTotal(): int
    {
        return $this->entriesTotal;
    }

    public function getEntriesSum(): int
    {
        return $this->entriesSum;
    }
}