<?php

namespace App\Http\Interfaces;


interface StudentExamInterface{

    public function newExams();

    public  function oldExams();

    public function newStudentExam($request);

    public function oldStudentExam($request);

    public function storeStudentExam($request);
}
