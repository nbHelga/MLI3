@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Super Admin Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Statistics Cards -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-800">Total Users</h3>
                <p class="text-2xl font-bold text-blue-600">0</p>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-semibold text-green-800">Active Users</h3>
                <p class="text-2xl font-bold text-green-600">0</p>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="font-semibold text-purple-800">Total Departments</h3>
                <p class="text-2xl font-bold text-purple-600">0</p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button class="bg-white border border-gray-300 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                    <span class="block font-medium">Manage Users</span>
                    <span class="text-sm text-gray-500">Add, edit, or remove users</span>
                </button>
                
                <button class="bg-white border border-gray-300 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                    <span class="block font-medium">Manage Roles</span>
                    <span class="text-sm text-gray-500">Configure user roles and permissions</span>
                </button>
                
                <button class="bg-white border border-gray-300 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                    <span class="block font-medium">System Settings</span>
                    <span class="text-sm text-gray-500">Configure application settings</span>
                </button>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <p class="text-gray-600">No recent activity</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
