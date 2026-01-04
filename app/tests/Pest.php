<?php

use Tests\TestCase;

uses(TestCase::class)->in('API');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function something()
{

}
