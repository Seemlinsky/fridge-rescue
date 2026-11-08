"""
Tests voor recommend.py, gebaseerd op de functionele testgevallen (T1-T6)
uit de PoC-analyse. Draaien met:  python -m unittest discover ml
"""

import json
import os
import subprocess
import sys
import unittest

sys.path.insert(0, os.path.dirname(__file__))
from recommend import recommend  # noqa: E402

RECIPES = [
    {"name": "Kip met rijst en paprika", "ingredients": ["kip", "rijst", "paprika", "ui", "knoflook"], "steps": ["Kook de rijst."]},
    {"name": "Nasi goreng", "ingredients": ["rijst", "ei", "ui", "knoflook", "kip", "sojasaus"], "steps": []},
    {"name": "Pasta pomodoro", "ingredients": ["pasta", "tomaat", "knoflook", "ui", "kaas"], "steps": []},
    {"name": "Omelet met kaas en paprika", "ingredients": ["ei", "kaas", "paprika", "ui"], "steps": []},
    {"name": "Pannenkoeken", "ingredients": ["bloem", "ei", "melk", "boter"], "steps": []},
]

FRIDGE = ["kip", "rijst", "paprika", "ui"]


class RecommendTest(unittest.TestCase):

    def test_t1_kip_rijst_paprika_ui_geeft_kip_met_rijst_bovenaan(self):
        results = recommend(FRIDGE, RECIPES)
        self.assertEqual(results[0]["name"], "Kip met rijst en paprika")
        self.assertIn("knoflook", results[0]["missing"])

    def test_t2_zelfde_input_geeft_zelfde_ranking(self):
        self.assertEqual(recommend(FRIDGE, RECIPES), recommend(FRIDGE, RECIPES))

    def test_t3_knoflook_toevoegen_verhoogt_coverage(self):
        before = recommend(FRIDGE, RECIPES)[0]
        after = recommend(FRIDGE + ["knoflook"], RECIPES)[0]
        self.assertEqual(after["name"], "Kip met rijst en paprika")
        self.assertGreater(after["coverage"], before["coverage"])
        self.assertNotIn("knoflook", after["missing"])
        self.assertEqual(after["missing"], [])

    def test_t4_rijst_verwijderen_verandert_score(self):
        with_rice = {r["name"]: r["similarity"] for r in recommend(FRIDGE, RECIPES)}
        without_rice = {r["name"]: r["similarity"] for r in recommend(["kip", "paprika", "ui"], RECIPES)}
        self.assertNotEqual(with_rice["Kip met rijst en paprika"], without_rice["Kip met rijst en paprika"])

    def test_t5_lege_koelkast_geeft_geen_recommendations(self):
        self.assertEqual(recommend([], RECIPES), [])

    def test_onbekende_ingredienten_geven_geen_recommendations(self):
        self.assertEqual(recommend(["chocolade"], RECIPES), [])

    def test_similarity_is_tussen_0_en_1_en_limit_werkt(self):
        results = recommend(FRIDGE, RECIPES, limit=2)
        self.assertLessEqual(len(results), 2)
        for r in results:
            self.assertGreaterEqual(r["similarity"], 0)
            self.assertLessEqual(r["similarity"], 1)

    def test_script_werkt_via_stdin_en_stdout(self):
        """Zo roept Laravel (KnnTfidfStrategy) het script aan."""
        script = os.path.join(os.path.dirname(__file__), "recommend.py")
        payload = json.dumps({"fridge": FRIDGE, "recipes": RECIPES, "limit": 3})
        output = subprocess.run([sys.executable, script], input=payload, capture_output=True, text=True, check=True)
        results = json.loads(output.stdout)
        self.assertEqual(results[0]["name"], "Kip met rijst en paprika")


if __name__ == "__main__":
    unittest.main()
