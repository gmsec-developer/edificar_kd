<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Project;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('superadmin');

        if ($isSuperAdmin) {
            $totalCompanies = Company::count();
            $activeCompanies = Company::where('status', 'active')->count();
            $inactiveCompanies = Company::where('status', 'inactive')->count();

            $totalUsers = User::count();
            $activeUsers = User::where('is_active', true)->count();
            $inactiveUsers = User::where('is_active', false)->count();

            $rolesCount = Role::count();
            $permissionsCount = Permission::count();

            $totalProjects = Project::count();
            $validatedProjects = Project::where('status', 'technical_validated')->count();
            $observedProjects = Project::where('status', 'technical_observed')->count();

            $latestProjects = Project::with(['company', 'user'])
                ->latest()
                ->take(6)
                ->get();

            $logs = Activity::with('causer')->latest()->take(6)->get();

            $companies = Company::withCount('users')
                ->orderBy('name')
                ->take(8)
                ->get();

            return view('dashboard', compact(
                'isSuperAdmin',
                'totalCompanies',
                'activeCompanies',
                'inactiveCompanies',
                'totalUsers',
                'activeUsers',
                'inactiveUsers',
                'rolesCount',
                'permissionsCount',
                'totalProjects',
                'validatedProjects',
                'observedProjects',
                'latestProjects',
                'logs',
                'companies'
            ));
        }

        $companyId = $user->company_id;

        $totalUsers = User::where('company_id', $companyId)->count();
        $activeUsers = User::where('company_id', $companyId)->where('is_active', true)->count();
        $inactiveUsers = User::where('company_id', $companyId)->where('is_active', false)->count();

        $rolesCount = Role::count();
        $permissionsCount = Permission::count();

        $totalProjects = Project::where('company_id', $companyId)->count();
        $validatedProjects = Project::where('company_id', $companyId)
            ->where('status', 'technical_validated')
            ->count();
        $observedProjects = Project::where('company_id', $companyId)
            ->where('status', 'technical_observed')
            ->count();

        $latestProjects = Project::with(['company', 'user'])
            ->where('company_id', $companyId)
            ->latest()
            ->take(6)
            ->get();

        $logs = Activity::with('causer')
            ->whereHas('causer', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'isSuperAdmin',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'rolesCount',
            'permissionsCount',
            'totalProjects',
            'validatedProjects',
            'observedProjects',
            'latestProjects',
            'logs'
        ));
    }
}