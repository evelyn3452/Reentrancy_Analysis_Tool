@extends('components.layout')

@section('content')

<div class="container">
    <h1>Analysis Results</h1>
    @if(isset($result['reentrancy_issues']) && count($result['reentrancy_issues']) > 0)
        @foreach($result['reentrancy_issues'] as $issue)
            <div class="result">
                {{ $issue['contract_name'] }} </br>
                <span>function </span>{{ $issue['function_name'] }} <span>()</span> </br>
                {{ $issue['impact'] }} </br>
                <pre>{{ $issue['code_block'] }}</pre> 
            </div>
        @endforeach
    @else
        <p>No reentrancy vulnerabilities found.</p>
    @endif
</div>
@endsection
