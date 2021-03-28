<?php

namespace App\Http\Controllers;


use App\Http\Interfaces\EndUserInterface;
use Illuminate\Http\Request;



class EndUserController extends Controller
{
    private $endUserInterface;
    /**
     * @var EndUserInterface
     */

    public function __construct(EndUserInterface $endUserInterface)
    {
        $this->endUserInterface = $endUserInterface;
    }


   public function userGroups()
   {
       return $this->endUserInterface->userGroups();
   }

}
