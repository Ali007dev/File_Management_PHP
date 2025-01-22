<!DOCTYPE html>
<html>
<head>
    <title>Archive Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        h1, h2, p { color: #333; }
        pre { background-color: #f4f4f4; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Archive Report for File: {{ $diffResults['m_file']['name'] ?? 'Unknown File' }}</h1>
    <h2>Operation: {{ $diffResults['operation'] ?? 'Unknown Operation' }}</h2>
    <p>Date of Operation: {{ $diffResults['date'] ?? 'Unknown Date' }}</p>
    <h2>Old Content:</h2>
    @php
        $fileData = json_decode($diffResults['file'] ?? '{}', true);
    @endphp
    @if ($fileData && array_key_exists('old', $fileData))
        <pre>{{ $fileData['old'] }}</pre>
    @else
        <p>No old content available.</p>
    @endif
</body>
</html>
