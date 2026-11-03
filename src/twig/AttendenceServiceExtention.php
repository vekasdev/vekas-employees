<?php


namespace App\twig;

use App\Entity\Attendence;
use App\Entity\Employee;
use App\Service\AttendenceService;
use App\Service\EmployeeService;
use LogicException;
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
            ),
            new TwigFunction(
                "isAttendenceLate",
                [$this,"isAttendenceLate"]
            )
        ];
    }

    function getTodayAttendence(Employee $employee)  {
        return $this->attendenceService->getTodayAttendence($employee);
    }

    /**
     * @param Attendence $attendence
     * @return mixed
     */
    function isAttendenceLate($attendence) {
        if (! $attendence instanceof Attendence) {
            return null;
        }
        try {
            $isLate = $this->attendenceService->isAttendenceLate($attendence);
        } catch(LogicException $e) {
            $isLate = false;
        }
        return $isLate;
    }

}