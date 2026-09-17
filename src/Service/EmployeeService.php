<?php


namespace App\Service;

use App\Entity\Attendence;
use App\Entity\Employee;
use DomainException;
use InvalidArgumentException;

class EmployeeService {

    function __construct()
    {
        
    }

    function initCurrentDay() {
        
    }


    /**
     * checks if employee have a working hours for the specific attenence
     * @param Employee $employee
     * @param Attendence $attendence
     * @throws InvalidArgumentException
     * @return bool
     */
    function isEligitimate(Employee $employee,Attendence $attendence) {
        if ( $employee->getAttendences()->exists(
            fn( Attendence $a)=>$attendence->getId() === $a->getId()
        ) ) throw new InvalidArgumentException("attendence must be related to employee");
        
        $workingHours = $employee->getWorkingHours();
        return $attendence->getWorkingHours() >= $workingHours;
    }
    

}