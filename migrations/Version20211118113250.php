<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Employee;
use App\Entity\Vacation;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class Version20211118113250 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $employees = [
        [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'position' => 'programmer',
            'startingDate' => '2018-03-01',
            'vacations' => [
                [
                    'fromDate' => '2020-12-31',
                    'toDate' => '2020-12-31'
                ],
                [
                    'fromDate' => '2021-06-01',
                    'toDate' => '2021-06-15'
                ]
            ]
        ],
        [
            'firstName' => 'Nikolay',
            'lastName' => 'Nikolaev',
            'position' => 'programmer',
            'startingDate' => '2018-08-01',
            'vacations' => [
                [
                    'fromDate' => '2020-04-13',
                    'toDate' => '2020-04-16'
                ],
                [
                    'fromDate' => '2021-01-04',
                    'toDate' => '2021-01-06'
                ],
                [
                    'fromDate' => '2021-07-05',
                    'toDate' => '2021-07-09'
                ]
            ]
        ],
        [
            'firstName' => 'Dimitar',
            'lastName' => 'Dimitrov',
            'position' => 'programmer',
            'startingDate' => '2019-06-01'
        ],
        [
            'firstName' => 'Aneliya',
            'lastName' => 'Anelieva',
            'position' => 'accountant',
            'startingDate' => '2019-12-01',
            'vacations' => [
                [
                    'fromDate' => '2020-12-31',
                    'toDate' => '2020-12-31'
                ],
                [
                    'fromDate' => '2021-02-08',
                    'toDate' => '2021-02-12'
                ],
                [
                    'fromDate' => '2021-10-18',
                    'toDate' => '2021-10-22'
                ]
            ]
        ],
        [
            'firstName' => 'Borislava',
            'lastName' => 'Borislavova',
            'position' => 'designer',
            'startingDate' => '2020-10-01'
        ],
    ];

    public function getDescription(): string
    {
        return 'insert data';
    }

    /**
     * @param Schema $schema
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        foreach ($this->employees as $employeeData) {
            $employee = new Employee();

            if (isset($employeeData['vacations'])) {
                foreach ($employeeData['vacations'] as $vacationData) {
                    $vacation = (new Vacation())
                        ->setFromDate(new \DateTime($vacationData['fromDate']))
                        ->setToDate(new \DateTime($vacationData['toDate']))
                        ->setEmployee($employee)
                    ;

                    $em->persist($vacation);
                    $employee->addVacation($vacation);
                }
            }

            $employee->setFirstName($employeeData['firstName'])
                ->setLastName($employeeData['lastName'])
                ->setStartingDate(new \DateTime($employeeData['startingDate']))
                ->setposition($employeeData['position'])
            ;

            $em->persist($employee);
            $em->flush();
        }
    }

    public function down(Schema $schema): void
    {
        return;
    }
}
