<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meeting Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            margin: 40px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 10px;
            border-bottom: 3px solid #4CAF50;
        }

        .header h1 {
            font-size: 26px;
            margin: 0;
            color: #222;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .meta {
            margin-bottom: 25px;
        }
        .meta p {
            margin: 2px 0;
        }

        .section-title {
            background: linear-gradient(to right, #4CAF50, #81C784);
            color: #fff;
            padding: 8px 12px;
            font-weight: bold;
            margin-top: 30px;
            border-radius: 4px;
        }

        p {
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) td {
            background-color: #fafafa;
        }

        footer {
            position: fixed;
            bottom: 15px;
            left: 40px;
            right: 40px;
            font-size: 12px;
            color: #777;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        {{-- Optional logo --}}
        {{-- <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" style="height:60px; margin-bottom:10px;"> --}}
        <h1>Meeting Report</h1>
    </div>

    <div class="meta">
        <p><strong>Title:</strong> {{ $meeting->title }}</p>
        <p><strong>Date:</strong> {{ $meeting->date->format('F d, Y') }}</p>
        <p><strong>Time:</strong> {{ $meeting->start_time }} – {{ $meeting->end_time }}</p>
        <p><strong>Location:</strong> {{ $meeting->location }}</p>
        @if($meeting->project)
            <p><strong>Project:</strong> {{ $meeting->project->name }}</p>
        @endif
        @if($meeting->board)
            <p><strong>Board:</strong> {{ $meeting->board->name }}</p>
        @endif
        <p><strong>Description:</strong> {{ $meeting->description }}</p>
    </div>

    <div class="section-title">AI‑Generated Summary</div>
    <p>{!! nl2br(e($content)) !!}</p>

    <div class="section-title">Attendees</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Name</th>
                <th style="width: 20%;">Status</th>
                <th style="width: 40%;">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meeting->attendances as $a)
                <tr>
                    <td>{{ $a->user->name }}</td>
                    <td>{{ ucfirst(str_replace('_',' ', $a->status)) }}</td>
                    <td>{{ $a->notes ?: '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Generated on {{ now()->format('F d, Y H:i') }} by Meeting Manager
    </footer>

</body>
</html>