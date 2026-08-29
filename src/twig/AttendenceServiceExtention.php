<?php


namespace App\twig;

use App\Entity\Employee;
use App\Service\AttendenceService;
use App\Service\EmployeeService;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class AttendenceServiceExtention extends AbstractExtension {

    function __construct(private AttendenceService $attendenceService)
    {
        
    }
    #[Override]
    function getFunctions()
    {
        return [
            new TwigFunction(
                "getTodayAttendence",
                [$this,"getTodayAttendence"]
            )
        ];
    }

    function getTodayAttendence(Employee $employee)  {
        return $this->attendenceService->getTodayAttendence($employee);
    }
}