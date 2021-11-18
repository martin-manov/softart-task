<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    /**
     * @param Employee $employee
     * @return int
     */
    public function getTotalVacationDays(Employee $employee): int
    {
        $startingDate = $employee->getStartingDate();
        $currentDate = new \DateTime();

        $diff = $startingDate->diff($currentDate);
        $months = $diff->y * 12 + $diff->m;
        return (int)round(1.7 * $months);
    }

    /**
     * @param Employee $employee
     * @return int
     */
    public function getUsedVacationDays(Employee $employee): int
    {
        $usedDays = 0;
        $vacations = $employee->getVacations();

        foreach ($vacations as $vacation) {
            $start = $vacation->getFromDate();
            $end = $vacation->getToDate();
            $end->modify('+1 day');

            $diff = $end->diff($start);
            $days = $diff->days;

            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

            foreach($period as $dt) {
                $curr = $dt->format('D');

                if ($curr === 'Sat' || $curr === 'Sun') {
                    $days--;
                }
            }

            $usedDays += $days;
        }

        return $usedDays;
    }
}
