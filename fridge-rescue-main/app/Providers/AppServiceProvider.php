<?php

namespace App\Providers;

use App\Recommendation\IngredientCoverageStrategy;
use App\Recommendation\KnnTfidfStrategy;
use App\Recommendation\RecipeRecommendationService;
use App\Recommendation\RecipeRecommendationStrategy;
use App\Services\IngredientNormalizer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(KnnTfidfStrategy::class, fn () => new KnnTfidfStrategy(
            config('services.recommender.python'),
            base_path('ml/recommend.py'),
        ));

        // Strategy Pattern: hier wordt gekozen welk algoritme de app gebruikt.
        $this->app->bind(RecipeRecommendationStrategy::class, fn ($app) => match (config('services.recommender.strategy')) {
            'coverage' => $app->make(IngredientCoverageStrategy::class),
            default => $app->make(KnnTfidfStrategy::class),
        });

        $this->app->bind(RecipeRecommendationService::class, fn ($app) => new RecipeRecommendationService(
            $app->make(RecipeRecommendationStrategy::class),
            $app->make(IngredientNormalizer::class),
            config('recipes', []),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
