<?php

namespace App\Http\Interfaces;


interface SubscriptionInterface{

    public function limitSub();

    public function ended();
}
