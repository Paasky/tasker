<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Paasky\LaravelModelTest\TestsModels;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;
    use TestsModels;

    /*
     * Assert all model relations work and have back-relations (e.g. Task BelongsTo User, so User must HaveMany Tasks)
     */
    public function testModelRelations(): void
    {
        // Ignore cool new functions in Fortify that the package is incompatible with
        $this->ignoreMethodsPerNamespace['Laravel\\Fortify'] = [
            'recoveryCodes',
            'twoFactorQrCodeSvg',
            'twoFactorQrCodeUrl',
        ];
        $this->assertModels();
    }
}
