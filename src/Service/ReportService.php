<?php

namespace App\Service;

use App\Entity\Attendence;
use App\Entity\Employee;
use App\Factory\MonthDataFactory;
use App\Model\MonthData;
use App\Repository\AttendenceRepository;
use App\Repository\EmployeeRepository;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ReportService {
    function __construct(
        private AttendenceRepository $attendenceRepository,
        private EmployeeRepository $employeeRepository,
        private MonthDataFactory $monthDataFactory,
        private string $reportsDirectory
    )
    {
    }

    function getMonthlyReport(int $month,int $year,Employee $employee) {

        $attendences = $this->attendenceRepository->getByMonth($month,$year,$employee);
        $data = [];
        $totalWorkingHours = 0;
        $finalData = [];

        /** @var Attendence */
        foreach($attendences as $attendence) {
            $day = $attendence->getDate()->format("D");
            $workingHours = number_format($attendence->getWorkingHours(),2);
            $checkIn = $attendence->getCheckIn() ? $attendence->getCheckIn()->format("H:i A") : 'absent';
            $checkOut = $attendence->getCheckOut() ? $attendence->getCheckOut()->format("H:i A") : 'absent';
            $isEligitimate = $workingHours >= 8; // changing number
            $extraHours = (string) max($workingHours - 8 ,0);

            $totalWorkingHours += $workingHours;

            $data = [];
            $data[] = $employee->getFirstName()." ".$employee->getLastName();
            $data[] = $day;
            $data[] = $workingHours;
            $data[] = $checkIn;
            $data[] = $checkOut;
            $data[] = $isEligitimate ? "yes" : "no";
            $data[] = (string) $extraHours;

            $finalData[] = $data;
        }
        return $finalData;
    }

    function getAllEmployeesReport(int $month,int $year) {
        $employees = $this->employeeRepository->findAll();
        
        $spreadSheet = new Spreadsheet();

        $columns = [
            ["employee","day","working-hours","check-in","check-out","isEligitimate","extra-hours"]
        ];

        /** @var Employee */
        foreach($employees as $employee) {
            $report = $this->getMonthlyReport($month,$year,$employee);
            $columns = array_merge($columns,$report);
        }

        $sheet= $spreadSheet->getActiveSheet();
        $sheet->fromArray($columns);
        $xlsx = new Csv($spreadSheet);

        $reportName = uniqid()."report";

        $xlsx->save("$this->reportsDirectory/$reportName.csv");

        return $this->reportsDirectory."/".$reportName.".csv";
    }

    function getEmployeeData(Employee $employee ,int $month,int $year) { 
        $monthData = $this->monthDataFactory->createFromEmployee($employee,$year,$month);
        return $monthData->getDays();
    }

    function getEmployeeStatisticsForMonth() {

    }

    

}