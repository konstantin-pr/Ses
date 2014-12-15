<?php

namespace Library\Tool\Console\Command;

use Symfony\Component\Console\Input\InputArgument,
Symfony\Component\Console,
Symfony\Component\Console\Input\InputOption,
Symfony\Component\Console\Input\InputInterface,
Symfony\Component\Console\Output\OutputInterface;

class DropDbCommand extends Console\Command\Command
{

    /**
     * @see Console\Command\Command
     */
    protected function configure()
    {
        $this
        ->setName('orm:stuzo:drop-db')
        ->setDescription('Drop database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getHelper('em')->getEntityManager();
        $connection = $em->getConnection();

        $output->write('<comment>Dropping database "' . $connection->getDatabase() . '" ...</comment>' . PHP_EOL);

        try {
            $link = @mysql_connect($connection->getHost() . ':' . $connection->getPort(), $connection->getUsername(), $connection->getPassword());
            if($link === false){
                throw new \Exception('Error occured when connecting to MySQL. Check permissions');
            }
            $result = @mysql_query('DROP DATABASE IF EXISTS ' . $connection->getDatabase(), $link);
            if($result === false){
                throw new \Exception('Error occured during dropping database. Check dbname or permissions');
            }
            mysql_close($link);
        }
        catch (\Exception $e) {
            if($link !== false){
                mysql_close($link);
            }
            $output->write('<error>Can not drop database: ' . $e->getMessage() . '</error>' . PHP_EOL);
			exit(1);
		}
		$output->write('<info>Database dropped successfully!</info>' . PHP_EOL);
	}

}
