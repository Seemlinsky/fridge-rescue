<?php

namespace Tests\Unit;

use App\Recommendation\IngredientCoverageStrategy;
use App\Recommendation\KnnTfidfStrategy;
use App\Recommendation\RecipeRecommendationService;
use App\Services\IngredientNormalizer;
use PHPUnit\Framework\TestCase;

class RecipeRecommendationTest extends TestCase
{
    private function recipes(): array
    {
        return require __DIR__.'/../../config/recipes.php';
    }

    private function knn(): KnnTfidfStrategy
    {
        $python = getenv('PYTHON_PATH') ?: (PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3');

        return new KnnTfidfStrategy($python, __DIR__.'/../../ml/recommend.py');
    }

    public function test_knn_strategy_zet_kip_met_rijst_bovenaan(): void
    {
        $service = new RecipeRecommendationService($this->knn(), new IngredientNormalizer, $this->recipes());

        $results = $service->recommend(['Kipfilet', 'rijst', 'paprika', 'ui']);

        $this->assertSame('Kip met rijst en paprika', $results[0]['name']);
        $this->assertSame(['knoflook'], $results[0]['missing']);
    }

    public function test_knn_strategy_geeft_niets_bij_lege_koelkast(): void
    {
        $service = new RecipeRecommendationService($this->knn(), new IngredientNormalizer, $this->recipes());

        $this->assertSame([], $service->recommend([]));
    }

    public function test_strategies_zijn_verwisselbaar(): void
    {
        $service = new RecipeRecommendationService($this->knn(), new IngredientNormalizer, $this->recipes());
        $service->useStrategy(new IngredientCoverageStrategy);

        $results = $service->recommend(['kip', 'rijst', 'paprika', 'ui']);

        $this->assertInstanceOf(IngredientCoverageStrategy::class, $service->strategy());
        $this->assertSame('Kip met rijst en paprika', $results[0]['name']);
    }
}
