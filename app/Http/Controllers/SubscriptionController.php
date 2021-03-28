<?php

namespace App\Http\Controllers;


use App\Http\Interfaces\SubscriptionInterface;




class SubscriptionController extends Controller
{
    private $subscriptionInterface;
    /**
     * @var SubscriptionInterface
     */

    public function __construct(SubscriptionInterface $subscriptionInterface)
    {
        $this->subscriptionInterface = $subscriptionInterface;
    }



    public function limitSub(){

        return $this->subscriptionInterface->limitSub();
    }
    public function ended(){

        return $this->subscriptionInterface->ended();
    }
}
