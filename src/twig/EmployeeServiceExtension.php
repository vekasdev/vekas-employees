<?php

namespace App\twig;

use App\Entity\Employee;
use App\Service\EmployeeService;
use DateTime;
use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EmployeeServiceExtension extends AbstractExtension
{
    public function __construct(private EmployeeService $employeeService)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getLateDays', [$this, 'getLateDays']),
            new TwigFunction('getTotalWorkingHours', [$this, 'getTotalWorkingHours']),
            new TwigFunction('getAbsentDays', [$this, 'getAbsentDays']),
            new TwigFunction('getCommitmentPercentage', [$this, 'getCommitmentPercentage']),
        ];
    }

    public function getTotalWorkingHours(Employee $employee, string $period = 'all_time', ?DateTime $month = null)
    {
        return $this->employeeService->getTotalWorkingHours($employee, $period, $month);
    }

    public function getAbsentDays(Employee $employee, DateTimeInterface $from, DateTimeInterface $to): int
    {
        return $this->employeeService->getAbsentDays($employee, $from, $to);
    }

    public function getCommitmentPercentage(Employee $employee, DateTimeInterface $from, DateTimeInterface $to): float
    {
        return $this->employeeService->getCommitmentPercentage($employee, $from, $to);
    }

    public function getLateDays(Employee $employee, DateTimeInterface $from, DateTimeInterface $to): int
    {
        return $this->employeeService->getLateDays($employee, $from, $to);
    }
}
