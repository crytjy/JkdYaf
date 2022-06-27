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

class ModelMakeCommand extends GeneratorCommand
{
    public $name = 'make:model';

    protected $description = 'Create a model';

    protected $stubPath = LIB_PATH . 'Commands/Stub/Model.stub';

    protected $namespace = '/models';
}
