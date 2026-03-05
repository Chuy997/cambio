<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\OperatorRegistration;
use App\Models\ChangeRequest;
use App\Events\ChangeRequestCreated;
use App\Services\SystemAlertService;
use Mockery\MockInterface;

class ChangeRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Evitar que la configuración para el subdirectorio de Apache rompa
        // el enrutador virtual de testing de Livewire
        config(['livewire.asset_url' => null]);
    }

    public function test_operator_can_register_indirecto_change()
    {
        Event::fake([ChangeRequestCreated::class]);

        $this->mock(SystemAlertService::class, function (MockInterface $mock) {
            $mock->shouldReceive('sendWindowsAlert')->once()->andReturn(true);
        });

        Livewire::test(OperatorRegistration::class)
            ->set('rows', [
                ['box_name' => 'BOX-123', 'indirecto_code' => 'IND-001'],
                ['box_name' => 'BOX-456', 'indirecto_code' => 'IND-002'],
            ])
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSee('Requerimiento enviado exitosamente');

        $this->assertDatabaseCount('change_requests', 1);
        $this->assertDatabaseCount('change_request_items', 2);
        
        $this->assertDatabaseHas('change_request_items', ['box_name' => 'BOX-123']);
        $this->assertDatabaseHas('change_request_items', ['box_name' => 'BOX-456']);

        Event::assertDispatched(ChangeRequestCreated::class);
    }

    public function test_operator_cannot_register_duplicate_boxes_in_same_request()
    {
        Livewire::test(OperatorRegistration::class)
            ->set('rows', [
                ['box_name' => 'BOX-DUPLICATE', 'indirecto_code' => 'IND-001'],
                ['box_name' => 'BOX-DUPLICATE', 'indirecto_code' => 'IND-002'],
            ])
            ->call('submit')
            ->assertHasErrors('rows');

        $this->assertDatabaseCount('change_requests', 0);
    }

    public function test_monitor_dashboard_updates_status()
    {
        $request = ChangeRequest::create(['status' => 'pendiente']);
        $request->items()->create([
            'box_name' => 'TEST-BOX',
            'indirecto_code' => 'TEST-IND'
        ]);

        Livewire::test(\App\Livewire\MonitorDashboard::class)
            ->assertSee('TEST-BOX')
            ->assertSee('TEST-IND')
            ->assertSee('Pendiente')
            ->call('confirmChange', $request->id);

        $this->assertDatabaseHas('change_requests', [
            'id' => $request->id,
            'status' => 'completado'
        ]);
    }

    public function test_operator_can_toggle_catalog_visibility()
    {
        Livewire::test(OperatorRegistration::class)
            ->assertSet('isCatalogOpen', false)
            ->call('toggleCatalog')
            ->assertSet('isCatalogOpen', true)
            ->call('toggleCatalog')
            ->assertSet('isCatalogOpen', false);
    }

    public function test_operator_can_search_packaging_references()
    {
        \App\Models\PackagingReference::create(['code' => 'TARGET-BOX', 'type' => 'CAJA', 'dimensions' => '10x10x10']);
        \App\Models\PackagingReference::create(['code' => 'OTHER-BOX', 'type' => 'CAJA', 'dimensions' => '20x20x20']);

        Livewire::test(OperatorRegistration::class)
            ->set('searchReference', 'TARGET')
            ->assertViewHas('packagingReferences', function ($references) {
                return $references->count() === 1 && $references->first()->code === 'TARGET-BOX';
            });
    }
}
