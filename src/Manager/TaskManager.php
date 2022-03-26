<?php

namespace App\Manager;

use App\Service\Provider\ProviderV1Service;
use App\Service\Provider\ProviderV2Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;
use App\Entity\Developer;

class TaskManager 
{  
    const WEEKLY_WORKING_HOURS = 45;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var ProviderV1Service
     */
    private $providerv1;

    /**
     * @var ProviderV2Service
     */
    private $providerv2; 

    /**
     * @param EntityManagerInterface $entityManager
     * @param ProviderV1Service $providerv1
     * @param ProviderV2Service $providerv2
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ProviderV1Service $providerv1, 
        ProviderV2Service $providerv2
    ) 
    {
        $this->entityManager = $entityManager;
        $this->providerv1 = $providerv1;
        $this->providerv2 = $providerv2;
    }

    /**
     * @return void
     */
    public function createTask(): void {

        $v1 = $this->providerv1->getTasks();
        $v2 = $this->providerv2->getTasks(); 
    } 

    /** 
     * @return array
     */
    public function getWeeklyPlan(): array 
    {   
        $developerList = $this->entityManager->getRepository(Developer::class)->findAll();
        $taskList = $this->entityManager->getRepository(Task::class)->findAll();
        $taskSum = $this->entityManager
            ->createQueryBuilder()
            ->select('SUM(t.estimatedDuration) as taskSum')
            ->from(Task::class, 't')
            ->getQuery()
            ->getOneOrNullResult()
            ["taskSum"];
        
        $averageTimeDuration = $this->averageTimeDuration($developerList, $taskSum);

        for ($week = 1; $week <= $averageTimeDuration; $week++) {

            foreach ($developerList as $developer) {

                $hours = 0;

                foreach ($taskList as $key => $task) {

                    if (
                        $hours + $task->getEstimatedDuration() <= self::WEEKLY_WORKING_HOURS
                        && $task->getLevel() <= $developer->getLevel()
                    ) {
                        
                        $toDoList[$week][$developer->getName()][] = $task->getName();
                        unset($taskList[$key]);
                        $hours += $task->getEstimatedDuration();
                    }
                }
            }
            
            if (empty($taskList)) {
                break;
            }
        } 

        $result = [
            'duration' => $averageTimeDuration,
            'tasks' => $toDoList,
            'developers' => $developerList,
        ];

        return $result;
    }

    /**
     * @param Developer[] $developer
     * @param number $taskSum
     * @return number
     */
    public function averageTimeDuration(array $developerList, int $taskSum): int 
    {
        return round($taskSum / (self::WEEKLY_WORKING_HOURS * count($developerList)) );   
    }
}