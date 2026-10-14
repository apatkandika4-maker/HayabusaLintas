<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Route as TravelRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function index(): View
    {
        $routes = TravelRoute::with(['origin', 'dest'])->orderBy('id')->paginate(15);

        return view('admin.routes.index', compact('routes'));
    }

    public function create(): View
    {
        return $this->form(new TravelRoute());
    }

    public function store(Request $request): RedirectResponse
    {
        TravelRoute::create($this->validated($request));

        return redirect()->route('admin.routes.index')->with('sukses', 'Rute berhasil ditambahkan.');
    }

    public function edit(TravelRoute $route): View
    {
        return $this->form($route);
    }

    public function update(Request $request, TravelRoute $route): RedirectResponse
    {
        $route->update($this->validated($request));

        return redirect()->route('admin.routes.index')->with('sukses', 'Rute berhasil diperbarui.');
    }

    public function destroy(TravelRoute $route): RedirectResponse
    {
        $route->delete();

        return redirect()->route('admin.routes.index')->with('sukses', 'Rute berhasil dihapus.');
    }

    private function form(TravelRoute $route): View
    {
        $cities = City::orderBy('nama')->get();

        return view('admin.routes.form', compact('route', 'cities'));
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'origin_city_id' => 'required|exists:cities,id|different:dest_city_id',
            'dest_city_id' => 'required|exists:cities,id',
            'kategori' => 'required|in:pantura,hulu,provinsi',
            'harga_dasar' => 'required|integer|min:0',
        ]);
    }
}
