<?php

namespace App\Http\Controllers;


use App\Http\Interfaces\EndUserInterface;
use App\Http\Interfaces\SessionInterface;
use Illuminate\Http\Request;



class SessionController extends Controller
{
    private $sessionInterface;
    /**
     * @var SessionInterface
     */

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->sessionInterface = $sessionInterface;
    }


   public function allsession()
   {
       return $this->sessionInterface->allsession();
   }

   public function addsession($request)
   {
       return $this->sessionInterface->addsession($request);
   }

   public function deletesesson($request)
   {
        return $this->sessionInterface->deletesesson($request);
   }
}
