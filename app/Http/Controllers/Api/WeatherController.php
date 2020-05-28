<?php

namespace App\Http\Controllers\Api;

// use Forecast;
use App\Models\Weather;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeatherController extends Controller
{
    public function getWeather() {
    	// $weather_data = Forecast::get('27.7172','85.3240');
    	
    	// $weather = new Weather;
    	// $weather->summary = $weather_data['daily']['summary'];
    	// $weather->icon = $weather_data['daily']['icon'];
    	// $weather->daily_data = serialize($weather_data['daily']['data']);
    	// $weather->save();

    	dd('weather saved');
    }
}
