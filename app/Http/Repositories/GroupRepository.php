<?php

namespace App\Http\Repositories;


use App\Models\Role;
use App\Models\User;
use App\Http\Interfaces\TeacherInterface;
use App\Http\Traits\ApiDesignTrait;
use http\Env\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class TeacherRepository implements TeacherInterface {

    use ApiDesignTrait;


    private $userModel;
    private $roleModel;

    public function __construct(User $user,Role $role)
    {
        $this->userModel = $user;
        $this ->roleModel = $role;
    }

    public function addTeacher($request)
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
        return $this->ApiResponse(200,'teacher Was Created Successfully');
    }

    public function allTeachers()
    {
        //دى طريقة شغاله لكن بترجع اراى جوة اراى  لان بدنا نجيب الداتا من الرول الاول وليس من اليوزر
       // $staff = $this->roleModel::where('is_staff',1)->with('roleUser')->get();

        //********second way and it work also********
       /* $staff = $this->userModel::whereHas('roleName',function($query){
            return $query->where('is_staff',1);
        })->with('roleName')->get();*/

        //therd way more dynamic
        $is_teacher = 1;
        $teacher = $this->userModel::whereHas('roleName',function($query) use($is_teacher){
            return $query->where('is_teacher',$is_teacher);
        })->with('roleName')->get();

        return $this->ApiResponse(200,'done',null,$teacher);
    }

    public function updateTeacher($request)
    {
        $validation = Validator::make($request->all(),[

            'name' => 'required',
            'teacher_id' => 'required|exists:users,id',
            //first way for check and it work
            /*'email' => ['required',
                 'email',
                 Rule::unique('users')->ignore($request->teacher_id),
                ],*/
            'email' => 'required|email|unique:users,email,'.$request->teacher_id,
            'phone' => 'required',
            'password' => 'required|min:8|max:50',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validation->fails()){

            return $this->ApiResponse(422,'validation Error',$validation->errors());
        }

        $teacher = $this->userModel::where('id',$request->teacher_id)->first();

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
         ]);

        return $this->ApiResponse(200 , 'teacher updated Successfully');
    }

    public function deleteTeacher($request)
    {
        $validation = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id'
        ]);

        if($validation-> fails()){
            return $this->ApiResponse(422,'validation error',$validation->fails() );
        }

        $teacher = $this->userModel::whereHas('roleName', function ($query){
            return $query->where('is_teacher',1);
        })->find($request->teacher_id);

        if ($teacher){
            $teacher->delete();
            return $this->ApiResponse(200,'Teacher deleted successfully');
        }

            return $this->ApiResponse(422,'this user not teacher');
    }


    public function specificTeacher($request){

        $validation = validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id'
        ]);
            if($validation->fails()){
                return $this->ApiResponse(200,'validation error',$validation->errors());
            }
            $teacher = $this->userModel::whereHas('roleName',function ($query){
                return $query->where('is_teacher',1);
            })->find($request->teacher_id);

            if ($teacher){
                return $this->ApiResponse(200,'done',null,$teacher);
            }

            return $this->ApiResponse(422,'not found');
    }


}

?>

