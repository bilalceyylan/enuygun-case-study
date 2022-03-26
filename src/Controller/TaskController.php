<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\TaskManager;

class TaskController extends AbstractController
{
    /**
     * @var TaskManager
     */
    public $taskManager;

    /**
     * @param TaskManager $taskManager
     */
    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     * @Route("/task", name="app_task")
     */
    public function index(): Response
    {
        $data = $this->taskManager->getWeeklyPlan();
        
        return $this->render('task/index.html.twig', [
            'data' => $data,
        ]);
    }
}
