<?php

namespace App\Http\Repositories;


use App\Models\Group;
use App\Http\Interfaces\GroupInterface;
use App\Http\Traits\ApiDesignTrait;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class GroupRepository implements GroupInterface {

    use ApiDesignTrait;


    private $groupModel;

    public function __construct(Group $group)
    {
        $this->groupModel = $group;

    }

    public function addGroup($request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required|min:3|max:50',
            'body' => 'required',
            'image' => 'required', //|image|mimes:jpg,bmp,png,
            'teacher_id' => 'required|exists:users,id',
            ]);

        if ($validation->fails()) {

            return $this->ApiResponse(422,'validation Error',$validation->errors());
        }

        $this->groupModel::create([

            'name' => $request->name,
            'body' => $request->body,
            'image' => $request->image,
            'teacher_id' => $request->teacher_id,
            'created_by' => Auth()->user()->id,
        ]);
        return $this->ApiResponse(200,'Group Was Created Successfully');
    }

    public function allGroups()
    {

        $group = $this->groupModel::get();

        return $this->ApiResponse(200,'done',null,$group);
    }

    public function updateGroup($request)
    {
        $validation = Validator::make($request->all(),[

            'name' => 'required|min:3|max:50',
            'body' => 'required',
            'image' => 'required', //|image|mimes:jpg,bmp,png,
            'teacher_id' => 'required|exists:users,id',
        ]);

        if ($validation->fails()){

            return $this->ApiResponse(422,'validation Error',$validation->errors());
        }

        $group = $this->groupModel::find($request->teacher_id);

        if ($group){

            $group->update([
                'name' => $request->name,
                'body' => $request->body,
                'image' => $request->image,
                'teacher_id' => $request->teacher_id,
             ]);
        return $this->ApiResponse(200 , 'Group updated Successfully');
        }
        return $this->ApiResponse(404,'not found');
    }

    public function deleteGroup($request)
    {
        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation-> fails()){
            return $this->ApiResponse(422,'validation error',$validation->fails() );
        }

        $group = $this->groupModel::find($request->group_id);

        if ($group){
            $group->delete();
            return $this->ApiResponse(200,'Group deleted successfully');
        }

            return $this->ApiResponse(422,'this is not found');
    }


    public function specificGroup($request){

        $validation = validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id'
        ]);
            if($validation->fails()){
                return $this->ApiResponse(200,'validation error',$validation->errors());
            }
            $group = $this->groupModel::find($request->group_id);

            if ($group){
                return $this->ApiResponse(200,'done',null,$group);
            }

            return $this->ApiResponse(422,'not found');
    }


}



