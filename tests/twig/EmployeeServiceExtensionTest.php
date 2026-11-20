<?php

namespace App\Tests\twig;

use App\Entity\Employee;
use App\Service\EmployeeService;
use App\twig\EmployeeServiceExtension;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

class EmployeeServiceExtensionTest extends TestCase
{
    public function test_registers_employee_service_twig_functions(): void
    {
        $employee = new Employee();
        $from = new DateTimeImmutable('2026-01-01');
        $to = new DateTimeImmutable('2026-01-31');
        $employeeService = $this->createMock(EmployeeService::class);
        $employeeService
            ->expects(self::once())
            ->method('getTotalWorkingHours')
            ->with($employee, 'all_time', null)
            ->willReturn(8.5);
        $employeeService
            ->expects(self::once())
            ->method('getAbsentDays')
            ->with($employee, $from, $to)
            ->willReturn(2);
        $employeeService
            ->expects(self::once())
            ->method('getCommitmentPercentage')
            ->with($employee, $from, $to)
            ->willReturn(75.0);
        $employeeService
            ->expects(self::once())
            ->method('getLateDays')
            ->with($employee, $from, $to)
            ->willReturn(3);

        $extension = new EmployeeServiceExtension($employeeService);
        $functions = [];
        foreach ($extension->getFunctions() as $function) {
            $functions[$function->getName()] = $function;
        }

        self::assertCount(4, $functions);
        self::assertContainsOnlyInstancesOf(TwigFunction::class, $functions);
        self::assertSame(8.5, ($functions['getTotalWorkingHours']->getCallable())($employee));
        self::assertSame(2, ($functions['getAbsentDays']->getCallable())($employee, $from, $to));
        self::assertSame(75.0, ($functions['getCommitmentPercentage']->getCallable())($employee, $from, $to));
        self::assertSame(3, ($functions['getLateDays']->getCallable())($employee, $from, $to));
    }
}
