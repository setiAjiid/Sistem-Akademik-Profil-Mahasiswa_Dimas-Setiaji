<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // @vite dan @fonts butuh public/build/manifest.json hasil `pnpm build`.
        // Test tidak mengecek aset, jadi Vite dimatikan supaya test tetap jalan tanpa build.
        $this->withoutVite();
    }
}
