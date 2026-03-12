<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\ManageIndirectos;
use App\Models\PackagingReference;
use App\Models\ChangeRequest;

class ManageIndirectosTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_page_and_see_indirectos()
    {
        PackagingReference::create(['code' => 'TESTING-123', 'type' => 'CAJA', 'dimensions' => '10x10']);

        $response = $this->get('/catalogo');
        
        $response->assertStatus(200);
        $response->assertSee('TESTING-123');
        $response->assertSee('CAJA');
    }

    public function test_can_create_new_indirecto()
    {
        Livewire::test(ManageIndirectos::class)
            ->call('create')
            ->set('code', 'NEW-MLP123')
            ->set('type', 'TARIMA')
            ->set('dimensions', '120x80')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Indirecto creado exitosamente');

        $this->assertDatabaseHas('packaging_references', [
            'code' => 'NEW-MLP123',
            'type' => 'TARIMA',
        ]);
    }

    public function test_cannot_create_duplicate_code()
    {
        PackagingReference::create(['code' => 'DUP-1', 'type' => 'CAJA']);

        Livewire::test(ManageIndirectos::class)
            ->call('create')
            ->set('code', 'DUP-1')
            ->set('type', 'TARIMA')
            ->call('save')
            ->assertHasErrors(['code' => 'unique']);
    }

    public function test_can_edit_existing_indirecto()
    {
        $ref = PackagingReference::create(['code' => 'EDIT-ME', 'type' => 'CAJA', 'dimensions' => '10x10']);

        Livewire::test(ManageIndirectos::class)
            ->call('edit', $ref->id)
            ->assertSet('code', 'EDIT-ME')
            ->assertSet('type', 'CAJA')
            ->set('code', 'EDITED-OK')
            ->set('dimensions', '20x20')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Indirecto actualizado exitosamente');

        $this->assertDatabaseHas('packaging_references', [
            'id' => $ref->id,
            'code' => 'EDITED-OK',
            'dimensions' => '20x20',
        ]);
    }

    public function test_can_delete_unused_indirecto()
    {
        $ref = PackagingReference::create(['code' => 'DEL-ME', 'type' => 'GAYLOR']);

        Livewire::test(ManageIndirectos::class)
            ->call('delete', $ref->id)
            ->assertSet('isFormOpen', false)
            ->assertSee('Indirecto eliminado');

        $this->assertDatabaseMissing('packaging_references', [
            'id' => $ref->id,
        ]);
    }

    public function test_prevents_deletion_of_in_use_indirecto()
    {
        $ref = PackagingReference::create(['code' => 'IN-USE', 'type' => 'CAJA']);
        
        $cr = ChangeRequest::create(['status' => 'completado']);
        $cr->items()->create(['box_name' => 'B1', 'indirecto_code' => 'IN-USE']);

        Livewire::test(ManageIndirectos::class)
            ->call('delete', $ref->id)
            ->assertSee('No se puede eliminar')
            ->assertSee('IN-USE');

        $this->assertDatabaseHas('packaging_references', [
            'id' => $ref->id,
        ]);
    }
}
