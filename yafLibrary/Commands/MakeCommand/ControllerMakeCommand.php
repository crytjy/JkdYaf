<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Commands\MakeCommand;

use Commands\GeneratorCommand;

class ControllerMakeCommand extends GeneratorCommand
{
    public $name = 'make:controller';

    protected $description = 'Create a controller';

    protected $stubPath = LIB_PATH . 'Commands/Stub/Controller.stub';

    protected $namespace = '/modules';
}
