<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 06/11/17 10:46
 */

namespace Modules\Projector\Components;


use Modules\Editor\Fields\TextEditorField;
use Modules\Files\Fields\HasManyFilesField;
use Phact\Helpers\Paths;
use Phact\Helpers\Text;
use Phact\Main\Phact;
use Phact\Orm\Fields\BooleanField;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\DateField;
use Phact\Orm\Fields\DecimalField;
use Phact\Orm\Fields\FileField;
use Phact\Orm\Fields\ForeignField;
use Phact\Orm\Fields\HasManyField;
use Phact\Orm\Fields\ImageField;
use Phact\Orm\Fields\IntField;
use Phact\Orm\Fields\JsonField;
use Phact\Orm\Fields\ManyToManyField;
use Phact\Orm\Fields\PositionField;
use Phact\Orm\Fields\SlugField;
use Phact\Orm\Fields\TextField;
use Phact\Orm\Model;
use Phact\Template\Renderer;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class Scheme
{
    use Renderer;

    public function getFields()
    {
        return [
            'IntField' => [
                'class' => IntField::class,
                'attributes' => [
                    'len' => [
                        'name' => 'Длина',
                        'type' => 'int',
                        'default' => 11
                    ],
                    'default' => [
                        'name' => 'По-умолчанию',
                        'type' => 'int',
                        'default' => ''
                    ],
                ]
            ],
            'CharField' => [
                'class' => CharField::class,
                'attributes' => [
                    'len' => [
                        'name' => 'Длина',
                        'type' => 'int',
                        'default' => 255
                    ],
                    'default' => [
                        'name' => 'По-умолчанию',
                        'type' => 'char',
                        'default' => ''
                    ],
                ]
            ],
            'BooleanField' => [
                'class' => BooleanField::class,
                'attributes' => [
                    'default' => [
                        'name' => 'По-умолчанию',
                        'type' => 'bool',
                        'default' => 0
                    ]
                ]
            ],
            'DateField' => [
                'class' => DateField::class,
                'attributes' => self::getDateAttributes()
            ],
            'DateTimeField' => [
                'class' => DateField::class,
                'attributes' => self::getDateAttributes()
            ],
            'DecimalField' => [
                'class' => DecimalField::class,
                'attributes' => [
                    'precision' => [
                        'name' => 'Всего цифр',
                        'type' => 'int',
                        'default' => 10
                    ],
                    'scale' => [
                        'name' => 'После запятой',
                        'type' => 'int',
                        'default' => 2
                    ],
                ]
            ],
            'EmailField' => [
                'class' => CharField::class,
                'attributes' => [
                    'len' => [
                        'name' => 'Длина',
                        'type' => 'int',
                        'default' => 255
                    ],
                ]
            ],
            'FileField' => [
                'class' => FileField::class,
                'attributes' => [
                    'md5Name' => [
                        'name' => 'Хэшировать имя',
                        'type' => 'bool',
                        'default' => 1
                    ]
                ]
            ],
            'ImageField' => [
                'class' => ImageField::class,
                'attributes' => [
                    'md5Name' => [
                        'name' => 'Хэшировать имя',
                        'type' => 'bool',
                        'default' => 1
                    ]
                ]
            ],
            'ForeignField' => [
                'class' => ForeignField::class,
                'attributes' => self::getRelationAttributes()
            ],
            'HasManyField' => [
                'class' => HasManyField::class,
                'attributes' => self::getRelationAttributes()
            ],
            'HasManyFilesField' => [
                'class' => HasManyFilesField::class,
                'attributes' => self::getRelationAttributes()
            ],
            'ManyToManyField' => [
                'class' => ManyToManyField::class,
                'attributes' => self::getRelationAttributes()
            ],
            'JsonField' => [
                'class' => JsonField::class
            ],
            'PositionField' => [
                'class' => PositionField::class
            ],
            'SlugField' => [
                'class' => SlugField::class,
                'attributes' => [
                    'source' => [
                        'name' => 'Источник',
                        'type' => 'char',
                        'default' => 'name'
                    ]
                ]
            ],
            'TextField' => [
                'class' => TextField::class
            ],
            'TextEditorField' => [
                'class' => TextEditorField::class
            ]
        ];
    }

    public static function getDateAttributes()
    {
        return [
            'autoNowAdd' => [
                'name' => 'Дата добавления',
                'type' => 'bool',
                'default' => 0
            ],
            'autoNow' => [
                'name' => 'Дата изменения',
                'type' => 'bool',
                'default' => 0
            ],
        ];
    }

    public static function getRelationAttributes()
    {
        return [
            'modelClass' => [
                'name' => 'Связанная модель',
                'type' => 'model',
                'default' => ''
            ]
        ];
    }

    public function getModules()
    {
        return Phact::app()->getModulesList();
    }


    public function getData()
    {
        return [
            'models' => $this->getModels(),
            'modules' => $this->getModules(),
            'fields' => $this->getFields()
        ];
    }

    public function getSavedSchema()
    {
        $schema = $this->fetchSavedSchema();
        return $schema ?: $this->getDefaultSchema();
    }

    public function getDefaultSchema()
    {
        $width = 190;
        $i = 0;
        $x = 20;
        $y = 1800;

        return [
            [
                'name' => 'AdminConfig',
                'module' => 'Admin',
                'coordinates' => [
                    'x' => $x + $width * ($i++),
                    'y' => $y
                ],
            ],
            [
                'name' => 'Settings',
                'module' => 'Mail',
                'coordinates' => [
                    'x' => $x + $width * ($i++),
                    'y' => $y
                ],
            ],
            [
                'name' => 'MetaSettings',
                'module' => 'Meta',
                'coordinates' => [
                    'x' => $x + $width * ($i++),
                    'y' => $y
                ],
            ],
            [
                'name' => 'MetaTemplate',
                'module' => 'Meta',
                'coordinates' => [
                    'x' => $x + $width * ($i++),
                    'y' => $y
                ],
            ],
            [
                'name' => 'MetaUrl',
                'module' => 'Meta',
                'coordinates' => [
                    'x' => $x + $width * ($i++),
                    'y' => $y
                ],
            ],
            [
                'name' => 'InfoBlock',
                'module' => 'Text',
                'coordinates' => [
                    'x' => $x + $width * ($i++),
                    'y' => $y
                ],
            ],
            [
                'name' => 'User',
                'module' => 'User',
                'coordinates' => [
                    'x' => $x + $width * ($i++),
                    'y' => $y
                ],
            ]
        ];
    }

    public function getSchemaFilename()
    {
        $path = Paths::get('base') . '/../schema';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $name = 'schema.json';
        return $path . '/' . $name;
    }

    public function fetchSavedSchema()
    {
        $filename = $this->getSchemaFilename();
        if (is_file($filename)) {
            return json_decode(file_get_contents($filename), true);
        }
        return null;
    }

    public function storeSavedSchema($data)
    {
        file_put_contents($this->getSchemaFilename(), json_encode($data));
        return null;
    }

    public function getModels()
    {
        $savedSchema = $this->getSavedSchema();
        $currentSchema = $this->fetchModels();

        $syncedModels = [];
        $data = [];

        // Склейка моделей

        foreach ($currentSchema as $currentModel) {
            foreach ($savedSchema as $savedModel) {
                if (($savedModel['module'] == $currentModel['module']) &&
                    ($savedModel['name'] == $currentModel['name'])) {

                    $currentModel['exists'] = 1;
                    $currentModel['coordinates'] = $savedModel['coordinates'];

                    $fields = [];
                    $syncedFields = [];

                    // Склейка полей

                    foreach ($currentModel['fields'] as $currentField) {
                        $field = $currentField;
                        $hasSaved = false;
                        if (isset($savedModel['fields'])) {
                            foreach ($savedModel['fields'] as $savedField) {
                                if ($savedField['name'] == $currentField['name']) {
                                    $field = $savedField;
                                    unset($currentField['exists']);
                                    $field['exists'] = 1;
                                    $field['changes'] = ($currentField == $savedField) ? 0 : 1;
                                    $hasSaved = true;
                                }
                            }
                        }
                        if (!$hasSaved) {
                            $field['changes'] = 0;
                        }
                        $fields[] = $field;
                        $syncedFields[] = $currentField['name'];
                    }

                    if (isset($savedModel['fields'])) {
                        foreach ($savedModel['fields'] as $savedField) {
                            if (!in_array($savedField['name'], $syncedFields)) {
                                $savedField['exists'] = 0;
                                $fields[] = $savedField;
                            }
                        }
                    }

                    $currentModel['fields'] = $fields;
                } else {
                    $currentModel['exists'] = 1;
                }
            }

            $syncedModels[] = $currentModel['module'] . '\\' . $currentModel['name'];
            $data[] = $currentModel;
        }

        foreach ($savedSchema as $savedModel) {
            $name  = $savedModel['module'] . '\\' . $savedModel['name'];
            if (!in_array($name, $syncedModels)) {
                $savedModel['exists'] = 0;
                $data[] = $savedModel;
            }
        }

        // Расставляем модели по-умолчанию
        $y = 0;
        $x = 20;
        $width = 190;
        $height = 400;
        $line = 2000;

        foreach ($data as $i => $model) {
            if (!isset($data[$i]['coordinates'])) {
                if ($x > $line) {
                    $x = 20;
                    $y += $height;
                }
                $data[$i]['coordinates'] = [
                    'x' => $x,
                    'y' => $y
                ];
                $x += $width;
            };
        }
        return $data;
    }

    /**
     * Получение моделей из кода
     *
     * @return array
     */
    public function fetchModels()
    {
        $modulesPath = Paths::get('Modules');
        $activeModules = Phact::app()->getModulesList();
        $modelsFolder = 'Models';

        $models = [];
        foreach ($activeModules as $module) {
            $path = implode(DIRECTORY_SEPARATOR, [$modulesPath, $module, $modelsFolder]);
            if (is_dir($path)) {
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename)
                {
                    if ($filename->isDir()) continue;
                    $name = $filename->getBasename('.php');
                    $class = implode('\\', ['Modules', $module, $modelsFolder, $name]);
                    if (class_exists($class) && is_a($class, Model::class, true)) {
                        $reflection = new ReflectionClass($class);
                        if (!$reflection->isAbstract()) {
                            $model = new $class();
                            $models[] = $this->fetchModelData($model, $reflection);
                        }
                    }
                }
            }
        }
        return $models;
    }

    /**
     * Получение информации о модели из кода
     *
     * @param Model $model
     * @param ReflectionClass $reflection
     * @return array
     */
    public function fetchModelData(Model $model, ReflectionClass $reflection)
    {
        $fields = $model->getFields();

        $modelName = $model->classNameShort();
        $moduleName = $model->getModuleName();

        $constants = $reflection->getConstants();
        $resultFields = [];
        foreach ($fields as $name => $field) {
            $class = isset($field['class']) ? $field['class'] : '';
            $type = substr($class, strrpos($class, '\\')+1);

            $data = [
                'name' => $name,
                'label' => isset($field['label']) ? $field['label'] : '',
                'type' => $type,
                'editable' => isset($field['editable']) ? ($field['editable'] ? 1 : 0) : 1,
                'null' => isset($field['null']) ? ($field['null'] ? 1 : 0) : 0,
                'hint' => isset($field['hint']) ? $field['hint'] : '',
                'choices' => [],
                'attributes' => [],
                'exists' => 1
            ];

            if (isset($field['choices']) && is_array($field['choices']) && $field['choices']) {
                $nameUpper = mb_strtoupper($name);
                $choicesInfo = [];
                foreach ($field['choices'] as $value => $label) {
                    $choiceName = '';
                    foreach ($constants as $constantName => $constantValue) {
                        if (Text::startsWith(mb_strtoupper($constantName, 'UTF-8'), $nameUpper . '_') && $constantValue == $value) {
                            $choiceName = $constantName;
                        }
                    }
                    $choicesInfo[] = [
                        'name' => $choiceName,
                        'label' => $label,
                        'value' => $value
                    ];
                }
                if ($choicesInfo) {
                    $data['choices'] = $choicesInfo;
                }
            }

            $attributes = [];
            $fieldsData = $this->getFields();
            if (isset($fieldsData[$type]) && isset($fieldsData[$type]['attributes'])) {
                foreach ($fieldsData[$type]['attributes'] as $attributeName => $attributeData) {
                    $sourceAttrName = $attributeName;
                    if ($attributeName == 'len') {
                        $sourceAttrName = 'length';
                    }
                    $attributes[$attributeName] = isset($field[$sourceAttrName]) ? $field[$sourceAttrName] : $attributeData['default'];
                    if ($attributeName == 'modelClass') {
                        $attributes[$attributeName] = str_replace('Modules\\', '', $attributes[$attributeName]);
                        $attributes[$attributeName] = str_replace('Models\\', '', $attributes[$attributeName]);
                    }
                }
                $data['attributes'] = $attributes;
            }
            $resultFields[] = $data;
        }

        return [
            'name' => $modelName,
            'module' => $moduleName,
            'fields' => $resultFields
        ];
    }

    /**
     * Синхронизация с файлом
     *
     * @param $models
     * @param $syncFiles bool Создать модели/модули в коде
     */
    public function sync($models, $syncFiles)
    {
        $this->storeSavedSchema($models);
        if ($syncFiles) {
            $current = $this->fetchModels();
            $currentExists = function ($moduleName, $modelName) use ($current) {
                foreach ($current as $item) {
                    if ($item['name'] == $modelName && $item['module'] == $moduleName) {
                        return true;
                    }
                }
                return false;
            };
            foreach ($models as $model) {
                if (!$currentExists($model['name'], $model['module'])) {
                    $this->syncModel($model);
                }
            }
        }
    }

    /**
     * Создать модель в коде
     *
     * @param $model
     */
    public function syncModel($model)
    {
        $this->syncModule($model['module']);

        $modelsDir = Paths::get("Modules.{$model['module']}.Models");
        if (!is_dir($modelsDir)) {
            mkdir($modelsDir);
        }
        $modelFile = "{$modelsDir}/{$model['name']}.php";
        if (!is_file($modelFile)) {
            $uses = [];
            $constants = [];
            $fields = $this->getFields();
            $toString = '';
            foreach ($model['fields'] as $key => $fieldInfo) {
                $type = $fieldInfo['type'];
                $fieldStructure = $fields[$type];
                if (!$toString && $type == 'CharField') {
                    $toString = $fieldInfo['name'];
                }
                if ($fieldStructure) {
                    $class = $fieldStructure['class'];
                    $uses[] = $class;
                    if ($fieldInfo['attributes'] && is_array($fieldInfo['attributes'])) {
                        $attributes = [];
                        foreach ($fieldInfo['attributes'] as $name => $attribute) {
                            $data = isset($fieldStructure['attributes'][$name]) ? $fieldStructure['attributes'][$name] : null;
                            if (!isset($data['default']) || ($data['default'] != $attribute)) {
                                $attrName = $name;
                                if ($attrName == 'len') {
                                    $attrName = 'length';
                                }
                                if ($attrName == 'modelClass') {
                                    $classModel = str_replace('\\', '\\Models\\', $attribute);
                                    $classModel = 'Modules\\' . $classModel;
                                    $uses[] = $classModel;
                                    $attributeData = explode('\\', $attribute);
                                    $attribute = isset($attributeData[1]) ? $attributeData[1] . '::class' : '';
                                }
                                $attributes[$attrName] = is_numeric($attribute) ? (int) $attribute : (string) $attribute;
                            }
                        }
                        $model['fields'][$key]['attributesClean'] = $attributes;
                    }
                }
                if (isset($fieldInfo['choices']) && $fieldInfo['choices']) {
                    foreach ($fieldInfo['choices'] as $choice) {
                        $constants[] = [
                            'name' => $choice['name'],
                            'value' => $choice['value']
                        ];
                    }
                }
            }

            file_put_contents($modelFile, $this->renderTemplate('projector/php/model.php', [
                'constants' => $constants,
                'name' => $model['name'],
                'module' => $model['module'],
                'uses' => array_unique($uses),
                'fields' => $model['fields'],
                'toString' => $toString
            ]));
        }
    }

    public function syncModule($name)
    {
        $moduleDir = Paths::get("Modules.{$name}");
        if (!is_dir($moduleDir)) {
            mkdir($moduleDir);
        }
        $moduleFile = "{$moduleDir}/{$name}Module.php";
        if (!is_file($moduleFile)) {
            file_put_contents($moduleFile, $this->renderTemplate('projector/php/module.php', [
                'name' => $name
            ]));
        }
    }
}