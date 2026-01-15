<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query Dasar: Ambil user yang BUKAN admin
        $query = User::where('role', '!=', 'admin');

        // 2. Logika Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Eksekusi dengan Pagination
        // Variabel '$users' ini berisi Object LengthAwarePaginator
        $users = $query->withCount('courses')
                        ->latest()
                        ->paginate(10)
                        ->withQueryString();

        return view('admin.users.index', compact('users'));
    }
}