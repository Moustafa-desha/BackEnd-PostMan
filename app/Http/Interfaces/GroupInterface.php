<?php

namespace App\Http\Interfaces;


interface GroupInterface{

    public function addGroup($request);

    public function allGroups();

    public function specificGroup($request);

    public function updateGroup($request);

    public function deleteGroup($request);


}
