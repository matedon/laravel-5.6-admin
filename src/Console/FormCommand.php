<?php

namespace Encore\Admin\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class FormCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'laravel-admin form filed generator';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        $modelName = $this->option('model');
        if (empty($modelName) || !class_exists($modelName)) {
            $this->error('Model does not exists !');
            return false;
        }

        // Laravel 11+ native schema introspection (doctrine/dbal is no longer used)
        $model = $this->laravel->make($modelName);
        $columns = $model->getConnection()->getSchemaBuilder()->getColumns($model->getTable());

        $adminForm = '';
        if ($columns) {
            foreach ($columns as $column) {
                $name = $column['name'];
                if (in_array($name, ['id', 'created_at', 'deleted_at'])) {
                    continue;
                }
                $type = $this->normalizeColumnType($column['type_name']);
                $comment = $column['comment'];
                $default = $column['default'];

                // set column fieldType and defaultValue
                switch ($type) {
                    case 'boolean':
                    case 'bool':
                        $fieldType = 'switch';
                        $defaultValue = $default;
                        break;
                    case 'json':
                    case 'array':
                    case 'object':
                        $fieldType = 'text';
                        $defaultValue = $default;
                        break;
                    case 'string':
                        switch ($name) {
                            case $this->checkColumn($name, ['email']):
                                $fieldType = 'email';
                                break;
                            case $this->checkColumn($name, ['password', 'pwd']):
                                $fieldType = 'password';
                                break;
                            case $this->checkColumn($name, ['url', 'link', 'src', 'href']):
                                $fieldType = 'url';
                                break;
                            case $this->checkColumn($name, ['ip']):
                                $fieldType = 'ip';
                                break;
                            case $this->checkColumn($name, ['mobile', 'phone']):
                                $fieldType = 'mobile';
                                break;
                            case $this->checkColumn($name, ['color', 'rgb']):
                                $fieldType = 'color';
                                break;
                            case $this->checkColumn($name, ['image', 'img', 'avatar']) :
                                $fieldType = 'image';
                                break;
                            case $this->checkColumn($name, ['file', 'attachment']) :
                                $fieldType = 'file';
                                break;
                            default:
                                $fieldType = 'text';
                        }
                        $defaultValue = "'{$default}'";
                        break;
                    case 'integer':
                    case 'bigint':
                    case 'smallint':
                    case 'timestamp':
                        $fieldType = 'number';
                        $defaultValue = $default;
                        break;
                    case 'decimal':
                    case 'float':
                    case 'real':
                        $fieldType = 'decimal';
                        $defaultValue = $default;
                        break;
                    case 'datetime':
                        $fieldType = 'datetime';
                        $defaultValue = "date('Y-m-d H:i:s')";
                        break;
                    case 'date':
                        $fieldType = 'date';
                        $defaultValue = "date('Y-m-d')";
                        break;
                    case 'text':
                    case 'blob':
                        $fieldType = 'textarea';
                        $defaultValue = 'NULL';
                        break;
                    default:
                        $fieldType = 'text';
                        $defaultValue = "''";
                }

                // set column comment
                $comment = $comment ? $comment : $name;


                $adminForm .= "\$form->{$fieldType}('{$name}', '{$comment}')->default({$defaultValue});\n";
            }
            $this->alert("laravel-admin form filed generator for {$modelName}:");
            $this->info($adminForm);
        }

    }

    /**
     * Map a native database type name to the type names the generator used with doctrine/dbal.
     *
     * @param string $typeName
     * @return string
     */
    private function normalizeColumnType($typeName)
    {
        $typeName = strtolower((string) $typeName);

        $map = [
            'tinyint' => 'boolean',
            'int' => 'integer', 'mediumint' => 'integer',
            'varchar' => 'string', 'char' => 'string', 'enum' => 'string', 'set' => 'string',
            'tinytext' => 'text', 'mediumtext' => 'text', 'longtext' => 'text',
            'tinyblob' => 'blob', 'mediumblob' => 'blob', 'longblob' => 'blob',
            'double' => 'float', 'numeric' => 'decimal',
            'timestamp' => 'datetime',
        ];

        return $map[$typeName] ?? $typeName;
    }

    /**
     * Check if the table column contains the specified keywords of the array.
     *
     * @param string $haystack
     * @param array $needle
     * @return bool
     */
    private function checkColumn(string $haystack, array $needle)
    {
        foreach ($needle as $value) {
            if (strstr($haystack, $value) !== false) {
                return true;
            }
        }
        return false;
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', null, InputOption::VALUE_REQUIRED,
                'The eloquent model that should be use as controller data source.',],
        ];
    }
}
