<?php

namespace App\Http\Repositories;



use App\Http\Interfaces\StudentExamInterface;
use App\Models\Exam;
use App\Models\ExamTypes;
use App\Models\Question;
use App\Models\QuestionImage;
use App\Models\StudentExam;
use App\Models\StudentExamAnswer;
use App\Models\StudentGroup;
use App\Http\Traits\ApiDesignTrait;
use App\Models\SystemAnswer;
use Illuminate\Support\Facades\Validator;



class StudentExamRepository implements StudentExamInterface {

    use ApiDesignTrait;


    private $examModel;
    private $examTypesModel;
    private $studentGroupModel;
    private $questionModel;
    private $systemAnswerModel;
    private $questionImageModel;
    private $studentExamModel;
    private $studentExamAnswerModel;

    public function __construct(Exam $exam, ExamTypes $examTypes, StudentGroup $studentGroup, Question $question, SystemAnswer $systemAnswer,QuestionImage $questionImage,StudentExamAnswer $studentExamAnswer,StudentExam $studentExam)
    {
        $this->examModel = $exam;
        $this ->examTypesModel = $examTypes;
        $this->studentGroupModel = $studentGroup;
        $this->questionModel = $question;
        $this->systemAnswerModel = $systemAnswer;
        $this->questionImageModel = $questionImage;
        $this->studentExamModel = $studentExam;
        $this->studentExamAnswerModel = $studentExamAnswer;
    }

    public function newExams()
    {
        $userId = auth()->user()->id;

        $exams = $this->examModel::where('status',0)->whereHas('studentGroups',function($query) use($userId){
            return $query->where([ ['student_id',$userId], ['count','>=', 1] ]);
        })->get();

        return $this->ApiResponse(200,'done',null,$exams);
    }


    public function oldExams()
    {
        // TODO: Implement oldExams() method.
    }


    public function newStudentExam($request)
    {
        $validation = Validator::make($request->all(),[
            'exam_id' => 'required|exists:exams,id',
        ]);
        if ($validation->fails()){
            return $this->ApiResponse(422,'validation errors',$validation->errors());
        }
        $questionsCount = $this->examModel::select('count')->find($request->exam_id);

        $questions = $this->questionModel::where('exam_id',$request->exam_id)->inRandomOrder()
            ->limit($questionsCount->count)->with('questionImage')->get();

        return $this->ApiResponse(200,'done',null,$questions);
    }


    public function oldStudentExam($request)
    {
        // TODO: Implement oldStudentExam() method.
    }


    public function storeStudentExam($request)
    {
       //Validation need here

        $examData = $this->examModel::whereHas('examTypes',function ($query){
            return $query->where('is_mark',1);
        })->select('type_id','degree','count')->find($request->exam_id);

        $setStudentExam = $this->studentExamModel::create([
            'exam_id' => $request->exam_id,
            'student_id' => auth()->user()->id,
        ]);

        if ($examData){
            $questionDegree = $examData->degree / $examData->count;
            $totalDegree = 0;

            foreach ($request->questions as $question)
            {
                $getSystemAnswer = $this->systemAnswerModel::where('question_id',$question['question'])->first();
                if ($question['answer'] == $getSystemAnswer['answer'])
                {
                    $degree = $questionDegree;
                    $totalDegree += $degree;
                }else{
                    $degree = 0;
                }
                $this->studentExamAnswerModel::create([
                    'student_exam_id' => $setStudentExam->id,
                    'question_id' => $question['question'],
                    'degree' => $degree,
                ]);

                $setStudentExam->update([
                    'total_degree' => $totalDegree,

                ]);
            }

        }
//        else{
//            foreach ($request->questions as $question)
//            {
//
//            }
//        }

    }
}


