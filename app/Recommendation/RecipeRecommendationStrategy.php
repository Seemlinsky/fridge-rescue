<?php

namespace App\Recommendation;

/**
 * Strategy Pattern: ieder recommendation-algoritme implementeert deze interface,
 * zodat RecipeRecommendationService niet hoeft te weten hoe de score berekend wordt.
 */
interface RecipeRecommendationStrategy
{
    /**
     * @param  array<int, string>  $fridge  Genormaliseerde ingrediënten uit de koelkast
     * @param  array<int, array{name: string, ingredients: array<int, string>, steps?: array<int, string>}>  $recipes
     * @return array<int, array{name: string, similarity: float, coverage: float, score: float, matched: array<int, string>, missing: array<int, string>, steps: array<int, string>}>
     */
    public function recommend(array $fridge, array $recipes, int $limit = 5): array;

    /** Korte naam voor in de interface, bijvoorbeeld "TF-IDF + NearestNeighbors". */
    public function label(): string;
}
