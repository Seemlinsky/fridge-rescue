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

        @empty

            <p>Je koelkast is nog leeg.</p>

        @endforelse

    </div>

</div>

</body>
</html>
