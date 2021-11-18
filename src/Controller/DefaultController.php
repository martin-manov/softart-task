<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Repository\VacationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController 
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        return $this->render('employee/list.html.twig', [
            'employees' => $employees,
        ]);
    }

    /**
     * @Route("/vacation-days/{id}", name="vacation_days")
     */
    public function vacationDaysAction(EmployeeRepository $employeeRepository, VacationRepository $vacationRepository, int $id): Response
    {
        $employee = $employeeRepository->find($id);
        $totalDays = $employeeRepository->getTotalVacationDays($employee);
        $usedDays = $employeeRepository->getUsedVacationDays($employee);
        $remainingDays = $totalDays - $usedDays;

        $vacations = $vacationRepository->findBy(['employee' => $employee]);

        return $this->render('employee/data.html.twig', [
            'employee' => $employee,
            'totalDays' => $totalDays,
            'usedDays' => $usedDays,
            'remainingDays' => $remainingDays,
            'vacations' => $vacations
        ]);
    }
}