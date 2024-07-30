<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
</head>
<body>
    <h1>Reentrancy Vulnerability Report</h1>

    <pre>{{ print_r($report, true) }}</pre>

    @if (empty($report))
        <p>No vulnerabilities found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Contract</th>
                    <th>Function</th>
                    <th>Line Number</th>
                    <th>Code</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report as $vulnerability)
                    <tr>
                        <td>{{ $vulnerability['contract'] }}</td>
                        <td>{{ $vulnerability['function'] }}</td>
                        <td>{{ $vulnerability['line_number'] }}</td>
                        <td>{{ $vulnerability['code'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
