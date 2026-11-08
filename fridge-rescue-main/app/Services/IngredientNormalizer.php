<?php

namespace App\Services;

/**
 * Zet invoer van de gebruiker om naar een vaste basisnaam,
 * zodat bijvoorbeeld "Kipfilet" en "kip" als hetzelfde ingrediënt tellen.
 *
 * Dit gebeurt vóór de ML-pipeline: TF-IDF ziet ieder ingrediënt als één token,
 * dus verschillende schrijfwijzen moeten eerst samenvallen.
 */
class IngredientNormalizer
{
    /**
     * Alias => basisnaam. Bewust een simpele handmatige lijst voor de PoC.
     */
    private const ALIASES = [
        // kip
        'kipfilet' => 'kip', 'kippenfilet' => 'kip', 'kippendij' => 'kip', 'kippendijen' => 'kip',
        'kipdij' => 'kip', 'kipdijfilet' => 'kip', 'chicken' => 'kip', 'kipstukjes' => 'kip',
        // tomaat
        'tomaten' => 'tomaat', 'cherrytomaat' => 'tomaat', 'cherrytomaten' => 'tomaat',
        'tomatenblokjes' => 'tomaat', 'tomatenpuree' => 'tomaat', 'tomato' => 'tomaat', 'tomatoes' => 'tomaat',
        // ui
        'uien' => 'ui', 'rode ui' => 'ui', 'rode uien' => 'ui', 'sjalot' => 'ui', 'sjalotten' => 'ui', 'onion' => 'ui',
        // pasta
        'spaghetti' => 'pasta', 'penne' => 'pasta', 'macaroni' => 'pasta', 'fusilli' => 'pasta', 'tagliatelle' => 'pasta',
        // aardappel
        'aardappels' => 'aardappel', 'aardappelen' => 'aardappel', 'potato' => 'aardappel', 'potatoes' => 'aardappel', 'krieltjes' => 'aardappel',
        // overige
        'eieren' => 'ei', 'eggs' => 'ei', 'egg' => 'ei',
        'paprikas' => 'paprika', 'rode paprika' => 'paprika', 'groene paprika' => 'paprika', 'gele paprika' => 'paprika',
        'knoflookteen' => 'knoflook', 'knoflookteentjes' => 'knoflook', 'garlic' => 'knoflook',
        'basmatirijst' => 'rijst', 'zilvervliesrijst' => 'rijst', 'rice' => 'rijst',
        'geraspte kaas' => 'kaas', 'jonge kaas' => 'kaas', 'oude kaas' => 'kaas', 'parmezaan' => 'kaas', 'mozzarella' => 'kaas', 'cheese' => 'kaas',
        'rundergehakt' => 'gehakt', 'half om half gehakt' => 'gehakt', 'kipgehakt' => 'gehakt',
        'kidneybonen' => 'bonen', 'bruine bonen' => 'bonen',
        'wraps' => 'wrap', 'tortilla' => 'wrap', 'tortillas' => 'wrap',
        'ijsbergsla' => 'sla', 'rucola' => 'sla',
        'kookroom' => 'room', 'slagroom' => 'room',
        'olijven' => 'olijf',
        'volle melk' => 'melk', 'halfvolle melk' => 'melk',
        'roomboter' => 'boter',
        'broccoliroosjes' => 'broccoli',
        'ketjap' => 'sojasaus', 'soja saus' => 'sojasaus',
        'bouillonblokje' => 'bouillon', 'bouillonblokjes' => 'bouillon',
        'tarwebloem' => 'bloem',
        'witbrood' => 'brood', 'bruinbrood' => 'brood',
        'komkommers' => 'komkommer',
    ];

    public function normalize(string $ingredient): string
    {
        $value = mb_strtolower(trim($ingredient));

        // Vreemde tekens en cijfers weg, streepjes worden spaties ("half-om-half" -> "half om half").
        $value = str_replace(['-', '_'], ' ', $value);
        $value = preg_replace('/[^\p{L} ]+/u', '', $value) ?? '';
        $value = trim(preg_replace('/\s+/', ' ', $value) ?? '');

        return self::ALIASES[$value] ?? $value;
    }

    /**
     * Normaliseert een lijst en verwijdert lege waarden en dubbelen.
     *
     * @param  array<int, string>  $ingredients
     * @return array<int, string>
     */
    public function normalizeMany(array $ingredients): array
    {
        $normalized = array_map(fn (string $i) => $this->normalize($i), $ingredients);

        return array_values(array_unique(array_filter($normalized, fn (string $i) => $i !== '')));
    }
}
