<!DOCTYPE html>
<html>
<head>
    <title>File Comparison Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        h1, h2 { color: #333; }
        ul { list-style-type: none; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>File Comparison Report</h1>
    <h2>Added Lines:</h2>
    <ul>
        @foreach ($diffResults['added'] as $line)
            <li>Added: {{ $line }}</li>
        @endforeach
    </ul>
    <h2>Removed Lines:</h2>
    <ul>
        @foreach ($diffResults['removed'] as $line)
            <li>Removed: {{ $line }}</li>
        @endforeach
    </ul>
</body>
</html>
