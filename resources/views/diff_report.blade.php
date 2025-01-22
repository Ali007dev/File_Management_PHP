<!DOCTYPE html>
<html>
<head>
    <title>تقرير المقارنة</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        h1, h2 { color: #333; }
        ul { list-style-type: none; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>تقرير المقارنة بين الملفات</h1>
    <h2>السطور المضافة:</h2>
    <ul>
        @foreach ($diffResults['added'] as $line)
            <li>تم إضافة: {{ $line }}</li>
        @endforeach
    </ul>
    <h2>السطور المحذوفة:</h2>
    <ul>
        @foreach ($diffResults['removed'] as $line)
            <li>تم حذف: {{ $line }}</li>
        @endforeach
    </ul>
</body>
</html>
