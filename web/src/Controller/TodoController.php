<?php

namespace App\Controller;

use App\Entity\Todos;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todos', name:'app_todo', methods:['GET'])]
function index(EntityManagerInterface $em): Response
    {
    $products = $em->getRepository(Todos::class)->findAll();
    $data = [];
    foreach ($products as $todo) {
        $data[] = [
            'id' => $todo->getId(),
            'title' => htmlspecialchars_decode($todo->getTitle()),
            'description' => htmlspecialchars_decode($todo->getDescription()),
            'created_at' => $todo->getEntryTime(),
        ];
    }
    return $this->render('todo/index.html.twig', [
        'data' => $data,
    ]);
}

#[Route('/todos', name:'createTodo', methods:['POST'])]
function add(Request $request, ManagerRegistry $doctrine)
    {
    $em = $doctrine->getManager();
    $newPost = new Todos();
    $newPost->setTitle(htmlspecialchars($request->request->get('title')));
    $newPost->setDescription(htmlentities($request->request->get('description')));
    $newPost->setEntryTime(date_create());
    $em->persist($newPost);
    $em->flush();
    return $this->redirectToRoute('app_todo');

}
#[Route('/todos/{id}', name:'deleteTodo')]
function delete(int $id, ManagerRegistry $doctrine): Response
    {
    $em = $doctrine->getManager();
    $deletePost = $em->getRepository(Todos::class)->find($id);
    if (!$deletePost) {
        return $this->json('no item found');
    }
    $em->remove($deletePost);
    $em->flush();

    return $this->redirectToRoute('app_todo');
}

#[Route('/todos/getOne/{id}', name:'getOne')]
function getData(EntityManagerInterface $em, int $id): Response
    {
    $todo = $em->getRepository(Todos::class)->find($id);

    $data = [
        'id' => $todo->getId(),
        'title' => htmlspecialchars_decode($todo->getTitle()),
        'description' => htmlspecialchars_decode($todo->getDescription()),
        'created_at' => $todo->getEntryTime(),
    ];

    return $this->render('todo/edit.html.twig', [
        'data' => $data,
    ]);
}
#[Route('/todos/update/{id}', name:'updateTodo')]
function update(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
    $em = $doctrine->getManager();
    $updatePost = $em->getRepository(Todos::class)->find($id);
    if (!$updatePost) {
        return $this->json('no item found');
    }
    //    $content=json_decode($request->getContent());
    $updatePost->setTitle($request->request->get('title'));
    $updatePost->setDescription($request->request->get('description'));
    $em->flush();

    return $this->redirectToRoute('app_todo');

}

}
