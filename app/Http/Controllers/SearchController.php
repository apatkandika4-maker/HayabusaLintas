<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchScheduleRequest;
use App\Models\City;
use App\Models\Schedule;

class SearchController extends Controller
{
    public function cari(SearchScheduleRequest $request)
    {
        $data = $request->validated();

        $schedules = Schedule::with(['route.origin', 'route.dest'])
            ->whereDate('tanggal', $data['tanggal'])
            ->whereHas('route', function ($q) use ($data) {
                $q->where('origin_city_id', $data['origin'])
                    ->where('dest_city_id', $data['dest']);
            })
            ->where('sisa_kursi', '>', 0)
            ->orderBy('id')
            ->get();

        $cities = City::orderBy('nama')->get();

        return view('search', [
            'cities' => $cities,
            'schedules' => $schedules,
            'originId' => (int) $data['origin'],
            'destId' => (int) $data['dest'],
            'tanggal' => $data['tanggal'],
        ]);
    }
}
