<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table, th, td{
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }
        th{
            color: white;
            font-weight: bold;
            background-color: black;
        }
        tfoot{
            color: white;
            font-weight: bold;
            background-color: orangered;
        }
        .title{
            font-weight: bold;
        }
        .centerText{
            text-align: center;
        }
    </style>
</head>
<body>
    <p>
        <span class="title">Date:</span> {{ now()->toDateString() }} <br><br>

        @if (!empty($recipe->name))
            <span class="title">Name:</span> {{ $recipe->name }} v{{ $recipe->version }}
        @endif

        @if (!empty($recipe->code))
            <span class="title">SKU:</span> {{ $recipe->code }}
        @endif

        @if (!empty($recipe->code))
            <span class="title">Version:</span> {{ $recipe->code }}
        @endif

        @if (!empty($recipe->type))
            <span class="title">Type:</span> {{ $recipe->type->name }}
        @endif

        @if (count($recipe->attributes) > 0)
            <span class="title">Regulory Status:</span>
            {{ implode(', ', $recipe->attributes->pluck('name')->toArray()) }}
        @endif

    </p>
    <br>
    @if (!empty($recipe->carrier))
        <span class="title">Carrier:</span> {{ $recipe->carrier->name }}
    @endif
    <table style="width:100%">
        <tr>
            <th>Ingredient Name</th>
            <th>%</th>
            <th>Cost </th>
        </tr>
        @foreach ($recipe->ingredients as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td class="centerText">{{ number_format($item['percent'] ?? $item['quantity'], 2) }}</td>
                <td class="centerText">{{ number_format($item['cost'], 2) }}</td>
            </tr>
        @endforeach
        <tfoot>
            <tr>
                <td>Total</td>
                <td class="centerText">{{ number_format($recipe->total ?? 0, 2) }}</td>
                <td class="centerText">{{ number_format($recipe->cost ?? 0, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
