<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', '!=', 'admin');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        return view('admin.users.index', [
            'users' => $query->orderByDesc('created_at')->paginate(20)->withQueryString(),
            'search' => $request->search ?? '',
        ]);
    }

    public function show(User $user): View
    {
        // Prevent viewing admin users
        if ($user->isAdmin()) {
            abort(403, 'Cannot view admin user details.');
        }

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting admin users
        if ($user->isAdmin()) {
            abort(403, 'Cannot delete admin users.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        try {
            $userName = $user->name;
            
            // Delete related data in transaction
            \DB::transaction(function () use ($user) {
                // Delete user's comments
                $user->comments()->delete();
                // Delete user's likes
                \DB::table('post_likes')->where('user_id', $user->id)->delete();
                // Delete user
                $user->delete();
            });

            return redirect()->route('admin.users.index')
                ->with('status', "User '{$userName}' has been deleted successfully.");
        } catch (\Exception $e) {
            \Log::error('User deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete user. Please try again.']);
        }
    }
}
