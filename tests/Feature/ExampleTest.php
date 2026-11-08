<?php

namespace Tests\Feature;

use App\Models\FridgeItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_de_koelkastpagina_laadt(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('Fridge Rescue');
    }

    public function test_recepten_worden_getoond_voor_de_koelkast(): void
    {
        foreach (['Kipfilet', 'rijst', 'paprika', 'ui'] as $name) {
            FridgeItem::create(['name' => $name]);
        }

        $this->get('/')->assertStatus(200)->assertSee('Kip met rijst en paprika');
    }

    public function test_product_toevoegen_en_verwijderen(): void
    {
        $this->post('/fridge', ['name' => 'Kip'])->assertRedirect('/');
        $item = FridgeItem::first();
        $this->assertSame('Kip', $item->name);

        $this->delete("/fridge/{$item->id}")->assertRedirect('/');
        $this->assertSame(0, FridgeItem::count());
    }
}
