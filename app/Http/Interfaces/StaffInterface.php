<?php

namespace App\Http\Interfaces;


interface StaffInterface{

    public function addStaff($request);

    public function allStaff();

    public function specificStaff($request);

    public function updateStaff($request);

    public function deleteStaff($request);


}
