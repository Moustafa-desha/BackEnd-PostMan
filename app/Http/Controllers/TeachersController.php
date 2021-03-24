<?php

namespace App\Http\Controllers;


use App\Http\Interfaces\StaffInterface;
use Illuminate\Http\Request;



class StaffController extends Controller
{
    private $staffInterface;
    /**
     * @var StaffInterface
     */

    public function __construct(StaffInterface $staffInterface)
    {
        $this->staffInterface = $staffInterface;
    }


    public function addStaff(Request $request){

        return $this->staffInterface->addStaff($request);
    }

    public function allStaff(){

        return $this->staffInterface->allStaff();
    }

    public function specificStaff(Request $request){

        return $this->staffInterface->specificStaff($request);
    }

    public function updateStaff(Request $request){

        return $this->staffInterface->updateStaff($request);
    }


    public function deleteStaff(Request $request){

        return $this->staffInterface->deleteStaff($request);
    }


}
