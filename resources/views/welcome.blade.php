<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fridge Rescue</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f5f2;
            margin: 0;
            color: #222;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 50px auto;
        }

        h1 {
            font-size: 42px;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        input {
            padding: 12px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background: #248a3d;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #1d7032;
        }

        .item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>🥕 Fridge Rescue</h1>

    <p class="subtitle">
        Gebruik wat je nog thuis hebt en voorkom voedselverspilling.
    </p>

    <div class="card">

        <h2>Product toevoegen</h2>

        <form action="{{ route('fridge.store') }}" method="POST">

            @csrf

            <input
                type="text"
                name="name"
                placeholder="Product, bijvoorbeeld Kip"
                required
            >

            <input
                type="text"
                name="quantity"
                placeholder="Bijvoorbeeld 400 gram"
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

                <strong>{{ $item->name }}</strong>

                @if($item->quantity)
                    <br>
                    Hoeveelheid: {{ $item->quantity }}
                @endif

                @if($item->expires_at)
                    <br>
                    Houdbaar tot: {{ $item->expires_at }}
                @endif

            </div>

        @empty

            <p>Je koelkast is nog leeg.</p>

        @endforelse

    </div>

</div>

</body>
</html>
