<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
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

        return view('admin.properties.create', compact('propertyTypes'));
    }

    public function store(Request $request)
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

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['created_by'] = auth()->id();

        Property::create($validated);

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Mülk başarıyla oluşturuldu.');
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