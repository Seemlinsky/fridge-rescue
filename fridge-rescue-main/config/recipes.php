<?php

/*
|--------------------------------------------------------------------------
| Kleine lokale receptendataset voor de PoC
|--------------------------------------------------------------------------
|
| De ingrediënten staan hier al in hun genormaliseerde basisvorm
| (zie App\Services\IngredientNormalizer). Zout, peper en olie zijn
| weggelaten omdat bijna iedereen die heeft; ze zouden de ranking alleen
| maar ruis geven.
|
*/

return [
    [
        'name' => 'Kip met rijst en paprika',
        'ingredients' => ['kip', 'rijst', 'paprika', 'ui', 'knoflook'],
        'steps' => [
            'Kook de rijst volgens de verpakking.',
            'Snijd de kip in blokjes en bak deze goudbruin.',
            'Voeg ui, knoflook en paprika toe en bak 5 minuten mee.',
            'Serveer de kip met groenten over de rijst.',
        ],
    ],
    [
        'name' => 'Nasi goreng',
        'ingredients' => ['rijst', 'ei', 'ui', 'knoflook', 'kip', 'sojasaus'],
        'steps' => [
            'Kook de rijst en laat deze afkoelen.',
            'Bak ui, knoflook en kip.',
            'Voeg de rijst en sojasaus toe en roerbak alles.',
            'Bak een ei en leg dit op de nasi.',
        ],
    ],
    [
        'name' => 'Pasta pomodoro',
        'ingredients' => ['pasta', 'tomaat', 'knoflook', 'ui', 'kaas'],
        'steps' => [
            'Kook de pasta beetgaar.',
            'Fruit ui en knoflook en voeg de tomaten toe.',
            'Laat de saus 10 minuten zachtjes koken.',
            'Meng de pasta met de saus en strooi er kaas over.',
        ],
    ],
    [
        'name' => 'Pasta met gehakt en tomaat',
        'ingredients' => ['pasta', 'gehakt', 'tomaat', 'ui', 'knoflook'],
        'steps' => [
            'Kook de pasta.',
            'Bak het gehakt rul met ui en knoflook.',
            'Voeg tomaat toe en laat 10 minuten pruttelen.',
            'Meng met de pasta.',
        ],
    ],
    [
        'name' => 'Omelet met kaas en paprika',
        'ingredients' => ['ei', 'kaas', 'paprika', 'ui'],
        'steps' => [
            'Klop de eieren los.',
            'Bak ui en paprika kort in de pan.',
            'Giet de eieren erover en strooi de kaas erop.',
            'Bak tot de omelet gestold is.',
        ],
    ],
    [
        'name' => 'Gebakken aardappels met ei',
        'ingredients' => ['aardappel', 'ei', 'ui'],
        'steps' => [
            'Kook de aardappels 10 minuten voor en snijd ze in plakken.',
            'Bak de aardappels met ui goudbruin.',
            'Bak er een ei bij.',
        ],
    ],
    [
        'name' => 'Stamppot boerenkool',
        'ingredients' => ['aardappel', 'boerenkool', 'rookworst', 'melk', 'boter'],
        'steps' => [
            'Kook de aardappels met de boerenkool gaar.',
            'Verwarm de rookworst.',
            'Stamp de aardappels en boerenkool met melk en boter.',
            'Serveer met de rookworst.',
        ],
    ],
    [
        'name' => 'Tomatensoep',
        'ingredients' => ['tomaat', 'ui', 'knoflook', 'bouillon', 'room'],
        'steps' => [
            'Fruit ui en knoflook.',
            'Voeg tomaten en bouillon toe en kook 15 minuten.',
            'Pureer de soep en roer de room erdoor.',
        ],
    ],
    [
        'name' => 'Wraps met kip en sla',
        'ingredients' => ['wrap', 'kip', 'sla', 'tomaat', 'komkommer'],
        'steps' => [
            'Bak de kip gaar en snijd in reepjes.',
            'Snijd tomaat en komkommer.',
            'Vul de wraps met sla, groenten en kip.',
        ],
    ],
    [
        'name' => 'Roerbak met kip en broccoli',
        'ingredients' => ['kip', 'broccoli', 'sojasaus', 'knoflook', 'rijst'],
        'steps' => [
            'Kook de rijst.',
            'Roerbak de kip met knoflook.',
            'Voeg broccoli en sojasaus toe en bak 5 minuten.',
            'Serveer met de rijst.',
        ],
    ],
    [
        'name' => 'Tosti ham-kaas',
        'ingredients' => ['brood', 'ham', 'kaas', 'boter'],
        'steps' => [
            'Besmeer het brood met boter.',
            'Beleg met ham en kaas.',
            'Bak of grill de tosti tot de kaas gesmolten is.',
        ],
    ],
    [
        'name' => 'Griekse salade',
        'ingredients' => ['komkommer', 'tomaat', 'feta', 'ui', 'olijf'],
        'steps' => [
            'Snijd komkommer, tomaat en ui.',
            'Voeg feta en olijven toe.',
            'Besprenkel met olijfolie.',
        ],
    ],
    [
        'name' => 'Pannenkoeken',
        'ingredients' => ['bloem', 'ei', 'melk', 'boter'],
        'steps' => [
            'Meng bloem, eieren en melk tot een glad beslag.',
            'Bak de pannenkoeken in boter.',
        ],
    ],
    [
        'name' => 'Chili con carne',
        'ingredients' => ['gehakt', 'bonen', 'tomaat', 'ui', 'paprika', 'rijst'],
        'steps' => [
            'Kook de rijst.',
            'Bak het gehakt met ui en paprika.',
            'Voeg tomaat en bonen toe en laat 15 minuten stoven.',
            'Serveer met de rijst.',
        ],
    ],
];
