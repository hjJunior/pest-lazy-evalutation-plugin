<?php

declare(strict_types=1);

namespace Pest\Let;

use Closure;
use Pest\Expectation;
use Pest\Let\Concern\HasLetVariables;
use Pest\PendingCalls\BeforeEachCall;
use Pest\PendingCalls\DescribeCall;
use Pest\Plugin;
use Pest\Support\Backtrace;
use Pest\TestSuite;

Plugin::uses(HasLetVariables::class);

function subject(Closure $subject): void
{
    new SubjectTester($subject);
}

function let(string $key, Closure $resolver): BeforeEachCall
{
    $filename = Backtrace::testFile();

    return new BeforeEachCall(
        TestSuite::getInstance(),
        $filename,
        fn () => SubjectTester::getInstance()->let($key, $resolver),
    );
}

function get(string $key): mixed
{
    return SubjectTester::getInstance()->get($key);
}

function context(string $description, Closure $tests): DescribeCall
{
    return SubjectTester::getInstance()->context($description, $tests);
}

function expectSubject(): Expectation
{
    $tester = SubjectTester::getInstance();

    return expect($tester->resolveSubject());
}
