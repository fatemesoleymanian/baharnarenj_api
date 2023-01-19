<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\ChangeUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('only-admin')->only(['index', 'destroy']);
//    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::query()->get();
        return successResponse([
            'users' => $users
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return successResponse([
            'user' => $user
        ]);
    }

    /**
     * @param UpdateUserRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(UpdateUserRequest $request,): JsonResponse
    {
        $data = filterData($request->validated());
        $user = currentUser();
//        if (exists($data['email'])) {
//            $this->validateUserCredentials('email', $data['email']);
//        }
//        if (exists($data['mobile'])) {
//            $this->validateUserCredentials('mobile', $data['mobile']);
//        }
//        if (exists($data['username'])) {
//            $this->validateUserCredentials('username', $data['username']);
//        }
        $user->update($data);
        return successResponse([
            'user' => $user->first(),
        ]);
    }

    /**
     * @param string $column
     * @param string $value
     * @throws CustomException
     */
    protected function validateUserCredentials(string $column, string $value)
    {
        $anotherUser = User::query()->where($column, $value)->first();
        if (exists($anotherUser)) {
            throw new CustomException('این مورد متعلق به کاربر دیگریست.');
        }

    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return successResponse();
    }

    /**
     * @param ChangeUserPasswordRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function changePass(ChangeUserPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->checkOldPassword($data['old_password']);
        $this->confirmNewPassword($data['new_password'], $data['confirm_password']);
        $user = currentUser();
        $data['new_password'] = Hash::make($data['new_password']);
        $user->update([
            'password' => $data['new_password']
        ]);
        return successResponse();
    }

    /**
     * @param string $oldPass
     * @throws CustomException
     */
    protected function checkOldPassword(string $oldPass)
    {
        $user = currentUser();
        if (!Hash::check($oldPass, $user->getPassword())) throw new CustomException('رمز عبور وارد شده صحیح نمیباشد.');
    }

    /**
     * @param string $newPass
     * @param string $confirmPass
     * @throws CustomException
     */
    protected function confirmNewPassword(string $newPass, string $confirmPass)
    {
        if ($newPass != $confirmPass) throw new CustomException('رمز عبور وارد شده با تکرار آن تطابق ندارد.');
    }
}
