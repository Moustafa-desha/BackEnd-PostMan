<?php

namespace App\Http\Repositories;


use App\Models\Role;
use App\Models\StudentGroup;
use App\Models\User;
use App\Http\Interfaces\StudentInterface;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class StudentRepository implements StudentInterface {

    use ApiDesignTrait;


    private $userModel;
    private $roleModel;
    private $studentGroupModel;

    public function __construct(User $user,Role $role, studentGroup $studentGroup)
    {
        $this->userModel = $user;
        $this ->roleModel = $role;
        $this->studentGroupModel = $studentGroup;
    }


    public function addStudent($request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|min:8|max:50',
            //هنا استخدمنا طريقة الرول منفصل علشان نعمل فالديت ونستدعيه هنا باسم الملف
            //'groups' => ['required', 'array', new ValidGroupId()],
            'groups.*' => 'required|min:5',
        ]);

        if ($validation->fails()) {

            return $this->ApiResponse(422, 'validation Error', $validation->errors());
        }


        /**
         * this way with string from postman and we use explode to convert it to array
         */
            /*
              $groups = $request->groups;
                for ($i = 0 ; $i <= count($groups) ; $i++){

                  //for ($j = $i+1 ; $j <= count($groups)-1 ; $j++)
                    for ($j = $i+1 ; $j < count($groups) ; $j++){

                        if ($groups[$i][0] == $groups[$j][0]){
                            return $this->ApiResponse(422,'validation errors' ,'this Group already exists');
                        }
                    }
                }
                */

        /**
         * this way use string from postman and we convert to array by explode
         */

        $array = [];
        foreach ($request->groups as $group) {
            // explode تستخدم لتحويل الاسترينج وتقطيعه منفصل لاراى
            $requestGroup = explode(',', $group);
            if (count($requestGroup) != 3) {
                return $this->ApiResponse(422, 'validation errors', 'Reformat Group not right');
            }

           //  هنا بنشيك ان الجروب مش موجود قبل كدا علشان ميسجلش ف اكتر من جروب الطريقة الاولى نستيد اراى
            if (in_array($requestGroup[0], $array)) {
                return $this->ApiResponse(422, 'validation errors', 'this Group already exists');
            }
            $array[] = $requestGroup[0];

               //  هنا بنشيك ان الجروب مش موجود قبل كدا علشان ميسحلش ف اكتر من جروب الطريقة الثانيه
            /*
            if($this->studentGroupModel::where([['group_id', $requestGroup[0]], ['student_id',$student->id] ])->first()){
                return $this->ApiResponse(422, 'validation erorr', 'this Group already exists');
            };*/


            $studentRole = $this->roleModel::where([['is_teacher', 0], ['is_staff', 0]])->first();

            $student = $this->userModel::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_id' => $studentRole->id,
            ]);


            $this->studentGroupModel::create([
                'student_id' => $student->id,
                'group_id' => $requestGroup[0],
                'count' => $requestGroup[1],
                'price' => $requestGroup[2],
            ]);


            return $this->ApiResponse(200, 'Student Created Successfully');
        }
    }


    public function allStudents()
    {
        $student = $this->userModel::whereHas('roleName', function ($query){
            return $query->where([['is_teacher',0],['is_staff',0]]);
        })->withCount('studentGroup')->get();

        return $this->ApiResponse(200,'done',null,$student);
    }

    public function specificStudent($request)
    {
        // TODO: Implement specificStudent() method.
    }

    public function updateStudent($request)
    {


//        if($request->has('groups')) {
//            $groups = $request->groups;
//        }

//            for ($i = 0; $i < count($groups); $i++) {
//
//                $studentGroup = $this->studentGroupModel::where([['student_id', $request->student_id], ['group_id', $groups[$i][0]]]);
//            }
//        }




        $validation = Validator::make($request->all(),[

            'name' => 'required',
            /*'email' => ['required',
                 'email',
                 Rule::unique('users')->ignore($request->teacher_id),
                ],*/
            'email' => 'required|email|unique:users,email,'.$request->student_id,
            'phone' => 'required',
            'password' => 'required|min:8|max:50',
            'student_id' => 'required|exists:users,id',
        ]);

        if ($validation->fails()){

            return $this->ApiResponse(422,'validation Error',$validation->errors());
        }

        $requestGroups = [];

        $this->userModel::find($request->student_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        if($request->has('groups')){
            foreach ($request->groups as $group){
                $requestGroup = explode(',', $group);
                $requestGroups[] = $requestGroup[0];

                $this->studentGroupModel::create([
                    'student_id' => $request->student_id,
                    'group_id' => $requestGroup[0],
                    'count' => $requestGroup[1],
                    'price' => $requestGroup[2],
                ]);
            }
        }



        /**
         * this way will take array from postman
         */

//        if($request->has('groups')){
//
//            $groups = $request->groups;
//
//            for ($i = 0 ; $i < count($groups); $i++)
//            {
//                $studentGroup = $this->studentGroupModel::where
//                ([ ['student_id', $request->student_id], ['group_id', $groups[$i][0] ] ])->first();
//                if ($studentGroup){
//                    $studentGroup ->update([
//                        'count' => $groups[$i][1],
//                        'price' => $groups[$i][2],
//                    ]);
//                }
//                else
//                {
//                    $this->studentGroupModel::create([
//                        'student_id' => $request->student_id,
//                        'group_id' => $groups[$i][0],
//                        'count' => $groups[$i][1],
//                        'price' => $groups[$i][2],
//                    ]);
//                }
//            }
//
//            return $this->ApiResponse(200, 'student Updated');
//        }

        /**
         هنا هنخذف الجروب اللى مش هيتعدل عليه ومش هيبعته ف الريكوست بس موجود ف الداتابيز *
         */

//        $oldGroups = $this->studentGroupModel::where('student_id',$request->student_id)->get('group_id')->toArray();
//        $dataBaseGroups = array_column($oldGroups,'group_id');
//
//        $requestGroups = [];
//        foreach ($request->groups as $group){
//            $requestGroup = explode(',' , $group);
//            $requestGroups[] = $requestGroup[0];
//        }
//
//        $result = array_diff($dataBaseGroups , $requestGroups );
//
//        foreach($result as $item){
//             $this->studentGroupModel::where([ ['group_id',$item],['student_id',$request->student_id] ])->first()->delete();
//        }

        $this->studentGroupModel::whereNotIN('group_id',$requestGroups)
         ->where('student_id',$request->student_id)->delete();



        return $this->ApiResponse(200, 'student Updated');
    }

    public function deleteStudent($request)
    {
        // TODO: Implement deleteStudent() method.
    }

    public function updateStudentGroup($request)
    {
        // TODO: Implement updateStudentGroup() method.
    }

    public function deleteStudentGroup($request)
    {
        // TODO: Implement deleteStudentGroup() method.
    }
}



