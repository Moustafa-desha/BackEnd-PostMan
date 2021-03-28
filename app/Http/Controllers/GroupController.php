<?php

namespace App\Http\Controllers;


use App\Http\Interfaces\GroupInterface;
use Illuminate\Http\Request;



class GroupController extends Controller
{
    private $groupInterface;
    /**
     * @var GroupInterface
     */

    public function __construct(GroupInterface $groupInterface)
    {
        $this->groupInterface = $groupInterface;
    }


    public function addGroup(Request $request){

        return $this->groupInterface->addGroup($request);
    }

    public function allGroups(){

        return $this->groupInterface->allGroups();
    }

    public function specificGroup(Request $request){

        return $this->groupInterface->specificGroup($request);
    }

    public function updateGroup(Request $request){

        return $this->groupInterface->updateGroup($request);
    }


    public function deleteGroup(Request $request){

        return $this->groupInterface->deleteGroup($request);
    }


}
