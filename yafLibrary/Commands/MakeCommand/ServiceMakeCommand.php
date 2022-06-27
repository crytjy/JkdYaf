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

class ServiceMakeCommand extends GeneratorCommand
{
    public $name = 'make:service';

    protected $description = 'Create a service';

    protected $stubPath = LIB_PATH . 'Commands/Stub/Service.stub';

    protected $namespace = '/services';
}
