<?php

namespace App\Http\Controllers;


use App\Http\Interfaces\TeacherInterface;
use Illuminate\Http\Request;



class TeachersController extends Controller
{
    private $teacherInterface;
    /**
     * @var TeacherInterface
     */

    public function __construct(TeacherInterface $teacherInterface)
    {
        $this->teacherInterface = $teacherInterface;
    }


    public function addTeacher(Request $request){

        return $this->teacherInterface->addTeacher($request);
    }

    public function allTeachers(){

        return $this->teacherInterface->allTeachers();
    }

    public function specificTeacher(Request $request){

        return $this->teacherInterface->specificTeacher($request);
    }

    public function updateTeacher(Request $request){

        return $this->teacherInterface->updateTeacher($request);
    }


    public function deleteTeacher(Request $request){

        return $this->teacherInterface->deleteTeacher($request);
    }


}
