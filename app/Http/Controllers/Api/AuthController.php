<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * @var UserRepository
     */
    protected $userRepositry;

    public function __construct()
    {
        $this->userRepositry = new UserRepository(app(User::class));
    }

    public function login(AuthRequest $request)
    {
        $auth = Auth::attempt(
            $request->only([
                'phone',
                'password',
            ])
        );



        if (!$auth) {

            return response()->json([
                'status' => false,
                'code' => 500,
                'msg' => __('Invalid credentials!'),
            ], 500);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['status' => true, 'code' => 200, 'msg' => __('Log in success'), 'data' => [
            'token' => $accessToken,
            'user' => UserResource::make(Auth::user()),
        ]]);
    }



    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepositry->save($request);

            if (isset($request->phone)) {
                $otp = $this->sendOTP($request->phone);

                $user->otp = $otp;
                $user->save();
            }

            DB::commit();
            Auth::login($user);

            $accessToken = Auth::user()->createToken('authToken')->accessToken;

            if ($user) {
                // return $this->returnData( 'user', UserResource::make($user), '');

                return response(['status' => true, 'code' => 200, 'msg' => __('User created succesfully'), 'data' => [
                    'token' => $accessToken,
                    'user' => UserResource::make(Auth::user()),
                ]]);
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return $this->returnError('Sorry! Failed in creating user');
        }
    }

    public function check(Request $request)
    {
        if ($this->checkOTP($request->phone, $request->code)) {
            return $this->returnSuccessMessage('success');
        } else {
            return $this->returnError('Sorry! code not correct');
        }
    }

    public function profile(Request $request)
    {
        return $this->returnData('user', UserResource::make(Auth::user()), 'successful');
    }

    public function password(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {

            $otp = $this->sendOTP($request->phone);

            $user->otp = $otp;
            $user->save();

            return $this->returnSuccessMessage('Code was sent');
        }

        return $this->returnError('Code not sent User not found');
    }

    public function sociallogin(Request $request)
    {

        $user = User::where([
            ['email', $request->email]
        ])->first();

        if ($user) {

            $accessToken = $user->createToken('authToken')->accessToken;

            //$user->token = $request->token;
            $user->save();
            Auth::login($user);


            return response(['status' => true, 'code' => 200, 'msg' => 'success', 'data' => [
                'token' => $accessToken,
                'user' => $user
            ]]);
        }


        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'image' => '',
            'password' => Hash::make('1234'),
        ]);



        Auth::login($user);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['status' => true, 'code' => 200, 'msg' => 'success', 'data' => [
            'token' => $accessToken,
            'user' => UserResource::make(Auth::user()),
        ]]);
    }


    public function changePassword(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if ($user) {

            User::find($user->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);

            return $this->returnSuccessMessage('Password has been changed');
        }

        return $this->returnError('Password not matched!');
    }


    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $old_pw = $request->old_password;

        //   if ($user->password == $old_pw)

        if (Hash::check($old_pw, $user->password)) {


            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return $this->returnSuccessMessage('Password has been changed');
        }

        return $this->returnError('Password not matched!');
    }

    public function updateProfile(Request $request)
    {
        try {
            DB::beginTransaction();
            // dd( Auth::user() );
            $user = Auth::user();
            if ($user) {
                // check unique email except this user
                if (isset($request->email)) {
                    $check = User::where('email', $request->email)
                        ->first();

                    if ($check) {

                        return $this->returnError('The email address is already used!');
                    }
                }

                if (isset($request->phone)) {
                    $check = User::where('phone', $request->phone)
                        ->first();

                    if ($check) {

                        return $this->returnError('The phone number is already used!');
                    }
                }





                $this->userRepositry->edit($request, $user);

                if ($request->has('image')) {
                    $image = $this->userRepositry->insertImage($request->image, $user, true);
                }

                DB::commit();

                return $this->returnData('user', new UserResource($user), 'User updated successfully');


            }




            // unset($user->image);

            return $this->returnError('Sorry! Failed to find user');
        } catch (\Exception $e) {
            DB::rollback();
            //return $e;

            return $this->returnError('Sorry! Failed in updating user');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return $this->returnSuccessMessage('Logged out succesfully!');
    }

    public function delete($id)
    {
        $user = User::find($id);

        $user->delete();



        return $this->returnSuccessMessage('Done!');
    }





    public function updatePhone(Request $request, $id)
    {
        $user = User::find($id);
        $user->phone = $request->phone;
        $user->save();

        $otp = $this->sendOTP($request->phone);

        $user->otp = $otp;
        $user->save();



        return $this->returnSuccessMessage('Code was sent!');
    }


    public function resendOTP(Request $request, $id)
    {
        $user = User::find($id);

        $otp = $this->sendOTP($user->phone);

        $user->otp = $otp;
        $user->save();



        return $this->returnSuccessMessage('Code was sent!');
    }

    public function sendOTP($phone)
    {
        //$otp = 5555;
         $otp = mt_rand(1000, 9999);


        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://82.212.81.40:8080/websmpp/websms', [
            'form_params' => [
                'user' => 'Wecan',
                'pass' => 'Suh12346',
                'sid' => 'Yalla Mazad',
                'mno' => $phone,
                'text' => "Your OTP is " . $otp . " for your account",
                'respformat' => 'json',
            ],
            'headers' => [
                'Authorization' => 'Bearer 2c1d0706b21b715ff1e5a480b8360d90',
                'Accept'     => 'application/json',
            ]
        ]);

        // dd( $response );

        return $otp;
    }



    public function checkOTP($phone, $otp)
    {
        $user = User::where('phone', $phone)->first();

        if ((string)$user->otp == (string)$otp) {
            $user->confirm = 1;
            $user->save();
            return true;

        }

        return false;
    }


    public function updateDeviceToken(Request $request)
    {
        $user = Auth::user();
        $user->device_token = $request->device_token;
        $user->save();

        return $this->returnData('user', UserResource::make(User::find(Auth::user()->id)), 'successful');
    }
}
