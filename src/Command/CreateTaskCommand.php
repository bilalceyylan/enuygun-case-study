<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Manager\TaskManager;

class CreateTaskCommand extends Command
{
    /**
     * @var TaskManager
     */
    private $taskManager;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'task:create';

    /**
     * @param TaskManager $entityManager 
     */
    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager; 
        parent::__construct();
    }

    protected function configure()
    {
      $this
      	->setDescription('Task create command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->taskManager->createTask();

        $output->writeln(['Task created']);

        return Command::SUCCESS;
    }
}