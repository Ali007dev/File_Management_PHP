<!DOCTYPE html>
<html>
<head>
    <title>User Activity Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Report for User: {{ $data['userName'] }}</h1>
    <p>Email: {{ $data['email'] }}</p>
    <p>Phone Number: {{ $data['number'] }}</p>

    <h2>User File Operations</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Operation</th>
                <th>File</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data['fileLogs'] as $log)
                <tr>
                    <td>{{ $log['date'] }}</td>
                    <td>{{ $log['operation'] }}</td>
                    <td>{{ $log['m_file']['name'] ?? 'No name available' }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
