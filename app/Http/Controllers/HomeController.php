<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Route as TravelRoute;

class HomeController extends Controller
{
    public function index()
    {
        // Semua rute berangkat dari Pontianak -> jadikan kota asal tetap.
        $pontianak = City::where('slug', 'pontianak')->first() ?? City::orderBy('id')->first();

        // Rute dikelompokkan per kategori (pantura/hulu/provinsi) untuk tampilan beranda.
        $ruteByKategori = TravelRoute::with(['origin', 'dest'])
            ->orderBy('id')
            ->get()
            ->groupBy('kategori');

        // Daftar kota tujuan = tujuan dari rute yang berangkat dari Pontianak.
        $tujuan = $ruteByKategori->flatten()
            ->map(fn ($r) => $r->dest)
            ->filter()
            ->unique('id')
            ->sortBy('nama')
            ->values();

        return view('home', compact('pontianak', 'tujuan', 'ruteByKategori'));
    }
}
