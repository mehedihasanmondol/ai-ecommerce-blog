<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Services\RoleService;
use App\Modules\User\Repositories\PermissionRepository;
use App\Modules\User\Requests\StoreRoleRequest;
use App\Modules\User\Requests\UpdateRoleRequest;

/**
 * ModuleName: User Management
 * Purpose: Handle role management operations in admin panel
 * 
 * Key Methods:
 * - index(): List all roles
 * - create(): Show create role form
 * - store(): Store new role
 * - edit(): Show edit role form
 * - update(): Update role
 * - destroy(): Delete role
 * 
 * Dependencies:
 * - RoleService
 * - PermissionRepository
 * 
 * @author AI Assistant
 * @date 2025-11-04
 */
class RoleController extends Controller
{
    protected RoleService $roleService;
    protected PermissionRepository $permissionRepository;

    public function __construct(
        RoleService $roleService,
        PermissionRepository $permissionRepository
    ) {
        $this->roleService = $roleService;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of roles
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('roles.view'), 403, 'You do not have permission to view roles.');

        $roles = $this->roleService->getAllRoles();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     * Only shows permissions for modules that are enabled in system settings
     */
    public function create()
    {
        abort_if(!auth()->user()->hasPermission('roles.create'), 403, 'You do not have permission to create roles.');

        $permissions = $this->permissionRepository->getActiveByEnabledModules();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(StoreRoleRequest $request)
    {
        abort_if(!auth()->user()->hasPermission('roles.create'), 403, 'You do not have permission to create roles.');

        $result = $this->roleService->createRole($request->validated());

        if ($result['success']) {
            return redirect()
                ->route('admin.roles.index')
                ->with('success', $result['message']);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /**
     * Display the specified role
     */
    public function show(int $id)
    {
        abort_if(!auth()->user()->hasPermission('roles.view'), 403, 'You do not have permission to view roles.');

        $role = $this->roleService->getRoleById($id);

        if (!$role) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Role not found');
        }

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role
     * Only shows permissions for modules that are enabled in system settings
     */
    public function edit(int $id)
    {
        abort_if(!auth()->user()->hasPermission('roles.edit'), 403, 'You do not have permission to edit roles.');

        $role = $this->roleService->getRoleById($id);

        if (!$role) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Role not found');
        }

        $permissions = $this->permissionRepository->getActiveByEnabledModules();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role
     */
    public function update(UpdateRoleRequest $request, int $id)
    {
        abort_if(!auth()->user()->hasPermission('roles.edit'), 403, 'You do not have permission to edit roles.');

        $result = $this->roleService->updateRole($id, $request->validated());

        if ($result['success']) {
            return redirect()
                ->route('admin.roles.edit', $id)
                ->with('success', $result['message']);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /**
     * Remove the specified role
     */
    public function destroy(int $id)
    {
        abort_if(!auth()->user()->hasPermission('roles.delete'), 403, 'You do not have permission to delete roles.');

        $result = $this->roleService->deleteRole($id);

        if ($result['success']) {
            return redirect()
                ->route('admin.roles.index')
                ->with('success', $result['message']);
        }

        return redirect()
            ->back()
            ->with('error', $result['message']);
    }
}
