<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

        body { 
            font-family: 'Roboto-Regular';
        }
        table {
            width:100%;
        }
        table.sub-table {
            text-align:center;
            border-collapse: collapse;
            border: 1px solid #2d2925;
        }
        table.sub-table > th {
            border: 1px solid #2d2925;
        }
        .sub-table td {
            border: 1px solid #2d2925;
        }
        .sub-table td:first-child{
            width:33%;
        }
        .sub-table td{
            width:33%;
        }
        .sub-table h1.details-title {
            text-align:center !important;
            padding: 5px;
        }

        .address {
            text-align:right;
            font-size:14px;
            font-weight:500;
            color:#0e0d0c;
            text-transform: uppercase;
        }
     
        ul {
            list-style:none;
            font-weight:600;
            text-transform:uppercase;
            color: #2d2925;
            font-size: 14px;
        }
        .main-title{
            font-weight: 600;
            font-size: 18px;
            text-transform: uppercase;
            color: #2d2925;
            text-align: left;
            padding: 0px;
            margin: 0px;
        }
        .details-title{
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            color: #2d2925;
            text-align: left;
            padding: 0px;
            margin: 0px;

        }
        .cut-width {
            font-size: 16px;
            word-wrap:break-word;
        }
        h1 > .details {
            font-weight:400;
            max-width:800px;
        }
        .main-title-secondary{
            font-weight: 200;
            font-size: 16px;
            text-transform: uppercase;
            color: #211c1c;
        }
        .centerText{
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- <span class="title">Date:</span> {{ now()->toDateString() }} <br><br> -->
    <table>
        <tr>
            <th>
                <h1 class="main-title">Alchem <span class="main-title-secondary"> Flavours | </span> </h1>
            </th>
            <th class="address">
                7-390 Steelcase Road E
            </th>
        </tr>
        <tr>
            <th class="address" colspan="2">
                Markham, Ontario
            </th>
        </tr>
        <tr>
            <th class="address" colspan="2">
            Canada, L3R 1G2
            </th>
        </tr>
    </table>
    <table>
        <tr>
            <td>
                <h1 class="main-title">Specification sheet</h1>
                <br>
            </td>
        </tr>
        @if (!empty($recipe_specification->project_sample->name))
        <tr>
            <td>
                <h1 class="details-title">Product Name: <span class="details cut-width"> {{ $recipe_specification->project_sample->name }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->project_sample->external_code))
        <tr>
            <td>
                <h1 class="details-title">Product Code: <span class="details cut-width"> {{ $recipe_specification->project_sample->external_code }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->appearance))
        <tr>
            <td>
                <h1 class="details-title">Appearance: <span class="details cut-width"> {{ $recipe_specification->appearance }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->aroma))
        <tr>
            <td>
                <h1 class="details-title">Aroma: <span class="details cut-width"> {{ $recipe_specification->aroma }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->flavor))
        <tr>
            <td>
                <h1 class="details-title">Flavor: <span class="details cut-width"> {{ $recipe_specification->flavor }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->specific_gravity))
        <tr>
            <td>
                <h1 class="details-title">Specific Gravity: <span class="details cut-width"> {{ $recipe_specification->specific_gravity }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->viscosity))
        <tr>
            <td>
                <h1 class="details-title">Viscosity: <span class="details cut-width"> {{ $recipe_specification->viscosity }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->flash_point))
        <tr>
            <td>
                <h1 class="details-title">Flash Point: <span class="details cut-width"> {{ $recipe_specification->flash_point }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->ingredient_list))
        <tr>
            <td>
                <h1 class="details-title">Ingredient Listing: <span class="details cut-width"> {{ $recipe_specification->ingredient_list }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->packing))
        <tr>
            <td>
                <h1 class="details-title">Packaging: <span class="details cut-width"> {{ $recipe_specification->packing->first()->name }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->storage_conditions))
        <tr>
            <td>
                <h1 class="details-title">Storage Conditions: <span class="details cut-width"> {{ $recipe_specification->storage_conditions }} </span></h1>
            </td>
        </tr>
        @endif
        @if (!empty($recipe_specification->shelf_life))
        <tr>
            <td>
                <br>
                <h1 class="details-title">Shelf Life: <span class="details cut-width"> {{ $recipe_specification->shelf_life }} </span></h1>
            </td>
        </tr>
        @endif
        
    </table>
    <br>
   

    <table class="sub-table">
        <tr >
            <td>
                <h1 class="details-title">Food Allergens</h1>
            </td>
            <td>
                <h1 class="details-title">Yes</h1>
            </td>
            <td>
                <h1 class="details-title">No</h1>
            </td>
        </tr>
        @if (!empty($key_value))
        @foreach ($key_value as $key => $attribute)
        <tr>
            <td>
            <h1 class="details-title"style="font-weight:400;font-size:14px;">{{ $key_titles[$key] }}</h1>

               
            </td>
            @if ($attribute)

            <td>
                <span style="font-weight:600;">X</span>
            </td>
            <td>
            </td>
            @else
            <td>
            </td>
            <td>
            <span style="font-weight:600;">X</span>

            </td>
            @endif

        </tr>
        @endforeach
    @endif
    </table>
    <br>
    <br>
    <br>
    <table style="width:100%">
    <tr>
        <td style="width:50%;">
            <h1 class="details-title" style="text-align:center;">Approved By: <span class="details"> {{ $recipe_specification->approver->name }} </span></h1>
        </td>
        <td style="width:50%;">
        <h1 class="details-title" style="text-align:center;">Date of Issue: <span class="details"> {{ now()->toDateString() }}</span></h1>
        </td>
    </tr>
    </table>
</body>
</html>
