<?php


namespace  App\Controller;

use App\Entity\Employee;
use App\Form\ReportType;
use App\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/report")]
class ReportsController extends AbstractController {
    function __construct(
        private ReportService $reportService
    )
    {}

    /**
     *  gives report of specific employee
     */
    #[Route("/emp/{id}")]
    function employeeReport(Employee $employee) {
        $days = $this->reportService->getEmployeeData($employee,9,2026);
        return $this->render("report/employee_report.html.twig",[
            "days" => $days,
            "employee" => $employee
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