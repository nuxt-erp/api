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
        .center {
            margin-left: auto;
            margin-right: auto;
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
            <span class="title">Type:</span> {{ $recipe->type->value }}
        @endif

        @if (count($recipe->attributes) > 0)
            <span class="title">Regulory Status:</span>
            {{ implode(', ', $recipe->attributes->pluck('value')->toArray()) }}
        @endif

    </p>
    <br>
    @if (!empty($recipe->carrier))
        <span class="title">Carrier:</span> {{ $recipe->carrier->name }}
        <span class="title">Sample Size:</span> {{ $sample_size.$sample_uom }}
    @endif
    <table style="width:100%">
        <tr>
            <th>Ingredient Name</th>
            <th>QTY</th>
            <th>%</th>
            <th>Cost </th>
        </tr>
        @foreach ($recipe->ingredients as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td class="centerText">{{ number_format($item['quantity'], 4) }}</td>
                <td class="centerText">{{ number_format($item['percent'], 4) }}</td>
                <td class="centerText">{{ number_format($item->cost ?? 0, 4) }}</td>
            </tr>
        @endforeach
    </table>
    <br><br>
    <table class="center">
        <tr>
            <td>TOTAL RAW MATERIAL</td>
            <td class="centerText">{{ number_format($total_material, 4) . $sample_uom }}</td>
            <td class="centerText">{{ number_format($total_material_perc, 4) }}%</td>
            <td class="centerText">${{ number_format($total_material_cost ?? 0, 4) }}</td>
        </tr>
        <tr>
            <td>CARRIER</td>
            <td class="centerText">{{ number_format($total_carrier ?? 0, 4) . $sample_uom }}</td>
            <td class="centerText">{{ number_format($total_carrier_perc, 4) }}%</td>
            <td class="centerText">${{ number_format($total_carrier_cost ?? 0, 4) }}</td>
        </tr>
        <tr>
            <td>TOTAL</td>
            <td class="centerText">{{ number_format($total_material+$total_carrier ?? 0, 4) . $sample_uom}}</td>
            <td class="centerText">100%</td>
            <td class="centerText">${{ number_format($total_material_cost+$total_carrier_cost ?? 0, 4) }}</td>
        </tr>
    </table>
</body>
</html>
