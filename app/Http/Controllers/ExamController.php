<?php

namespace App\Http\Controllers;



use App\Http\Interfaces\ExamInterface;
use Illuminate\Http\Request;



class ExamController extends Controller
{
    private $examInterface;
    /**
     * @var ExamInterface
     */

    public function __construct(ExamInterface $examInterface)
    {
        $this->examInterface = $examInterface;
    }


   public function allExams()
   {
       return $this->examInterface->allExams();
   }

   public function examTypes()
   {
       return $this->examInterface->examTypes();
   }

   public function createdExam(Request $request)
   {
       return $this->examInterface->createdExam($request);
   }

   public function updateExam(Request $request)
   {
       return $this->examInterface->updateExam($request);
   }

   public function deleteExam(Request $request)
   {
       return $this->examInterface->deleteExam($request);
   }

   public function statusExam(Request $request)
   {
       return $this->examInterface->statusExam($request);
   }

   /** Question Section */
    public function x()
    {
        return $this->examInterface->allQuestions();
    }

    public function creatQuestion(Request $request)
    {
        return $this->examInterface->creatQuestion($request);
    }

    public function updateQuestion(Request $request)
    {
        return $this->examInterface->updateQuestion($request);
    }

    public function deleteQuestion(Request $request)
    {
        return $this->examInterface->deleteQuestion($request);
    }
}
