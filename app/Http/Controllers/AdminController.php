<?php

namespace App\Http\Controllers;


use App\Exceptions\CustomException;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'only-admin'])->only('all');
    }

    /**
     * @param AdminLoginRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function login(AdminLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $admin = Admin::query()->where('username', $data['username'])->firstOrFail();
        $this->checkCredentials($admin, $data['password']);
        $token = $admin->getToken();
        return successResponse([
            'admin' => $admin,
            'token' => $token
        ]);
    }

    /**
     * @param $admin
     * @param $password
     * @throws CustomException
     */
    protected function checkCredentials($admin, $password)
    {
        if (!Hash::check($password, $admin->password)) {
            throw new CustomException('نام کاربری یا رمز عبور وارد شده معتبر نیست');
        }
    }

    /**
     * @return JsonResponse
     * @throws CustomException
     */
    public function all(): JsonResponse
    {
        $allAdmins = Admin::query()->get();
        $currentAdmin = currentUser();
        $filteredAdmins = $allAdmins->filter(function ($admin) use ($currentAdmin) {
            return $admin->id != $currentAdmin->getId();
        });
        return successResponse([
            'admins' => $filteredAdmins
        ]);
    }
}
