<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Amenity;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('propertyType')->latest()->paginate(10);

        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        $propertyTypes = PropertyType::all();
        $amenities = Amenity::all();

        return view('admin.properties.create', compact('propertyTypes', 'amenities'));
    }

    public function store(Request $request)
    {
        // 1. Düzeltme: Kapasite, Görsel, Olanak ve Politika doğrulama kuralları eklendi
        $validated = $request->validate([
            'property_type_id' => 'required|exists:property_types,id',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'price_per_night'  => 'required|numeric|min:0',
            
            // Otel tiplerinde alan gizlendiği için nullable yapıldı (veya varsayılan 1 verilir)
            'capacity'         => 'nullable|integer|min:1',
            'bedrooms'         => 'nullable|integer|min:0',
            'bathrooms'        => 'nullable|integer|min:0',
            
            'address'          => 'required|string|max:255',
            'city'             => 'required|string|max:100',
            'country'          => 'required|string|max:100',
            'status'           => 'required|in:draft,published,inactive',

            // İlişkili Verilerin Validation Kuralları
            'amenities'        => 'nullable|array',
            'amenities.*'      => 'exists:amenities,id',
            
            'policies'         => 'nullable|array',
            'policies.*.icon'  => 'nullable|string',
            'policies.*.title' => 'required_with:policies|string|max:255',
            'policies.*.description' => 'nullable|string',

            'images'           => 'nullable|array',
            'images.*'         => 'image|mimes:jpeg,png,jpg,webp|max:2048' // Max 2MB
        ]);

        // Slug ve Kullanıcı Bilgisi
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['created_by'] = auth()->id() ?? 1;

        // Varsayılan Kapasite Ayarı (Otel seçilip boş geçildiyse 1 atar)
        $validated['capacity'] = $validated['capacity'] ?? 1;

        // 2. Mülk Ana Kaydını Oluştur
        $property = Property::create($validated);

        // 3. Düzeltme: Olanakları (Amenities) Çoka Çok (Many-to-Many) Bağla
        if ($request->has('amenities')) {
            $property->amenities()->sync($request->amenities);
        }

        // 4. Düzeltme: Görselleri Yükle ve Kaydet
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public'); // storage/app/public/properties klasörüne atar
                
                // Eğer PropertyImage modeliniz varsa:
                $property->images()->create([
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Mülk ve bağlı tüm veriler başarıyla oluşturuldu.');
    }

    public function edit(Property $property)
    {
        $propertyTypes = PropertyType::all();

        return view('admin.properties.edit', compact('property', 'propertyTypes'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'property_type_id' => 'required|exists:property_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'status' => 'required|in:draft,published,inactive',
        ]);

        $property->update($validated);

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Mülk başarıyla güncellendi.');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Mülk başarıyla silindi.');
    }
}