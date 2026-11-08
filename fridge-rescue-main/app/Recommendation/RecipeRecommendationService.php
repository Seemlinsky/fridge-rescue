<?php

namespace App\Recommendation;

use App\Services\IngredientNormalizer;

/**
 * Haalt de recepten op, normaliseert de koelkast en laat de gekozen
 * strategy de ranking maken. De service kent zelf geen ML-details.
 */
class RecipeRecommendationService
{
    /**
     * @param  array<int, array{name: string, ingredients: array<int, string>, steps?: array<int, string>}>  $recipes
     */
    public function __construct(
        private RecipeRecommendationStrategy $strategy,
        private readonly IngredientNormalizer $normalizer,
        private readonly array $recipes,
    ) {}

    public function useStrategy(RecipeRecommendationStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function strategy(): RecipeRecommendationStrategy
    {
        return $this->strategy;
    }

    /**
     * @param  array<int, string>  $fridgeItems  Ruwe namen zoals de gebruiker ze invoerde
     */
    public function recommend(array $fridgeItems, int $limit = 5): array
    {
        $fridge = $this->normalizer->normalizeMany($fridgeItems);

        return $this->strategy->recommend($fridge, $this->recipes, $limit);
    }
}
