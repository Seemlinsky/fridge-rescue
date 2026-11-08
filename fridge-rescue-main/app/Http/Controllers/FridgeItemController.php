<?php

namespace App\Http\Controllers;

use App\Models\FridgeItem;
use App\Recommendation\IngredientCoverageStrategy;
use App\Recommendation\KnnTfidfStrategy;
use App\Recommendation\RecipeRecommendationService;
use Illuminate\Http\Request;
use Throwable;

class FridgeItemController extends Controller
{
    public function index(Request $request, RecipeRecommendationService $recommender)
    {
        $items = FridgeItem::orderBy('expires_at')->get();

        // Met ?strategy=coverage of ?strategy=knn kun je de algoritmes vergelijken (Strategy Pattern).
        $strategy = $request->query('strategy');
        if ($strategy === 'coverage') {
            $recommender->useStrategy(app(IngredientCoverageStrategy::class));
        } elseif ($strategy === 'knn') {
            $recommender->useStrategy(app(KnnTfidfStrategy::class));
        }

        $recommendations = [];
        $error = null;

        try {
            $recommendations = $recommender->recommend($items->pluck('name')->all());
        } catch (Throwable $e) {
            report($e);
            $error = 'De aanbevelingen konden niet worden berekend. Controleer of Python en scikit-learn geïnstalleerd zijn.';
        }

        return view('fridge', [
            'items' => $items,
            'recommendations' => $recommendations,
            'strategyLabel' => $recommender->strategy()->label(),
            'error' => $error,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:100',
            'expires_at' => 'nullable|date',
        ]);

        FridgeItem::create($validated);

        return redirect('/');
    }

    public function destroy(FridgeItem $fridgeItem)
    {
        $fridgeItem->delete();

        return redirect('/');
    }
}
