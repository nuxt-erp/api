<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page { margin: 10px 5px 5px; }
        table{
            border: 2px solid black;
            border-collapse: collapse;
            margin-left: 2px;
        }
        tr{
            border-bottom: 1px solid black;
        }
        th, td {
            border: 1px solid black;
            padding: 1px;
            text-align: left;
            font-size: 62%;
            height: 16px;
        }
        th {
            font-size: 75%;
        }      
        .double-border {
            border-bottom: 2px solid black;
        }
        .right-border{
            border-right: 1px solid black;
        }
        .double-row{
            height: 18px;
            padding-top: 3px;
            padding-bottom: 3px;            
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
        <table style="width:100%">
            <tr>
            @foreach ($headers as $header)
                <td>
                    {{ $header['value'] }}
                </td>
            @endforeach
            </tr>
            @foreach ($results as $result)
                <tr>
                    @foreach ($headers as $header)
                    <td>
                        {{ $result[$header['key']]}}
                    </td>
                    @endforeach
                </tr>
            @endforeach
        </table>

</body>
</html>
