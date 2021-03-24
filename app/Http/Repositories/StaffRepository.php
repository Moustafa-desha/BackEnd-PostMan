<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Http\Interfaces\StaffInterface;
use App\Http\Traits\ApiDesignTrait;
use http\Env\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class StaffRepository implements StaffInterface {

    use ApiDesignTrait;


    private $userModel;

    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    public function addStaff($request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|min:8|max:50',
            'role_id' => 'required|exists:roles,id',
            ]);

        if ($validation->fails()) {

            return $this->ApiResponse(422,'validation Error',$validation->errors());
        }

        $this->userModel::create([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);
        return $this->ApiResponse(200,'User Was Created Successfully');
    }

    public function allStaff()
    {
        // TODO: Implement allStaff() method.
    }

    public function updateStaff($request)
    {
        $validation = Validator::make($request->all(),[

            'name' => 'required',
            'staff_id' => 'required|exists:users,id',
            'email' => ['required',
                'email',
                 Rule::unique('users')->ignore($request->staff_id),
                ],
            'phone' => 'required',
            'password' => 'required|min:8|max:50',
            'role_id' => 'required|exists:roles,id',

        ]);
        if ($validation->fails()){

            return $this->ApiResponse(422,'validation Error',$validation->errors());
        }

        $user = $this->userModel::where('id',$request->staff_id)->first();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
         ]);

        return $this->ApiResponse(200 , 'User Edited Successfully');
    }

    public function deleteStaff($request)
    {
        // TODO: Implement deleteStaff() method.
    }
}

?>
