<?php

namespace App\Http\Interfaces;


interface TeacherInterface{

    public function addTeacher($request);

    public function allTeachers();

    public function specificTeacher($request);

    public function updateTeacher($request);

    public function deleteTeacher($request);


}
