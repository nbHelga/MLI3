<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;
use App\Models\UserMenu;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->status !== 'Super Admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }
        
        $users = User::with('employee')->get();
        return view('admin.superadmin', compact('users'));
    }

    public function editForm($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Ambil semua menu
            $menus = Menu::orderBy('id')->get();
            
            // Ambil menu yang sudah dimiliki user
            $userMenus = DB::table('usermenu')
                ->where('id_users', $id)
                ->get();

            // \Log::info('Loading edit form', [
            //     'user_id' => $id,
            //     'total_menus' => $menus->count(),
            //     'user_menus' => $userMenus->count()
            // ]);

            return view('admin.editform', compact('user', 'menus', 'userMenus'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit form', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('admin.superadmin')
                ->with('error', 'Error loading user data: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $oldStatus = $user->status;
            
            // Validasi status yang diperbolehkan
            $request->validate([
                'status' => 'nullable|in:Super Admin,Administrator,Operator'
            ]);

            // Update menggunakan model
            $user->status = $request->status;
            $user->save();

            // \Log::info('Status updated successfully', [
            //     'user_id' => $id,
            //     'old_status' => $oldStatus,
            //     'new_status' => $request->status
            // ]);

            return redirect()->back()
                ->with('status_success', "User status updated successfully for {$user->employee->nama}");
        } catch (\Exception $e) {
            // \Log::error('Failed to update user status', [ 
            //     'error' => $e->getMessage(),
            //     'user_id' => $id
            // ]);

            return redirect()->back()
                ->with('status_error', "User status update failed for {$user->employee->nama}. Please try again.");
        }
    }

    // public function updateAccess(Request $request, $id)
    // {
    //     try {
    //         \Log::info('Received request for user access update', [
    //             'user_id' => $id,
    //             'menu_ids' => $request->menuIds
    //         ]);

    //         DB::beginTransaction();

    //         // Hapus semua akses menu yang ada
    //         DB::table('usermenu')->where('id_users', $id)->delete();

    //         // Tambahkan akses menu baru
    //         if (!empty($request->menuIds)) {
    //             $menuData = [];
    //             foreach ($request->menuIds as $menuId) {
    //                 $menuData[] = [
    //                     'id_users' => $id,
    //                     'id_menu' => $menuId,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ];
    //             }
    //             DB::table('usermenu')->insert($menuData);
    //         }

    //         DB::commit();

    //         return back()->with('menu_access_success', true);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Error updating user access', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return back()->with('menu_access_error', true);
    //     }
    // }

    public function destroy($id)
    {
        if (!Auth::check() || Auth::user()->status !== 'Super Admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            
            // Hapus semua akses menu terlebih dahulu
            UserMenu::where('id_users', $id)->delete();
            
            // Hapus user
            $user->delete();

            DB::commit();
            return redirect()->back()->with('delete_success', 'User has been deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('delete_error', 'Failed to delete user');
        }
    }

    // public function addAccess(Request $request, $id)
    // {
    //     \Log::info('Attempting to add menu access', [
    //         'user_id' => $id,
    //         'menu_id' => $request->menuId,
    //         'request_data' => $request->all()
    //     ]);

    //     try {
    //         $result = DB::table('usermenu')->insert([
    //             'id_users' => $id,
    //             'id_menu' => $request->menuId,
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);

    //         \Log::info('Menu access addition result', [
    //             'success' => $result,
    //             'user_id' => $id,
    //             'menu_id' => $request->menuId
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Akses menu berhasil ditambahkan'
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Failed to add menu access', [
    //             'user_id' => $id,
    //             'menu_id' => $request->menuId,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal menambahkan akses menu: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function removeAccess(Request $request, $id)
    // {
    //     \Log::info('Attempting to remove menu access', [
    //         'user_id' => $id,
    //         'menu_id' => $request->menuId,
    //         'request_data' => $request->all()
    //     ]);

    //     try {
    //         $result = DB::table('usermenu')
    //             ->where('id_users', $id)
    //             ->where('id_menu', $request->menuId)
    //             ->delete();

    //         \Log::info('Menu access removal result', [
    //             'success' => $result,
    //             'user_id' => $id,
    //             'menu_id' => $request->menuId
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Akses menu berhasil dihapus'
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Failed to remove menu access', [
    //             'user_id' => $id,
    //             'menu_id' => $request->menuId,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal menghapus akses menu: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function showAddUserForm()
    {
        // Ambil data employee yang belum memiliki user
        $employees = Employees::whereNotIn('id', function($query) {
            $query->select('id')->from('users');
        })->get();

        return view('admin.add-users', compact('employees'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employees,id|unique:users,id',
            'username' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6',
            'status' => 'required|in:Super Admin,Administrator,Operator'
        ]);

        try {
            User::create([
                'id' => $request->id,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'status' => $request->status
            ]);

            return redirect()
                ->route('admin.superadmin')
                ->with('add_success', 'User has been added successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('add_error', 'Failed to add user: ' . $e->getMessage());
        }
    }

    public function updateMenuAccess(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            
            // Convert status to boolean untuk mengecek apakah status adalah ada atau tidak. Karena kalau ada / true maka akan menambahkan akses menu, kalau tidak ada / false maka akan menghapus akses menu
            $status = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
            
            \Log::info('Updating menu access', [
                'user_id' => $request->user_id,
                'menu_id' => $request->menu_id,
                'status' => $status
            ]);

            if ($status) {
                // Tambah akses menu
                $exists = DB::table('usermenu')
                    ->where('id_users', $request->user_id)
                    ->where('id_menu', $request->menu_id)
                    ->exists();

                if (!$exists) {
                    DB::table('usermenu')->insert([
                        'id_users' => $request->user_id,
                        'id_menu' => $request->menu_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                return redirect()->back()
                    ->with('menu_access_granted', true);
            } else {
                // Hapus akses menu
                $deleted = DB::table('usermenu')
                    ->where('id_users', $request->user_id)
                    ->where('id_menu', $request->menu_id)
                    ->delete();
                
                \Log::info('Delete result', ['deleted' => $deleted]);
                    
                return redirect()->back()
                    ->with('menu_access_revoked', true);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to update menu access', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id,
                'menu_id' => $request->menu_id
            ]);

            return redirect()->back()
                ->with('menu_access_error', true);
        }
    }
} 