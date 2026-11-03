<?php

namespace App\Controller;

use App\Entity\WeeklyDayOff;
use App\Form\WeeklyDayOffType;
use App\Repository\WeeklyDayOffRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/weeklydayoff')]
final class WeeklyDayOffController extends AbstractController
{
    #[Route(name: 'app_weekly_day_off_index', methods: ['GET'])]
    public function index(WeeklyDayOffRepository $weeklyDayOffRepository): Response
    {
        return $this->render('weekly_day_off/index.html.twig', [
            'weekly_day_offs' => $weeklyDayOffRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weekly_day_off_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weeklyDayOff = new WeeklyDayOff();
        $form = $this->createForm(WeeklyDayOffType::class, $weeklyDayOff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weeklyDayOff);
            $entityManager->flush();

            return $this->redirectToRoute('app_weekly_day_off_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weekly_day_off/new.html.twig', [
            'weekly_day_off' => $weeklyDayOff,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_weekly_day_off_delete', methods: ['GET'])]
    public function delete(Request $request, WeeklyDayOff $weeklyDayOff, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($weeklyDayOff);
        $entityManager->flush();
        return $this->redirectToRoute('app_weekly_day_off_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_weekly_day_off_show', methods: ['GET'])]
    public function show(WeeklyDayOff $weeklyDayOff): Response
    {
        return $this->render('weekly_day_off/show.html.twig', [
            'weekly_day_off' => $weeklyDayOff,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weekly_day_off_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WeeklyDayOff $weeklyDayOff, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeeklyDayOffType::class, $weeklyDayOff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weekly_day_off_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weekly_day_off/edit.html.twig', [
            'weekly_day_off' => $weeklyDayOff,
            'form' => $form,
        ]);
    }


}
