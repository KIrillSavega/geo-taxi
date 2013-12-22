<?php

class MigrateAllCommand extends CConsoleCommand
{
    public $notAllowedMigrateCommands = array(
        'migrate',
        'migrateall'
    );

    public function run($args)
    {
        $commands = Yii::app()->getCommandRunner()->commands;

        foreach ($commands as $command => $path) {
            if (str_replace('migrate', '', $command) != $command && !in_array($command, $this->notAllowedMigrateCommands)) {
                echo $command . ': ' . PHP_EOL;
                $command = Yii::app()->getCommandRunner()->createCommand($command);
                $command->run($args);
                echo PHP_EOL;
            }
        }
    }
}

