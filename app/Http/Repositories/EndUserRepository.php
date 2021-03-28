<?php

namespace App\Http\Repositories;


use App\Http\Interfaces\EndUserInterface;
use App\Models\Group;
use App\Models\Role;
use App\Models\StudentGroup;
use App\Models\User;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class EndUserRepository implements EndUserInterface {

    use ApiDesignTrait;


    private $userModel;
    private $groupModel;
    private $studentGroupModel;

    public function __construct(User $user,Group $group, StudentGroup $studentGroup)
    {
        $this->userModel = $user;
        $this ->groupModel = $group;
        $this->studentGroupModel = $studentGroup;
    }


    public function userGroups()
    {
        $userId = auth()->user()->id;
        $roleName = auth()->user()->roleName->name;

        if($roleName == 'Teacher'){
            return $this->groupModel::where('teacher_id',$userId)->withCount('groupStudents')->get();

        }elseif ($roleName == 'Student')

           return $this->groupModel::whereHas('groupStudents',function ($query) use($userId){
                $query->where([ ['student_id',$userId], ['count' ,'>=', 1] ]);
            })->withCount('groupStudents')->get();

    }
}


