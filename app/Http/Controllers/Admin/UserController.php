<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tüm kullanıcıları listeler.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Kullanıcıyı veritabanından siler.
     */
    public function destroy(User $user)
    {
        // Kendini silmesini engellemek için güvenlik kontrolü
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Kendi hesabınızı silemezsiniz!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }
}