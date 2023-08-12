<?php

namespace App\Http\Controllers;

use App\Events\addedDataEvent;
use App\Models\Countries;
use App\Models\Covid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CovidController extends Controller
{
    public function getBarChartData(Request $request)
    {
        $countryData = Covid::where('country_id', $request->country)
            // use When for filtering by date
            ->when($request->from, function ($query) use ($request) {
                // When the Request has from then this method will be called
                return $query->whereDate('date', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                // When the Request has to then this method will be called
                return $query->whereDate('date', '<=', $request->to);
            })
            ->selectRaw('SUM(Confirmed) as Confirmed, SUM(Recovered) as Recovered, SUM(Deaths) as Deaths')
            ->first();

        // to get the country Name From Country Table
        $country = Countries::where('id', $request->country)->first()->name;

        // return the as a JSON

        return response()->json(['country' => $country, 'countryData' => $countryData]);
    }


    public function getBubbleChartData(Request $request)
    {
        $data = Covid::where('country_id', $request->country)
            // use When for filtering by date
            ->when($request->from, function ($query) use ($request) {
                return $query->whereDate('date', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                return $query->whereDate('date', '<=', $request->to);
            })
            ->orderBy('date')
            ->limit(50)
            ->get();

        // start Getting the Bubble Size
        $totalCases = $data->pluck('Active');
        $totalCasesArray = $totalCases->toArray();
        $maxTotalCases = max($totalCasesArray);
        $bubleSizes = $totalCases->map(function ($cases) use ($maxTotalCases) {
            return ($cases / $maxTotalCases) * 25;
        });

        // return the data as json
        return response()->json([
            'cases' => $data->pluck('Confirmed'),
            'deaths' => $data->pluck('Deaths'),
            'recoveries' => $data->pluck('Recovered'),
            'bubbleSizes' => $bubleSizes->values(),
        ]);
    }

    public function getDoughnutChartData(Request $request)
    {
        $data = Covid::where('country_id', $request->country)
            // use When for filtering by date
            ->when($request->from, function ($query) use ($request) {
                return $query->whereDate('date', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                return $query->whereDate('date', '<=', $request->to);
            })
            ->select('Confirmed', 'Deaths', 'Recovered')
            ->orderBy('date', 'desc')
            ->first();

        return response()->json(['data' => [
            $data->Confirmed,
            $data->Recovered,
            $data->Deaths
        ]]);
    }

    public function getHorizontalBarChartData(Request $request)
    {
        $data = Covid::with('country')
            ->when($request->from, function ($query) use ($request) {
                return $query->whereDate('date', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                return $query->whereDate('date', '<=', $request->to);
            })
            ->limit(30)
            ->get();
        $countries = $data->pluck('country.name');
        $casesData = $data->pluck('Deaths');

        return response()->json([
            'countries' => $countries,
            'cases' => $casesData,
        ]);
    }

    public function polarAreaChartData(Request $request)
    {
        $data = Covid::where('country_id', $request->country)
            ->when($request->from, function ($query) use ($request) {
                return $query->whereDate('date', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                return $query->whereDate('date', '<=', $request->to);
            })
            ->limit(8)
            ->get();

        return response()->json([
            'labels' => $data->pluck('date')->toArray(),
            'casesData' => $data->pluck('Confirmed')->toArray(),
        ]);
    }

    public function realTimeChart(Request $request)
    {
        $data = Covid::where('country_id', $request->country)
            ->limit(20)
            ->orderBy('date', 'desc')
            ->get();

        $country = Countries::where('id', $request->country)->first();
        return response()->json([
            'country' => $country->name,
            'labels' => $data->pluck('date')->toArray(),
            'Confirmed' => $data->pluck('Confirmed')->toArray(),
        ]);
    }

    public function addData()
    {
        $date = Carbon::now()->startOfDay();

        for ($i = 1; $i <= 20; $i++) {
            $item = new Covid();
            $item->country_id = 29;
            $item->date = $date->toDateString();
            $item->Confirmed = rand(0, 200);
            $item->Deaths = rand(0, 100);
            $item->Recovered = rand(0, 100);
            $item->Active = rand(0, 100);
            $item->save();

            // Increase the date by one day
            $date->addDay();
            event(new addedDataEvent('Afghanistan', $item->date, $item->Confirmed));
            sleep(2);
        }
    }

    public function scatterLineChartData(Request $request)
    {
        $data = Covid::select('date', 'Confirmed', 'Deaths', 'Recovered', 'Active')
            ->where('country_id', $request->country)
            ->when($request->from, function ($query) use ($request) {
                return $query->whereDate('date', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                return $query->whereDate('date', '<=', $request->to);
            })
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }
}
