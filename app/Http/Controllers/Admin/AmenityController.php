<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AmenityController extends Controller
{
    /**
     * 1. LISTELEME (index)
     * Tüm olanakları (WiFi, Klima vb.) listeler.
     */
    public function index()
    {
        // En son eklenen olanağı en üstte tutarak 10'arlı sayfalar halinde çekiyoruz
        $amenities = Amenity::latest()->paginate(10);

        return view('admin.amenities.index', compact('amenities'));
    }

    /**
     * 2. EKLEME FORMU SAYFASI (create)
     */
    public function create()
    {
        return view('admin.amenities.create');
    }

    /**
     * 3. VERİTABANINA KAYIT (store)
     */
    public function store(Request $request)
    {
        // A) Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name',
            'icon' => 'nullable|string|max:100', // Örn: 'bx-wifi' veya 'bx-swim'
            'is_active' => 'nullable|boolean',
        ]);

        // B) Slug Oluşturma
        // Örn: "Otopark & Garaj" -> "otopark-garaj"
        $validated['slug'] = Str::slug($request->name);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : 1;

        // C) Kayıt
        Amenity::create($validated);

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Olanak başarıyla eklendi.');
    }

    /**
     * 4. DÜZENLEME FORMU SAYFASI (edit)
     * Route Model Binding ile seçilen Amenity otomatik yakalanır.
     */
    public function edit(Amenity $amenity)
    {
        return view('admin.amenities.edit', compact('amenity'));
    }

    /**
     * 5. GÜNCELLEME İŞLEMİ (update)
     */
    public function update(Request $request, Amenity $amenity)
    {
        // Validation (Unique kuralında mevcut olanağın ID'sini hariç tutuyoruz)
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name,' . $amenity->id,
            'icon' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($request->name);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : 1;

        $amenity->update($validated);

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', 'Olanak başarıyla güncellendi.');
    }

    /**
     * 6. SİLME İŞLEMİ (destroy)
     */
    public function destroy(Amenity $amenity)
    {
        // Mantıksal Güvenlik Kontrolü:
        $amenity->update([
            'is_active' => !$amenity->is_active
        ]);

        $statusMessage = $amenity->is_active ? 'aktif' : 'pasif';

        return redirect()
            ->route('admin.amenities.index')
            ->with('success', "Olanak durumu '{$statusMessage}' olarak güncellendi.");
    }
}