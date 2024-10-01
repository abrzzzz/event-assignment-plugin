<?php

use Database\Migrations\EventUserAttendances;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Wpint\Support\Facades\Migration;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        DB::setDefaultConnection('mysql_testing');
        Migration::up([
            EventUserAttendances::class
        ]);
    }
}
