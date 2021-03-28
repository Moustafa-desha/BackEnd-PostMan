<?php

namespace App\Http\Interfaces;


interface ExamInterface{

    public function allExams();

    public function examTypes();

    public function createdExam($request);

    public function updateExam($request);

    public function deleteExam($request);

    public function statusExam($request);

    /** َQuestions Section */

     public function allQuestions();

     public function creatQuestion($request);

     public function updateQuestion($request);

     public function deleteQuestion($request);




}
