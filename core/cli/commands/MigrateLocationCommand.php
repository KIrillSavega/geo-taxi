<?php

class MigrateLocationCommand extends MigrateCommand
{
    /**
     * @var string the directory that stores the migrations. This must be specified
     * in terms of a path alias, and the corresponding directory must exist.
     * Defaults to 'application.migrations' (meaning 'protected/migrations').
     */
    public $migrationPath = 'core.migrations.location';
    /**
     * @var string the application component ID that specifies the database connection for
     * storing migration information. Defaults to 'db'.
     */
    public $connectionID = 'dbLocation';
}