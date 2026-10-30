<?php

namespace App\Entity;

use App\Repository\WorkdayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Util\DateTimeInputParser;

#[ORM\Entity(repositoryClass: WorkdayRepository::class)]
class Workday
{
    #[ORM\Id]
    #[ORM\Column(length: 10)]  // 'Y-m-d'
    private string $date;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private \DateTimeImmutable $startTime;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $endTime = null;

    /**
     * @var Collection<int, Pause>
     */
    #[ORM\OneToMany(targetEntity: Pause::class, mappedBy: 'workday', cascade: ['persist'], orphanRemoval: true)]
    private Collection $pauses;

    public function __construct(
        \DateTimeImmutable $date,
        \DateTimeImmutable $startTime,
        ?\DateTimeImmutable $endTime = null,
        Collection $pauses = new ArrayCollection(),
        private DateTimeInputParser $dateTimeInputParser = new DateTimeInputParser()
    )
    {
        $this->date = $this->dateTimeInputParser->formatDate($date);
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->pauses = $pauses;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getStartTime(): \DateTimeImmutable
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

    public function setEndTime(?\DateTimeImmutable $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @return Collection<int, Pause>
     */
    public function getPauses(): Collection
    {
        return $this->pauses;
    }

    public function addPause(Pause $pause): static
    {
        if (!$this->pauses->contains($pause)) {
            $this->pauses->add($pause);
            $pause->setWorkday($this);
        }

        return $this;
    }

    public function removePause(Pause $pause): static
    {
        if ($this->pauses->removeElement($pause)) {
            // set the owning side to null (unless already changed)
            if ($pause->getWorkday() === $this) {
                $pause->setWorkday(null);
            }
        }

        return $this;
    }
}
