"""
FridgeRescue - recommendation met TF-IDF + NearestNeighbors (scikit-learn).

Laravel stuurt via stdin een JSON-object:
    {
        "fridge":  ["kip", "rijst", "paprika", "ui"],
        "recipes": [{"name": "...", "ingredients": ["kip", ...], "steps": [...]}, ...],
        "limit":   5
    }

Het script geeft via stdout een JSON-lijst terug met de beste recepten:
    [{"name": ..., "similarity": 0.81, "coverage": 0.75, "score": 0.79,
      "matched": [...], "missing": [...], "steps": [...]}, ...]

Stappen:
1. TfidfVectorizer wordt gefit op de ingrediëntenlijsten van de recepten.
   Ieder (al genormaliseerd) ingrediënt is één token.
2. De koelkast wordt met dezelfde vocabulary en IDF-gewichten getransformeerd.
3. NearestNeighbors (metric="cosine", algorithm="brute") zoekt de receptvectoren
   met de kleinste cosine distance. similarity = 1 - cosine distance.
4. De eindscore combineert de ML-similarity met ingredient coverage
   (welk deel van het recept al in de koelkast ligt).
"""

import json
import sys

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neighbors import NearestNeighbors

# Weging van de eindscore. Similarity is de ML-score, coverage is een kleine bonus
# voor recepten waarvoor je weinig extra hoeft te kopen.
SIMILARITY_WEIGHT = 0.7
COVERAGE_WEIGHT = 0.3


def identity_analyzer(ingredients):
    """De ingrediënten zijn al genormaliseerd door Laravel; ieder ingrediënt is één token."""
    return ingredients


def recommend(fridge, recipes, limit=5):
    fridge = sorted(set(fridge))
    recipes = [r for r in recipes if r.get("ingredients")]

    if not fridge or not recipes:
        return []

    # 1. Features: TF-IDF op de ingrediënten van de recepten.
    vectorizer = TfidfVectorizer(analyzer=identity_analyzer)
    recipe_matrix = vectorizer.fit_transform([r["ingredients"] for r in recipes])

    # 2. Koelkast in dezelfde feature-ruimte zetten.
    fridge_vector = vectorizer.transform([fridge])

    # Geen enkel koelkast-ingrediënt komt in een recept voor: niets aan te bevelen.
    if fridge_vector.nnz == 0:
        return []

    # 3. Neighbor search met cosine distance.
    n_neighbors = min(len(recipes), max(1, int(limit)))
    model = NearestNeighbors(n_neighbors=n_neighbors, metric="cosine", algorithm="brute")
    model.fit(recipe_matrix)
    distances, indices = model.kneighbors(fridge_vector)

    # 4. Resultaten opbouwen met uitleg (heb-je-al / nog-nodig).
    fridge_set = set(fridge)
    results = []
    for distance, index in zip(distances[0], indices[0]):
        recipe = recipes[index]
        ingredients = list(dict.fromkeys(recipe["ingredients"]))
        matched = [i for i in ingredients if i in fridge_set]
        missing = [i for i in ingredients if i not in fridge_set]

        if not matched:
            continue

        similarity = max(0.0, 1.0 - float(distance))
        coverage = len(matched) / len(ingredients)
        score = SIMILARITY_WEIGHT * similarity + COVERAGE_WEIGHT * coverage

        results.append({
            "name": recipe["name"],
            "similarity": round(similarity, 4),
            "coverage": round(coverage, 4),
            "score": round(score, 4),
            "matched": matched,
            "missing": missing,
            "steps": recipe.get("steps", []),
        })

    # Sorteren op eindscore; bij gelijke score op naam zodat de output reproduceerbaar is.
    results.sort(key=lambda r: (-r["score"], r["name"]))
    return results


def main():
    try:
        payload = json.load(sys.stdin)
        results = recommend(
            payload.get("fridge", []),
            payload.get("recipes", []),
            payload.get("limit", 5),
        )
    except Exception as error:  # Laravel leest de foutmelding uit stderr
        print(f"recommend.py fout: {error}", file=sys.stderr)
        sys.exit(1)

    json.dump(results, sys.stdout, ensure_ascii=False)


if __name__ == "__main__":
    main()
