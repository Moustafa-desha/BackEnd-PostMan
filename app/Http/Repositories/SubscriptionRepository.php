<?php

namespace App\Http\Repositories;



use App\Http\Interfaces\SubscriptionInterface;
use App\Models\StudentGroup;
use App\Models\User;
use App\Http\Traits\ApiDesignTrait;




class SubscriptionIRepository implements SubscriptionInterface {

    use ApiDesignTrait;


    private $userModel;
    private $studentGroup;

    public function __construct(User $user,StudentGroup  $studentGroup)
    {
        $this->userModel = $user;
        $this ->studentGroup = $studentGroup;
    }

    public function limitSub()
    {
       $studentLimit = $this->studentGroup::whereIn('count', [1,2])->with('groupUser')->get();

        return $this->ApiResponse(200,'done',null, $studentLimit);
    }

    public function ended()
    {
        $ended = $this->studentGroup::whereIn('count',[0])->with('groupUser')->get();

        return $this->ApiResponse(200, 'done',null, $ended);
    }
}



