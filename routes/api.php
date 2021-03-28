<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::POST('login', [AuthController::class, 'login']);
});

Route::group(['prefix' => 'admin', 'middleware'=>['jwt.token', 'roles:Admin.']], function (){
    Route::POST('add/staff', [StaffController::class,'addStaff']);
    Route::POST('update/staff', [StaffController::class,'updateStaff']);
    Route::get('all/staff', [StaffController::class,'allStaff']);
    Route::POST('delete/staff', [StaffController::class,'deleteStaff']);
    Route::get('specific/staff',[StaffController::class,'specificStaff']);
});

// this 'roles:Admin.Support.Secretary' we received from Roles Middleware
Route::group(['prefix' => 'dashboard', 'middleware'=>['jwt.token', 'roles:Admin.Support.Secretary']], function (){
    //Teacher Route
    Route::POST('add/teacher', [TeachersController::class,'addTeacher']);
    Route::POST('update/teacher', [TeachersController::class,'updateTeacher']);
    Route::get('all/teacher', [TeachersController::class,'allTeachers']);
    Route::POST('delete/teacher', [TeachersController::class,'deleteTeacher']);
    Route::get('specific/teacher',[TeachersController::class,'specificTeacher']);

    //Group Route
    Route::POST('add/group', [GroupController::class,'addGroup']);
    Route::POST('update/group', [GroupController::class,'updateGroup']);
    Route::get('all/group', [GroupController::class,'allGroups']);
    Route::POST('delete/group', [GroupController::class,'deleteGroup']);
    Route::get('specific/group',[GroupController::class,'specificGroup']);

    /**Student Route**/
    Route::Post('add/student', [StudentController::class,'addStudent']);
    Route::get('all/student', [StudentController::class,'allStudents']);
    Route::POST('update/student', [StudentController::class,'updateStudent']);


     /**LimitSub Route**/
    Route::get('all/limit',[SubscriptionController::class,'limitSub']);
    Route::get('ended/limit',[SubscriptionController::class,'ended']);

});


/**
 * EndUser Route
 */
Route::group(['prefix' => 'enduser', 'middleware'=>['jwt.token', 'roles:Student.Teacher']], function (){
    Route::get('groups', [EndUserController::class,'userGroups']);



    /** Exam Section  */
    Route::get('all/exams', [ExamController::class,'allExams']);
    Route::get('type/exams', [ExamController::class,'examTypes']);
    Route::POST('create/exams', [ExamController::class,'createdExam']);
    Route::POST('update/exams', [ExamController::class,'updateExam']);
    Route::POST('delete/exams', [ExamController::class,'deleteExam']);
    Route::POST('status/exams', [ExamController::class,'statusExam']);

    /** Student Exam Section */

    Route::get('new/exams',[StudentExamController::class,'newExams']);
    Route::POST('new/student/exams',[StudentExamController::class,'newStudentExam']);
    Route::POST('store/student/exams',[StudentExamController::class,'storeStudentExam']);





});
