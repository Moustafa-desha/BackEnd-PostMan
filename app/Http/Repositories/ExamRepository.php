<?php

namespace App\Http\Repositories;



use App\Http\Interfaces\ExamInterface;
use App\Models\Exam;
use App\Models\ExamTypes;
use App\Models\Question;
use App\Models\QuestionImage;
use App\Models\StudentGroup;
use App\Http\Traits\ApiDesignTrait;
use App\Models\SystemAnswer;
use Illuminate\Support\Facades\Validator;



class ExamRepository implements ExamInterface {

    use ApiDesignTrait;


    private $examModel;
    private $examTypesModel;
    private $studentGroupModel;
    private $questionModel;
    private $systemAnswerModel;
    private $questionImageModel;

    public function __construct(Exam $exam, ExamTypes $examTypes, StudentGroup $studentGroup, Question $question, SystemAnswer $systemAnswer,QuestionImage $questionImage)
    {
        $this->examModel = $exam;
        $this ->examTypesModel = $examTypes;
        $this->studentGroupModel = $studentGroup;
        $this->questionModel = $question;
        $this->systemAnswerModel = $systemAnswer;
        $this->questionImageModel = $questionImage;
    }



    public function allExams()
    {
       $userRole = Auth()->user()->roleName->name;
       $userId = Auth()->user()->id;

       if ($userRole == 'Teacher'){
           $exams = $this ->examModel::where('teacher_id',$userId)->get();

       }elseif ($userRole == 'Student')
       {
           $groups = $this->studentGroupModel::where([['student_id',$userId],['count','>',0]])->get();

           $groupsIds = [];
           foreach ($groups as $group){
               $groupsIds [] = $group->group_id;
           }
           $exams = $this->examModel::whereIn('group_id',$groupsIds)->get();
       }

        return $this->ApiResponse(200,'done',null,$exams);
    }

    public function examTypes()
    {
        $examstype = $this->examTypesModel::get();
        return $this->ApiResponse(200,'done',Null,$examstype);
    }

    public function createdExam($request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'time' => 'required',
            'degree' => 'required',
            'count' => 'required',
            'type_id' => 'required|exists:exam_types,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422,'validation error',$validation->errors());
        }
        $addExam = $this->examModel::create([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
            'count' => $request->count,
            'type_id' => $request->type_id,
            'group_id' => $request->group_id,
            'teacher_id' => auth()->user()->id,
        ]);
        return $this->ApiResponse(200,'Exam created successfully');
    }

    public function updateExam($request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'time' => 'required',
            'degree' => 'required',
            'count' => 'required',
            'group_id' => 'required|exists:groups,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        if($validation->fails()){
            return $this->ApiResponse(422,'validation error',$validation->errors());
        }
        $this->examModel::find($request->exam_id)->update([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
            'count' => $request->count,
            'group_id' => $request->group_id,
        ]);
        return $this->ApiResponse(200,'Exam Updated');
    }

    public function deleteExam($request)
    {
        $validation = Validator::make($request->all(),[
            'exam_id' => 'required|exists:exams,id',
        ]);
        if ($validation->fails()){
            return $this->ApiResponse(422,'validation error', $validation->errors());
        }
        $this->examModel::where('id',$request->exam_id)->delete();
        return $this->ApiResponse(200,'exam deleted successful');
    }

    public function statusExam($request)
    {
        $validation = Validator::make($request->all(),[
            'exam_id' => 'required|exists:exams,id',
            'status' => 'required',
        ]);
        if ($validation->failed()){
            return $this->ApiResponse(200,'validation error',$validation->errors());
        }
        $exam = $this->examModel::where('id',$request->exam_id)->first();

        $exam->update([
            'status' => $request->status,
        ]);
        if ($request->status == 0){
            return $this->ApiResponse(200,'Exam is Close');
        }elseif ($request->status == 1){
            return $this->ApiResponse(200,'Exam is Open');
        }


    }


    /** Question Section */

    public function allQuestions()
    {
        // TODO: Implement alQuestions() method.
    }

    public function creatQuestion($request)
    {
        $validation = Validator::make($request->all(),[
            'title' => 'required|min:3',
            'exam_id' => 'required|exists:exams,id',
        ]);
        if ($validation->fails())
        {
            return $this->ApiResponse(422,'validation error',$validation->errors());
        }
        $question = $this->questionModel::create([
            'title' => $request->title,
            'exam_id' => $request->exam_id,
        ]);
        if ($request->has('answer')){
            $this->systemAnswerModel::create([
                'question_id' => $request->question_id,
                'answer' => $request->answer,
            ]);
        }
        if ($request->has('image')){
            $this->questionImageModel::create([
                'image' =>'test.png',
                'question_id' => $request->question_id,
            ]);
        }
    }

    public function updateQuestion($request)
    {
        // TODO: Implement updateQuestion() method.
    }

    public function deleteQuestion($request)
    {
        // TODO: Implement deleteQuestion() method.
    }
}


