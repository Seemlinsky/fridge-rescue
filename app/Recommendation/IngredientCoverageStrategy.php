<?php

namespace App\Recommendation;

/**
 * Baseline zonder Machine Learning: Jaccard-overlap + ingredient coverage.
 *
 * Wordt gebruikt als controlepunt: de ML-strategy moet minstens
 * even logische rankings geven als deze simpele regel.
 */
class IngredientCoverageStrategy implements RecipeRecommendationStrategy
{
    public function recommend(array $fridge, array $recipes, int $limit = 5): array
    {
        $fridgeSet = array_values(array_unique($fridge));

        if ($fridgeSet === []) {
            return [];
        }

        $results = [];

        foreach ($recipes as $recipe) {
            $ingredients = array_values(array_unique($recipe['ingredients'] ?? []));

            if ($ingredients === []) {
                continue;
            }

            $matched = array_values(array_intersect($ingredients, $fridgeSet));
            $missing = array_values(array_diff($ingredients, $fridgeSet));

            if ($matched === []) {
                continue;
            }

            $union = count(array_unique(array_merge($ingredients, $fridgeSet)));
            $jaccard = count($matched) / $union;
            $coverage = count($matched) / count($ingredients);

            $results[] = [
                'name' => $recipe['name'],
                'similarity' => round($jaccard, 4),
                'coverage' => round($coverage, 4),
                'score' => round(0.7 * $jaccard + 0.3 * $coverage, 4),
                'matched' => $matched,
                'missing' => $missing,
                'steps' => $recipe['steps'] ?? [],
            ];
        }

        usort($results, fn (array $a, array $b) => [$b['score'], $a['name']] <=> [$a['score'], $b['name']]);

        return array_slice($results, 0, max(1, $limit));
    }

    public function label(): string
    {
        return 'Baseline: ingredient overlap (Jaccard)';
    }
}
