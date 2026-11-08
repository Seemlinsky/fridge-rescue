# 🥕 Fridge Rescue

Proof of Concept voor Beroepsproduct 5 (ADA Machine Learning) – Stefan Stojković.

Fridge Rescue helpt je koken met wat je nog thuis hebt. Je zet producten in je koelkast
en de app rangschikt recepten op hoe goed ze passen. Per recept zie je een matchscore,
wat je al hebt, wat je nog nodig hebt en de bereidingsstappen.

## Hoe werkt de recommendation?

```
Koelkast (Laravel)
   │  IngredientNormalizer: "Kipfilet" -> "kip", "cherrytomaten" -> "tomaat"
   ▼
RecipeRecommendationService
   │  gebruikt een RecipeRecommendationStrategy (Strategy Pattern)
   ├── KnnTfidfStrategy          -> ml/recommend.py (scikit-learn)
   │      1. TfidfVectorizer fit op de ingrediënten van de recepten
   │      2. koelkast transformeren naar dezelfde feature-ruimte
   │      3. NearestNeighbors(metric="cosine", algorithm="brute")
   │      4. score = 0.7 · (1 − cosine distance) + 0.3 · ingredient coverage
   └── IngredientCoverageStrategy -> baseline zonder ML (Jaccard + coverage)
```

Laravel en Python praten via JSON over stdin/stdout. De recepten staan in `config/recipes.php`.

Je kunt de algoritmes vergelijken in de browser:
- `/?strategy=knn` – ML: TF-IDF + NearestNeighbors (standaard)
- `/?strategy=coverage` – baseline

## Belangrijkste bestanden

| Bestand | Wat doet het? |
|---|---|
| `app/Services/IngredientNormalizer.php` | Schrijfwijzen terugbrengen naar één basisnaam |
| `app/Recommendation/RecipeRecommendationStrategy.php` | Interface (Strategy Pattern) |
| `app/Recommendation/KnnTfidfStrategy.php` | ML-strategy, roept Python aan |
| `app/Recommendation/IngredientCoverageStrategy.php` | Baseline zonder ML |
| `app/Recommendation/RecipeRecommendationService.php` | Gebruikt de gekozen strategy |
| `ml/recommend.py` | TF-IDF + NearestNeighbors met scikit-learn |
| `config/recipes.php` | Kleine lokale receptendataset |

## Installeren

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

pip install -r ml/requirements.txt

php artisan serve
```

Als Python op je computer anders heet, zet dan in `.env`: `PYTHON_PATH=python` (of het volledige pad).
Met `RECOMMENDER_STRATEGY=coverage` gebruikt de app standaard de baseline.

## Testen

```bash
python -m unittest discover ml   # testgevallen T1–T6 uit de PoC-analyse
php artisan test                 # normalizer, strategies en de koelkastpagina
```
