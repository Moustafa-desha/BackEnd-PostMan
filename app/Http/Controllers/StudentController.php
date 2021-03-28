<?php

namespace App\Http\Controllers;


use App\Http\Interfaces\StudentInterface;
use Illuminate\Http\Request;



class StudentController extends Controller
{
    private $studentInterface;

    /**
     * @var StudentInterface
     */

    public function __construct(StudentInterface $studentInterface)
    {
       $this->studentInterface = $studentInterface;
    }

    public function addStudent(Request $request){

        return  $this->studentInterface->addStudent($request);
    }

    public function allStudents(){

        return  $this->studentInterface->allStudents();
    }

    public function updateStudent(Request $request){
        return $this->studentInterface->updateStudent($request);
    }
}
