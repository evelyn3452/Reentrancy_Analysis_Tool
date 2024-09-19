@extends('components.layout')

@section('content')

<div class="container">
    <h1>Analysis Results</h1>
    <p><strong>Uploaded File:</strong> {{ $fileName }}</p>
    @if(isset($result['reentrancy_issues']) && count($result['reentrancy_issues']) > 0)
        @foreach($result['reentrancy_issues'] as $issue)
            <div class="result">
                <h3>{{ $issue['contract_name'] }}</h3> </br>
                <div class="func_name" id="func_name">
                    <p>at line {{ $issue['start_line'] }}, function {{ $issue['function_name'] }} ()
                    <span style='font-size:15px;'>&#11167;</span></p>
                </div>
                <div class="code_Block" id="code_Block" style="display: none">
                    <pre>{{ $issue['code_block'] }}</pre> 
                </div>
            </div>
        @endforeach
    @elseif (isset($result['reentrancy_issues'])  == " ")
    <p>No reentrancy vulnerabilities found.</p>
    @else
    <p>There is no file being uploaded.</p>
    @endif
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#func_name").click(function(){
    $("#code_Block").slideToggle();
  });
});
</script>
@endsection

