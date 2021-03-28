<?php

namespace App\Http\Repositories;

use App\Http\Traits\ApiDesignTrait;
use App\Http\Interfaces\AuthInterface;
use App\Models\User;

class AuthRepository implements AuthInterface{

    use ApiDesignTrait;

    //ده علشان نستخدم الموديل بطريقه غير مباشره واكتر من مره بدون مشاكل
    private $userModel;
        public function __construct(User $user)
        {
            $this->userModel = $user;
        }

    public function login()
        {
                $credentials = request(['email', 'password']);

                if (! $token = auth()->attempt($credentials)) {
                    return $this->ApiResponse(422, 'Unauthorized');
                }

                return $this->respondWithToken($token);
        }

        protected function respondWithToken($token)
        {
            $userData = $this->userModel::find(auth()->user()->id);

            $data =[
                    'name' => $userData->name,
                    'email' => $userData->email,
                    'phone' => $userData->phone,
                    'role_id' => $userData->role_id,
                //الطريقة دى علشان نجيب الاسم بتاع الرول بتاعه وظيفته ايه عن طريق رييلشن اللى ف الموديل
                    'role' => auth()->user()->roleName->name,
                    'access_token' => $token,
            ];
            return $this->ApiResponse(200,'Done',null,$data);

        }

}

?>
