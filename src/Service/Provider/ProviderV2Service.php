<?php

namespace App\Service\Provider;

use App\Service\Provider\ProviderAbstract;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class ProviderV2Service extends ProviderAbstract 
{
    private const URL = "http://www.mocky.io/v2/5d47f235330000623fa3ebf7";

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @param HttpClientInterface $client
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        HttpClientInterface $client,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($entityManager);
        
        $this->client = $client;
    } 

    /**
     * @param array $data
     * @return Task
     */
    public function parseData(array $data): Task
    {
        $task = new Task();
        $task->setName(array_keys($data)[0]);
        $task->setEstimatedDuration($data[array_keys($data)[0]]['estimated_duration']);
        $task->setLevel($data[array_keys($data)[0]]['level']);
        
        return $task;
    }

    /**
     * @return array
     */
    public function sendRequest(): array
    {
        return $this->client->request('GET', self::URL)->toArray();
    }
}