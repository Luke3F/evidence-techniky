<?php

namespace Tests\Feature;

use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    private function valid(array $o = []): array
    {
        return array_merge([
            'name' => 'Vrtačka', 'inventory_number' => 'INV-1',
            'status' => 'active', 'purchased_at' => '2024-01-15',
        ], $o);
    }

    public function test_can_create_equipment(): void
    {
        $this->post('/equipment', $this->valid())->assertRedirect();
        $this->assertDatabaseHas('equipment', ['inventory_number' => 'INV-1']);
    }

    public function test_inventory_number_must_be_unique(): void
    {
        Equipment::create($this->valid());
        $this->post('/equipment', $this->valid())->assertSessionHasErrors('inventory_number');
    }

    public function test_status_must_be_allowed(): void
    {
        $this->post('/equipment', $this->valid(['status' => 'broken']))->assertSessionHasErrors('status');
    }

    public function test_date_format_is_validated(): void
    {
        $this->post('/equipment', $this->valid(['purchased_at' => '32.13.2024']))->assertSessionHasErrors('purchased_at');
    }

    public function test_service_record_cost_cannot_be_negative(): void
    {
        $e = Equipment::create($this->valid());
        $this->post("/equipment/{$e->id}/service-records", [
            'description' => 'Test', 'serviced_at' => '2024-02-01', 'cost' => '-5',
        ])->assertSessionHasErrors('cost');
    }

    public function test_service_record_added_and_cost_summed(): void
    {
        $e = Equipment::create($this->valid());
        foreach (['100,50', '200'] as $c) {
            $this->post("/equipment/{$e->id}/service-records", [
                'description' => 'Servis', 'serviced_at' => '2024-02-01', 'cost' => $c,
            ])->assertSessionHasNoErrors();
        }
        $this->assertEquals(300.50, (float) $e->serviceRecords()->sum('cost'));
    }

    public function test_status_filter(): void
    {
        Equipment::create($this->valid());
        Equipment::create($this->valid(['inventory_number' => 'INV-2', 'name' => 'Pila', 'status' => 'retired']));
        $this->get('/equipment?status=retired')->assertSee('Pila')->assertDontSee('Vrtačka');
    }
}
