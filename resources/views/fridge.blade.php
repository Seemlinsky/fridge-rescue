<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fridge Rescue</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f3;
            margin: 0;
            color: #222;
        }

        .container {
            max-width: 850px;
            margin: 50px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        input {
            padding: 12px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            padding: 12px 20px;
            background: #2d8a45;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete {
            background: #c0392b;
            padding: 8px 12px;
        }

        .recipe {
            padding: 18px 0;
            border-bottom: 1px solid #eee;
        }

        .score {
            float: right;
            background: #2d8a45;
            color: white;
            border-radius: 20px;
            padding: 4px 12px;
            font-weight: bold;
        }

        .tag {
            display: inline-block;
            border-radius: 6px;
            padding: 3px 8px;
            margin: 2px;
            font-size: 14px;
        }

        .have { background: #dff3e4; }
        .need { background: #fde8e6; }
        .muted { color: #777; font-size: 14px; }
        .error { color: #c0392b; }
    </style>
</head>

<body>

<div class="container">

    <h1>🥕 Fridge Rescue</h1>

    <p>
        Gebruik wat je nog thuis hebt en voorkom voedselverspilling.
    </p>

    <div class="card">

        <h2>Product toevoegen</h2>

        <form method="POST" action="{{ route('fridge.store') }}">

            @csrf

            <input
                type="text"
                name="name"
                placeholder="Bijv. Kip"
                required
            >

            <input
                type="text"
                name="quantity"
                placeholder="Bijv. 400 gram"
            >

            <input
                type="date"
                name="expires_at"
            >

            <button type="submit">
                Toevoegen
            </button>

        </form>

    </div>

    <div class="card">

        <h2>🧊 Mijn koelkast</h2>

        @forelse($items as $item)

            <div class="item">

                <div>
                    <strong>{{ $item->name }}</strong>

                    @if($item->quantity)
                        <br>
                        {{ $item->quantity }}
                    @endif

                    @if($item->expires_at)
                        <br>
                        Houdbaar tot: {{ $item->expires_at }}
                    @endif
                </div>

                <form method="POST" action="{{ route('fridge.destroy', $item) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete">Verwijderen</button>
                </form>

            </div>

        @empty

            <p>Je koelkast is nog leeg.</p>

        @endforelse

    </div>

    <div class="card">

        <h2>🍳 Wat kun je hiermee koken?</h2>

        <p class="muted">
            Methode: {{ $strategyLabel }} ·
            vergelijk: <a href="{{ route('fridge.index', ['strategy' => 'knn']) }}">ML (TF-IDF + KNN)</a> |
            <a href="{{ route('fridge.index', ['strategy' => 'coverage']) }}">baseline</a>
        </p>

        @if($error)
            <p class="error">{{ $error }}</p>
        @endif

        @forelse($recommendations as $recipe)

            <div class="recipe">

                <span class="score">{{ round($recipe['score'] * 100) }}%</span>
                <strong>{{ $recipe['name'] }}</strong>

                <p class="muted">
                    Similarity {{ round($recipe['similarity'] * 100) }}% ·
                    coverage {{ round($recipe['coverage'] * 100) }}%
                </p>

                <div>
                    Heb je al:
                    @foreach($recipe['matched'] as $ingredient)
                        <span class="tag have">{{ $ingredient }}</span>
                    @endforeach
                </div>

                @if(count($recipe['missing']) > 0)
                    <div>
                        Nog nodig:
                        @foreach($recipe['missing'] as $ingredient)
                            <span class="tag need">{{ $ingredient }}</span>
                        @endforeach
                    </div>
                @endif

                <details>
                    <summary>Bereiding</summary>
                    <ol>
                        @foreach($recipe['steps'] as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </details>

            </div>

        @empty

            @unless($error)
                <p>Voeg ingrediënten toe om recepten te zien.</p>
            @endunless

        @endforelse

    </div>

</div>

</body>
</html>
