@extends('layouts.app')

@section('content')
<div class="container">
<meta name="csrf-token" content="{{ csrf_token() }}">
<?php 
header("Access-Control-Allow-Origin: *");                                                                            
header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);
$time_start = microtime(true);

$All = [];

$link       = 'https://www.worldometers.info/coronavirus/';
$jsonData   = file_get_contents($link);

//echo $jsonData;

$dom = new DOMDocument;
$dom->loadHTML($jsonData);

$tables = $dom->getElementById('main_table_countries_today');
$tr     = $tables->getElementsByTagName('tr'); 

foreach ($tr as $element1) {        
    for ($i = 0; $i < count($element1); $i++) {

	if($element1->getElementsByTagName('td')->item(0)->textContent < 1)
	{
		continue;
	}

        //Not able to fetch the user's link :(
        $country       = $element1->getElementsByTagName('td')->item(1)->textContent;                  
        $total_cases   = $element1->getElementsByTagName('td')->item(2)->textContent;                 
        $new_cases     = $element1->getElementsByTagName('td')->item(3)->textContent;                  
        $total_deaths  = $element1->getElementsByTagName('td')->item(4)->textContent;                 
        $new_deaths    = $element1->getElementsByTagName('td')->item(5)->textContent;                  
	    $total_recover = $element1->getElementsByTagName('td')->item(6)->textContent;                 

        array_push($All, array(
            "country"       => $country,
            "total_cases"   => $total_cases,
            "new_cases"     => $new_cases,
            "total_deaths"  => $total_deaths,
            "new_deaths"    => $new_deaths,
            "total_recover" => $total_recover
        ));
    }
}

$json = json_encode($All, JSON_PRETTY_PRINT);

$data =  json_decode($json);

echo "<table style='width: 100%; border-collapse: collapse; font-family: arial, sans-serif;'>";
echo "<tr>";
echo " 
    <th style='border: 1px solid #dddddd; padding: 8px;'>Country</th>
    <th style='border: 1px solid #dddddd; padding: 8px;'>Total cases</th>
    <th style='border: 1px solid #dddddd; padding: 8px;'>New cases</th> 
    <th style='border: 1px solid #dddddd; padding: 8px;'>Total deaths</th> 
    <th style='border: 1px solid #dddddd; padding: 8px;'>New deaths</th> 
    <th style='border: 1px solid #dddddd; padding: 8px;'>total recoveries</th> 
     ";
echo "</tr>";

foreach ($data as $stand) {
    // Output a row
    echo "<tr>";
    echo "<td style='border: 1px solid #dddddd; padding: 8px;'> $stand->country </td>";
    echo "<td style='border: 1px solid #dddddd; padding: 8px;'> $stand->total_cases </td>";
    echo "<td style='border: 1px solid #dddddd; padding: 8px;'> $stand->new_cases </td>";
    echo "<td style='border: 1px solid #dddddd; padding: 8px;'> $stand->total_deaths </td>";
    echo "<td style='border: 1px solid #dddddd; padding: 8px;'> $stand->new_deaths </td>";
    echo "<td style='border: 1px solid #dddddd; padding: 8px;'> $stand->total_recover </td>";
    echo "</tr>";
}
echo "</table>";
?>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
</div>
@endsection
