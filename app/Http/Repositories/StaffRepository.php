<?php

namespace App\Http\Repositories;

use App\Models\Role;
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
    private $roleModel;

    public function __construct(User $user,Role $role)
    {
        $this->userModel = $user;
        $this ->roleModel = $role;
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
        //دى طريقة شغاله لكن بترجع اراى جوة اراى  لان بدنا نجيب الداتا من الرول الاول وليس من اليوزر
       // $staff = $this->roleModel::where('is_staff',1)->with('roleUser')->get();

        //********second way and it work also********
       /* $staff = $this->userModel::whereHas('roleName',function($query){
            return $query->where('is_staff',1);
        })->with('roleName')->get();*/

        //therd way
        $is_staff = 1;
        $staff = $this->userModel::whereHas('roleName',function($query) use($is_staff){
            return $query->where('is_staff',$is_staff);
        })->with('roleName')->get();

        return $this->ApiResponse(200,'done',null,$staff);
    }

    public function updateStaff($request)
    {
        $validation = Validator::make($request->all(),[

            'name' => 'required',
            'staff_id' => 'required|exists:users,id',
            //first way for check and it work
            /*'email' => ['required',
                 'email',
                 Rule::unique('users')->ignore($request->staff_id),
                ],*/
            'email' => 'required|email|unique:users,email,'.$request->staff_id,
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
        $validation = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id'
        ]);

        if($validation-> fails()){
            return $this->ApiResponse(422,'validation error',$validation->fails() );
        }

        $staff = $this->userModel::whereHas('roleName', function ($query){
            return $query->where('is_staff',1);
        })->find($request->staff_id);

        if ($staff){
            $staff->delete();
            return $this->ApiResponse(200,'staff deleted successfully');
        }

            return $this->ApiResponse(422,'this user not staff');
    }


    public function specificStaff($request){

        $validation = validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id'
        ]);
            if($validation->fails()){
                return $this->ApiResponse(200,'validation error',$validation->errors());
            }
            $staff = $this->userModel::whereHas('roleName',function ($query){
                return $query->where('is_staff',1);
            })->find($request->staff_id);

            if ($staff){
                return $this->ApiResponse(200,'done',null,$staff);
            }

            return $this->ApiResponse(422,'not found');
    }
}

?>

