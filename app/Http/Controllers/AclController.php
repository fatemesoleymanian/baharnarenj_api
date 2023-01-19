<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Dotenv\Exception\ExceptionInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AclController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::query()->create($data);
        return successResponse();
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::query()->where('username', '=', $data['username'])->first();
        $this->checkPassword($user, $data['password']);
        $token = $user->createToken('userToken')->plainTextToken;
        return successResponse([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * @param $user
     * @param string $password
     * @throws CustomException
     */
    protected function checkPassword($user, string $password)
    {
        if (!Hash::check($password, $user->password)) {
            throw new CustomException('نام کاربری یا رمز عبور شما اشتباه است.');
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return successResponse(
            [
                'msg' => '.از حساب کاربری خود خارج شدید'
            ]
        );
    }

    public function forgetPassword(Request $request)
    {
        Validator::validate(
            $request->all(),
            ['username' => "required"],
            [
                'email.required' => 'لطفا ایمیل یا شماره تلفن خود را وارد کنید!',
            ]
        );

        $username = $request->username;
//        $otp = rand(10000, 1000000);
        $otp = '11111111';
        $user = User::where('mobile', $request->username)->orWhere('email', $request->username)->first();

        if (!$user) return response()->json([
            'message' => 'این ایمیل یا شماره تلفن درسیستم وجود ندارد!'
        ],200);

        // phone number
        if (str_starts_with($username, '09')) {

//            $kavenegar = new KavenegarApi(config('kavenegar.apikey'));
//            $kavenegar->VerifyLookup(
//                $username,
//                $otp,
//                null,
//                null,
//                'verify',
//                $type = null
//            );

            cache()->remember($username, 250, function () use ($otp) {
                return $otp;
            });
            return response()->json(['message' => 'رمز یکبار مصرف به شما پیامک شد.'],201);
        }
        //EMAIL
        else {

            try {
                //email with job and queue
                //                $details['view'] = 'mail.conf_code';
                //                $details['conf_code'] = $conf_code;
                //                $details['key'] = $request->key;
                //                dispatch(new EmailJob($details));

                //no queue
//                Mail::send(
//                    'mail.forget_password',
//                    ['code' => $otp],
//                    function ($message) use ($request) {
//                        $message->to($request->username);
//                        $message->subject('ساخت ایران');
//                    }
//                );
                cache()->remember($username, 250, function () use ($otp) {
                    return $otp;
                });
                return response()->json(['message' => 'رمز یکبار مصرف به شما ایمیل شد.'],201);
            } catch (ExceptionInterface $e) {
                return response()->json([
                    'email' => $e
                ], 200);
            }
        }
    }

    public function resetPassword(Request $request)
    {
        Validator::validate(
            $request->all(),
            ['otp' => "required"],
            ['otp.required' => 'لطفا رمز یکبار مصرف را وارد کنید!']
        );

        $username = $request->username;
        $otp = cache()->get($username);
        if ($otp != $request->otp) return response()->json([
            'message' => 'رمز وارد شده صحیح نیست!'
        ], 200);

        $user = User::where('mobile', $request->username)->orWhere('email', $request->username)->update([
            'password' => Hash::make($otp)
        ]);

        return response()->json([
         'message' => 'رمز عبور شما تغییر کرد ، لطفا وارد حساب کاربری خود شوید.'
        ], 201);
    }
}
