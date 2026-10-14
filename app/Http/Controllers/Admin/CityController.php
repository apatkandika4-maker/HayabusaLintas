<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(): View
    {
        $cities = City::withCount(['originRoutes', 'destRoutes'])->orderBy('nama')->get();

        return view('admin.cities.index', compact('cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
        ], [
            'nama.required' => 'Nama kota wajib diisi.',
        ]);

        $slug = $this->slugUnik($data['nama']);

        if (City::where('slug', $slug)->exists()) {
            return back()->with('gagal', "Kota \"{$data['nama']}\" sudah ada.")->withInput();
        }

        City::create(['nama' => $data['nama'], 'slug' => $slug]);

        return redirect()->route('admin.cities.index')->with('sukses', "Kota \"{$data['nama']}\" ditambahkan.");
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
        ], [
            'nama.required' => 'Nama kota wajib diisi.',
        ]);

        $slug = $this->slugUnik($data['nama']);

        if (City::where('slug', $slug)->where('id', '!=', $city->id)->exists()) {
            return back()->with('gagal', "Kota \"{$data['nama']}\" sudah ada.")->withInput();
        }

        $city->update(['nama' => $data['nama'], 'slug' => $slug]);

        return redirect()->route('admin.cities.index')->with('sukses', 'Kota berhasil diperbarui.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->loadCount(['originRoutes', 'destRoutes']);

        if ($city->origin_routes_count > 0 || $city->dest_routes_count > 0) {
            return back()->with('gagal', "Kota \"{$city->nama}\" masih dipakai di rute. Hapus/ubah rute terkait dulu.");
        }

        $nama = $city->nama;
        $city->delete();

        return redirect()->route('admin.cities.index')->with('sukses', "Kota \"{$nama}\" dihapus.");
    }

    private function slugUnik(string $nama): string
    {
        return Str::slug($nama);
    }
}
