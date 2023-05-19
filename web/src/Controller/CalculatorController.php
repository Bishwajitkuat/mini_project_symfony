<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    #[Route('/app_calculator', name: 'app_calculator')]
    public function index(Request $request)

    {
         $firstNumber =intval($request->request->get('firstNumber'));
         $secondNumber =intval($request->request->get('secondNumber'));
         $operation=  $request->request->get('operation');

         $result = null;

         switch ($operation) {
            case '+':
                $result = $firstNumber + $secondNumber;
                break;
            case '-':
                $result = $firstNumber - $secondNumber;
                break;
            case '*':
                $result = $firstNumber * $secondNumber;
                break;
            case '/':
                $result = $firstNumber / $secondNumber;
                break;
         }

        return $this->render('calculator/index.html.twig',['result'=>$result]);
    }
}
