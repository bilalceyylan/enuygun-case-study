<?php 

namespace App\Service\Provider;

use App\Entity\Task;
use App\Service\Provider\ProviderAbstractInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class ProviderAbstract implements ProviderAbstractInterface
{ 
    /**
     * @var Task[]
     */
    private $tasks;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;  

    /**
     * @param EntityManagerInterface $entityManager 
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    abstract public function sendRequest(): array; 

    /**
     * @param array $data
     * @return Task
     */
    abstract public function parseData(array $data): Task;

    /**
     * @return array
     */
    public function getTasks(): array
    {
        $this->renderTasks();

        return $this->tasks;
    }

    /**
     * @return void
     */
    private function renderTasks(): void
    {
        foreach ($this->sendRequest() as $key => $item) {

            $task = $this->parseData($item);

            $this->entityManager->persist($task);

            $this->tasks[] = $task;
        }

        $this->entityManager->flush();
    }
}