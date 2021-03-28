<?php

namespace App\Http\Repositories;



use App\Http\Interfaces\SessionInterface;
use App\Models\GroupSession;
use App\Models\StudentGroup;
use App\Http\Traits\ApiDesignTrait;
use Facade\FlareClient\Api;
use Illuminate\Support\Facades\Validator;



class SessionRepository implements SessionInterface {

    use ApiDesignTrait;


    private $groupSessionModel;
    private $studentGroupModel;

    public function __construct(GroupSession $groupSession, StudentGroup $studentGroup)
    {

        $this ->groupSessionModel = $groupSession;
        $this->studentGroupModel = $studentGroup;
    }


    public function allsession()
    {
         $session = $this->groupSessionModel::where('name','group_id')->get();

         return $this->ApiResponse(200,'done',null,$session);
    }


    public function addsession($request)
    {
        $validator = Validator::make($request->all(),[
        'name' => 'required|min:3|max:100',
        'from' => 'required|date_format:H:i',
        'to' =>   'required|date_format:H:i|after:from',
        'link' => 'required|url',
        'group_id' => 'required|exists:groups,id',
        ]);

        if ($validator->fails()) {
            return $this->ApiResponse(422,'Validaton error' , $validator->errors());
         }

        $this->groupSessionModel::create([
            'name' => $request->name,
            'from' => $request->from,
            'to'   => $request->to,
            'link' => $request->link,
            'group_id'=> $request->group_id,
         ]);
        $this->studentGroupModel::where('group_id',$request->group_id)->where('count', '>', 0)->decrement('account');

        return $this->ApiResponse(200,'Added successfully');
    }

    public function deletesesson($request)
    {
       //
    }
}


