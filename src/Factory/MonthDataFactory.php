<?php

namespace App\Factory;

use App\Entity\Employee;
use App\Entity\Month;
use App\Model\MonthData;
use App\Repository\AttendenceRepository;
use App\Repository\EmployeeRepository;

class MonthDataFactory {

    function __construct(
        private AttendenceRepository $employeeRepository
    )
    {
        
    }


    /**
     * create a month data object that contain data of each day and it reporesents 
     * the employee state whether is attended or not or there is a day off
     * @param Employee $employee
     * @param mixed $year
     * @param mixed $month
     * @return MonthData
     */
    function createFromEmployee(Employee $employee,$year,$month) {
        $monthData = new MonthData($year,$month);
        $attendencies = $this->employeeRepository->getByMonth($month,$year,$employee);
        
        foreach($monthData->getDays() as $dayData) {

            foreach($attendencies as $attendence) {
                if ( $attendence->isSameDay($dayData->getDate()) ) {
                    $dayData->setAttendence($attendence);
                }
            }

            foreach($employee->getDayOffs() as $dayOff) {
                if ($employee->isDayOff($dayData->getDate()) ) {
                    $dayData->setOff(true);
                }
            }

        }

        return $monthData; 
    }

}