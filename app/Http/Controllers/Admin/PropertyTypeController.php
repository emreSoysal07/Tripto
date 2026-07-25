<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Slug (URL dostu metin) üretmek için gerekli yardımcısı

class PropertyTypeController extends Controller
{
    /**
     * 1. LISTELEME (index)
     * Tüm emlak tiplerini veritabanından çekip sayfada gösterir.
     */
    public function index()
    {
        // PropertyType::latest() -> En son eklenen veriyi en üstte getirmek için sıralar.
        // paginate(10) -> Verileri 10'arlı sayfalara böler (Önceki adımda kurduğumuz Sneat pagination çalışacak).
        $propertyTypes = PropertyType::latest()->paginate(10);

        // compact('propertyTypes') -> $propertyTypes değişkenini 'propertyTypes' adıyla Blade şablonuna gönderir.
        return view('admin.property-types.index', compact('propertyTypes'));
    }

    /**
     * 2. EKLEME FORMU SAYFASI (create)
     * Yeni emlak tipi ekleme formunu ekrana getirir.
     */
    public function create()
    {
        return view('admin.property-types.create');
    }

    /**
     * 3. VERİTABANINA KAYIT (store)
     * Formdan gelen verileri doğrular (validation), işler ve veritabanına kaydeder.
     */
    public function store(Request $request)
    {
        // A) VALIDATION (Doğrulama):
        // Kullanıcıdan gelen isteklerin (request) veritabanına girmeden önceki güvenlik kapısıdır.
        $validated = $request->validate([
            'name'      => 'required|string|max:255|unique:property_types,name',
            'icon'      => 'nullable|string|max:100', // Örn: 'bx-building-house' (Boxicons)
            'has_rooms' => 'nullable|boolean',        // Formda checkbox işaretlendiyse true/1 gelir
            'is_active' => 'nullable|boolean',
        ]);

        // B) SLUG OLUŞTURMA:
        // Slug, başlığı URL yapısına uygun hale getirir (Örn: "Müstakil Ev" -> "mustakil-ev").
        // Str::slug() Türkçe karakterleri de temizleyerek dönüştürür.
        $validated['slug'] = Str::slug($request->name);

        // C) CHECKBOX (BOOLEAN) KONTROLÜ:
        // Formlarda checkbox işaretlenmediğinde HTTP isteğinde hiç gönderilmez.
        // Bu yüzden $request->has('has_rooms') diyerek işaretli mi (true/false) kontrol ediyoruz.
        $validated['has_rooms'] = $request->has('has_rooms');
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : 1;

        // D) VERİTABANINA EKLEME:
        // Model üzerindeki $fillable alanlarına izin verilen verileri topluca kaydeder.
        PropertyType::create($validated);

        // E) YÖNLENDİRME (Redirect):
        // Başarılı mesajıyla (session flash) kullanıcıyı tekrar liste sayfasına gönderir.
        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'Emlak tipi başarıyla oluşturuldu.');
    }

    /**
     * 4. DÜZENLEME FORMU SAYFASI (edit)
     * Seçilen kaydın bilgilerini forma doldurmak üzere getirir.
     * 
     * NOT: (PropertyType $propertyType) -> "Route Model Binding" özelliğidir.
     * URL'den gelen ID'yi (örn: /admin/property-types/5/edit) otomatik olarak bulur ve nesneye dönüştürür.
     * Eğer 5 ID'li kayıt yoksa otomatik 404 hatası döner.
     */
    public function edit(PropertyType $propertyType)
    {
        return view('admin.property-types.edit', compact('propertyType'));
    }

    /**
     * 5. GÜNCELLEME İŞLEMİ (update)
     * Düzenleme formundan gelen verileri veritabanında günceller.
     */
    public function update(Request $request, PropertyType $propertyType)
    {
        // Validation yaparken unique kuralında mevcut kaydın kendi ID'sini hariç tutuyoruz ($propertyType->id).
        // Aksi halde adını değiştirmeden kaydetmek istediğinde "Bu isim zaten var" hatası verir.
        $validated = $request->validate([
            'name'      => 'required|string|max:255|unique:property_types,name,' . $propertyType->id,
            'icon'      => 'nullable|string|max:100',
            'has_rooms' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($request->name);
        $validated['has_rooms'] = $request->has('has_rooms');
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : 1;

        // Mevcut nesne üzerindeki bilgileri günceller.
        $propertyType->update($validated);

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'Emlak tipi başarıyla güncellendi.');
    }

    /**
     * 6. SİLME İŞLEMİ (destroy)
     * Seçilen kaydı veritabanından siler.
     */
    public function destroy(PropertyType $propertyType)
    {
        // İLERİ DÜZEY NOT: Bu emlak tipine bağlı mülkler (Property) var mı kontrolü eklenebilir.
        // Eğer bu emlak tipine bağlı ilanlar varsa silinmesini engelleyebiliriz:
        $propertyType->update([
            'is_active' => !$propertyType->is_active
        ]);

        $statusMessage = $propertyType->is_active ? 'aktif' : 'pasif';

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', "Emlak tipi durumu '{$statusMessage}' olarak güncellendi.");
    }
}