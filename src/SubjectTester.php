<?php

declare(strict_types=1);

namespace Pest\Let;

use Closure;
use Pest\Let\Concern\HasLetVariables;
use Pest\PendingCalls\DescribeCall;

final class SubjectTester
{
    use HasLetVariables;

    private static ?SubjectTester $instance = null;

    public function __construct(
        protected Closure $subjectResolver,
    ) {
        self::$instance = $this;
    }

    public static function getInstance(): SubjectTester
    {
        return self::$instance ?? throw new \Exception('No subject test instance found, did you called subject()');
    }

    public function context(string $context, Closure $tests): DescribeCall
    {
        return describe($context, function () use ($tests) {
            return $tests();
        });
    }

    public function resolveSubject(): mixed
    {
        $subject = $this->subjectResolver;

        return $subject();
    }
}
