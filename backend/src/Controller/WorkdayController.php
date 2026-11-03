<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\WorkdayService;
use App\Entity\Workday;
use App\Util\DateTimeInputParser;
use Doctrine\Common\Collections\Collection;

final class WorkdayController extends AbstractController
{
    public function __construct(
        private WorkdayService $workdayService,
        private DateTimeInputParser $dateTimeInputParser,
    ) {
    }
    
    #[Route('/api/workdays', methods: ['GET'])]
    public function getWorkdays(): Response
    {
        $workdays = $this->workdayService->findAllWorkdays();

        $workdaysArray = [];
        foreach ($workdays as $workday) {
            $workdaysArray[] = $this->workdayToArray($workday);
        }

        return $this->json($workdaysArray, Response::HTTP_OK);
    }

    private function workdayToArray(Workday $workday): array
    {
        return [
            'date' => $workday->getDate(),
            'startTime' => $this->dateTimeInputParser->formatTime($workday->getStartTime()),
            'endTime' => $workday->getEndTime() ? $this->dateTimeInputParser->formatTime($workday->getEndTime()) : null,
            'pauses' => $this->formatPauses($workday->getPauses()),
        ];
    }

    private function formatPauses(Collection $pauses): array
    {
        $pausesArray = [];

        foreach ($pauses as $pause) {
            $pausesArray[] = [
                'startTime' => $this->dateTimeInputParser->formatTime($pause->getStartTime()),
                'endTime' => $this->dateTimeInputParser->formatTime($pause->getEndTime()),
            ];
        }

        return $pausesArray;
    }
}