<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Commands;

class GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    public $name = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * The stub file path.
     *
     * @var string
     */
    protected $stubPath = '';

    /**
     * The default namespace for the class.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * command params.
     *
     * @var string
     */
    protected $argv = '';

    /**
     * The default namespace for the class prefix.
     *
     * @var string
     */
    private $rootNamespace = 'app\\';

    private $tableName = '';

    private $className = '';

    private $classFileName = '';

    private $modulesName = '';

    private $type = '';

    private $filePath = '';

    public function __construct($argv)
    {
        $this->argv = $argv;
    }

    /**
     * php artisan make:services Api Test.
     */
    public function handle()
    {
        // 解析参数
        $this->parserParams();
        // 创建文件
        $this->makeDirectory(explode($this->classFileName . '.php', $this->filePath)[0]);

        $this->createClass();
    }

    /**
     * Get class name.
     *
     * @return mixed|string
     */
    public function parserParams()
    {
        $this->type = ($this->argv[1] ? explode(':', $this->argv[1]) : [])[1];  // 类型
        $this->modulesName = $this->argv[2] ? rtrim(ltrim($this->argv[2], '\\/'), '\\/') : '';
        $this->classFileName = $this->className = $this->argv[3] ? rtrim(ltrim($this->argv[3], '\\/'), '\\/') : '';
        $this->namespace = $this->namespace ? rtrim(ltrim($this->namespace, '\\/'), '\\/') : '';
        if ($this->type == 'model') {
            $this->classFileName = $this->className = $this->modulesName;
            $this->modulesName = '';
        }
        if ($this->rootNamespace != substr($this->namespace, 0, strlen($this->rootNamespace))) {
            $this->namespace = $this->rootNamespace . $this->namespace . '\\' . ($this->modulesName ? $this->modulesName . '\\' : '');
        }
        $this->namespace = rtrim(ltrim($this->namespace, '\\/'), '\\/');
        if ($this->type == 'controller') {
            $this->namespace = $this->namespace . '/controllers';
        }

        $this->filePath = APP_PATH . '/' . str_replace('\\', '/', $this->namespace) . '/' . $this->className . '.php';

        $this->initClassName();
    }

    /**
     * init classNmae.
     */
    public function initClassName()
    {
        switch ($this->type) {
            case 'controller':
                $this->className .= 'Controller';
                break;
            case 'model':
                $this->className .= 'Model';
                break;
        }
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param $filePath
     */
    public function makeDirectory($filePath)
    {
        if (! is_dir($filePath)) {
            @mkdir($filePath);
        }
    }

    /**
     * Create a class file.
     */
    public function createClass()
    {
        try {
            $nameData = [
                'DummyNamespace',
                'DummyClass',
            ];
            $valueData = [
                $this->namespace,
                $this->className,
            ];

            if ($this->type == 'model') {
                $nameData[] = 'DummyTable';
                $nameData[] = 'DummyCacheKey';

                $this->toUnderScore();
                $valueData[] = $this->tableName;
                $valueData[] = $this->className;
            }

            $stub = file_get_contents($this->stubPath);
            $stub = str_replace($nameData, $valueData, $stub);

            file_put_contents($this->filePath, $stub);

            $this->info($this->type . ' created successfully.');
        } catch (\Exception $exception) {
            $this->info($exception, 3);
        }
    }

    public function toUnderScore()
    {
        $str = $this->classFileName;
        $dstr = preg_replace_callback('/([A-Z]+)/', function ($matchs) {
            return '_' . strtolower($matchs[0]);
        }, $str);
        $this->tableName = trim(preg_replace('/_{2,}/', '_', $dstr), '_');
    }

    /**
     * Write a string as information output.
     *
     * @param mixed $string
     * @param mixed $type
     */
    public function info($string, $type = 1)
    {
        \Jkd::echoStr($string, $type);
    }
}
