<?php


namespace  App\Controller;

use App\Form\ReportType;
use App\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class ReportsController extends AbstractController {
    function __construct()
    {
        
    }

    #[Route("/report/{month}/{year}","report_all",requirements:["month"=>"\d+","year"=>"\d+"])]
    function makeReport(int $month,int $year,ReportService $reportService) {
        $report = $reportService->getAllEmployeesReport($month,$year);
        return $this->file($report,"report-file-$year-$month".uniqid().".csv");
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

}