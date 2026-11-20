<?php


namespace App\Controller;

use App\Entity\DayOff;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\DayOffRepository;
use DateTimeImmutable;

#[Route("/dayoff")]
class DayOffController extends AbstractController {
    function __construct()
    {}


    #[Route("/new", name:"day_off_new")]
    function new(Request $request ,EntityManagerInterface $em) {
       $dayOff = new DayOff();
       $form = $this->createForm(DayOff::class,$dayOff);

       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($dayOff);  
            $em->flush();    
        }
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: '/');
    }

    #[Route("/delete/{id}", name:"day_off_delete")]
    function delete(Request $request,DayOff $dayOff,EntityManagerInterface $em) {
        $em->remove($dayOff);
        $em->flush();
        $referer = $request->headers->get("referer");
        return $this->redirect($referer ?:"/");
    }

    #[Route("/toggle/{id}/{date}", name:"day_off_toggle", requirements:["date"=>"\\d{4}-\\d{2}-\\d{2}"], methods:["POST"])]
    function toggle(Employee $employee, string $date, Request $request, DayOffRepository $dayOffRepository, EntityManagerInterface $em): Response
    {
        $tokenId = 'toggle'.$employee->getId().'-'.$date;
        if (!$this->isCsrfTokenValid($tokenId, $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('رمز الطلب غير صالح.');
        }

        $day = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        $errors = DateTimeImmutable::getLastErrors();
        if ($day === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
            throw $this->createNotFoundException('التاريخ غير صالح.');
        }

        $existingDayOff = null;
        foreach ($employee->getDayOffs() as $dayOff) {
            if ($dayOff->getDate()?->format('Y-m-d') === $date) {
                $existingDayOff = $dayOff;
                break;
            }
        }

        if ($existingDayOff !== null) {
            $employee->removeDayOff($existingDayOff);
            if ($existingDayOff->getEmployees()->isEmpty()) {
                $em->remove($existingDayOff);
            }
        } else {
            foreach ($dayOffRepository->findByDate($day) as $dayOff) {
                if ($dayOff->getDate()?->format('Y-m-d') === $date) {
                    $existingDayOff = $dayOff;
                    break;
                }
            }

            if ($existingDayOff === null) {
                $existingDayOff = new DayOff();
                $existingDayOff->setDate($day);
            }

            $employee->addDayOff($existingDayOff);
            $em->persist($existingDayOff);
        }

        $em->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('report_employee', [
            'id' => $employee->getId(),
            'year' => $day->format('Y'),
            'month' => (int) $day->format('n'),
        ]));
    }
}