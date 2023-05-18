<?php

namespace App\Controller;

use App\Entity\Todos;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends AbstractController
{   
    #[Route('/todos', name: 'app_todo', methods:['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Todos::class)->findAll();
        $data = [];
        foreach($products as $todo){
            $data[] = [
                'id'=>$todo->getId(),
                'title'=>$todo->getTitle(),
                'description'=>$todo->getDescription(),
                'created_at'=>$todo->getEntryTime(),
            ];
        }
        return $this->render('todo/index.html.twig', [
            'data'=>$data
        ]);
    }

    #[Route('/todos', name: 'createTodo', methods:['POST'])]
    public function add(Request $request, ManagerRegistry $doctrine)
    {
       $em = $doctrine->getManager();
       $newPost = new Todos();
       $newPost->setTitle($request->request->get('title'));
       $newPost->setDescription($request->request->get('description'));
       $newPost->setEntryTime(date_create());
       $em->persist($newPost);
       $em->flush();
       return $this->redirectToRoute('app_todo');
        
    }
    #[Route('/todos/{id}', name: 'deleteTodo')]
    public function delete(int $id, ManagerRegistry $doctrine): Response
    {
       $em = $doctrine->getManager();
       $deletePost = $em->getRepository(Todos::class)->find($id);
       if (!$deletePost){
        return $this->json('no item found');
       }
       $em->remove($deletePost);
       $em->flush();
       
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todos/getOne/{id}', name: 'getOne')]
    public function getData(EntityManagerInterface $em, int $id): Response
    {
        $todo = $em->getRepository(Todos::class)->find($id);
        
            $data = [
                'id'=>$todo->getId(),
                'title'=>($todo->getTitle()),
                'description'=>($todo->getDescription()),
                'created_at'=>$todo->getEntryTime(),
            ];

        return $this->render('todo/edit.html.twig', [
            'data' => $data
        ]);
    }
    #[Route('/todos/update/{id}', name: 'updateTodo')]
    public function update(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
       $em = $doctrine->getManager();
       $updatePost = $em->getRepository(Todos::class)->find($id);
       if (!$updatePost){
        return $this->json('no item found');
       }
    //    $content=json_decode($request->getContent());
       $updatePost->setTitle($request->request->get('title'));
       $updatePost->setDescription($request->request->get('description'));
       $em->flush();
       
        return $this->redirectToRoute('app_todo');
    
    }
    
}
