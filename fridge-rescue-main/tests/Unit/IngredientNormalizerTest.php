<?php

namespace Tests\Unit;

use App\Services\IngredientNormalizer;
use PHPUnit\Framework\TestCase;

class IngredientNormalizerTest extends TestCase
{
    public function test_t6_kipfilet_wordt_kip(): void
    {
        $normalizer = new IngredientNormalizer;

        $this->assertSame('kip', $normalizer->normalize('Kipfilet'));
        $this->assertSame('kip', $normalizer->normalize('  KIPFILET!! '));
    }

    public function test_aliassen_uit_de_analyse(): void
    {
        $normalizer = new IngredientNormalizer;

        $this->assertSame('tomaat', $normalizer->normalize('cherrytomaten'));
        $this->assertSame('ui', $normalizer->normalize('Rode ui'));
        $this->assertSame('pasta', $normalizer->normalize('spaghetti'));
        $this->assertSame('aardappel', $normalizer->normalize('potatoes'));
    }

    public function test_normalize_many_verwijdert_dubbelen_en_lege_waarden(): void
    {
        $normalizer = new IngredientNormalizer;

        $this->assertSame(['kip', 'rijst'], $normalizer->normalizeMany(['kip', 'Kipfilet', '', 'rijst', '123']));
    }
}
