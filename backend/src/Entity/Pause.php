<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Pause
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private \DateTimeImmutable $startTime;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private \DateTimeImmutable $endTime;

    #[ORM\ManyToOne(inversedBy: 'pauses')]
    #[ORM\JoinColumn(name: 'workday_date', referencedColumnName: 'date', nullable: false)]
    private Workday $workday;

    public function __construct(\DateTimeImmutable $startTime, \DateTimeImmutable $endTime)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getWorkday(): ?Workday
    {
        return $this->workday;
    }

    public function setWorkday(?Workday $workday): static
    {
        $this->workday = $workday;

        return $this;
    }
}
