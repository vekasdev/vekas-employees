<?php


namespace  App\Controller;

use App\Entity\Employee;
use App\Form\ReportType;
use App\Service\MonthService;
use App\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/report")]
class ReportsController extends AbstractController {
    function __construct(
        private ReportService $reportService
    )
    {}

    /**
     *  gives report of specific employee at specific date
     */
    #[Route("/emp/{id}/{year}/{month}","report_employee")]
    function employeeReport(Employee $employee,$year,$month) {
        $days = $this->reportService->getEmployeeData($employee,$month,$year);
        return $this->render("report/employee_report.html.twig",[
            "days" => $days,
            "employee" => $employee
        ]);
    }


    #[Route("/choose/{id}","report_employee_choose")]
    function employeeReportChoosing(Employee $employee, Request $request,MonthService $monthService) {
        $formBuilder = $this->createFormBuilder();
        $registeredDates = $monthService->getActivatedMonths();
        
        $years = array_combine(array_keys($registeredDates),array_keys($registeredDates));
        $months = [];

        foreach($registeredDates as  $months_) {
            foreach($months_ as $month_) {
                $months[$month_] = $month_;
            }
        }

        $formBuilder->add("years", ChoiceType::class,[
            "choices" => $years
        ]);

        $formBuilder->add("months",ChoiceType::class,[
            "choices" => array_unique($months)
        ]);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $year = $data["years"];
            $month = $data["months"];

            return $this->redirectToRoute("report_employee",[
                "id" => $employee->getId(),
                "year" => $year,
                "month" => $month
            ]);
        }

        return $this->render("report/select_report_data.html.twig",[
            "form" => $form,
        ]);
        
    }

    #[Route("/report/make",name:"submit_report")]
    function submitReport(Request $request) {
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->redirectToRoute("report_all",[
                "month" => $data["month"],
                "year" => $data["year"]
            ]);
        }

        return $this->render("report/report.html.twig",[
            "form" => $form->createView()
        ]);
    }

    #[Route("/{month}/{year}","report_all",requirements:["month"=>"\d+","year"=>"\d+"])]
    function makeReport(int $month,int $year,ReportService $reportService) {
        $report = $reportService->getAllEmployeesReport($month,$year);
        return $this->file($report,"report-file-$year-$month".uniqid().".csv");
    }

}