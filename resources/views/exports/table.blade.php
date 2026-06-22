<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { color: #2E7D32; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ECEFF1; padding: 8px; text-align: left; }
        th { background: #2E7D32; color: white; }
        tr:nth-child(even) { background: #FAFAFA; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Généré le {{ now()->format('d/m/Y H:i') }} — {{ config('agri.name') }}</p>
    <table>
        <thead>
            <tr>@foreach($headers as $h)<th>{{ $h }}</th>@endforeach</tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>@foreach($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
