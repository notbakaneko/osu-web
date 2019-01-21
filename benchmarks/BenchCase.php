<?php

namespace Benchmarks;

/**
 * @Revs(1000)
 * @Iterations(50)
 * @OutputTimeUnit("seconds")
 * @OutputMode("throughput")
 * @BeforeMethods({"init"})
 */
abstract class BenchCase
{
    protected $app;

    public $subject;

    public function init()
    {
        if (!$this->app) {
            $this->app = $this->createApplication();
        }

        $this->subject = $this->getSubject();
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    abstract public function getSubject();
}
