<?php

namespace Library\Models;

use Phalcon\Di;
use Phalcon\Db\{ Column, RawValue, Exception };
use Phalcon\Messages\Message;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Class ModelBase
 * @package Library\Models
 */
class ModelBase extends Model
{
    public function onConstruct()
    {
        $this->useDynamicUpdate(true);

        // These Behaviors (if any) are notified AFTER native behavior methods in the models
        $this->addBehavior(new Behavior\AutoTimeable([
            'beforeValidationOnCreate' => ['field' => 'created_on'],
            'beforeValidationOnUpdate' => ['field' => 'updated_on'],
        ]));

        $metaData = $this->getModelsMetaData();
        if ($metaData->hasAttribute($this, 'deleted')) {
            $this->addBehavior(new SoftDelete([
                'field' => 'deleted',
                'value' => 1
            ]));
        }
    }

    /**
     * Redefines result of getChangedFields for the case if orm.cast_on_hydrate is ON:
     * possibly a phalcon bug, snapshot contains casted data, but fetched result not always,
     * so we have to make a workaround for this case.
     * @return array
     */
    public function getChangedFields(): array
    {
        $changedFields = parent::getChangedFields();

        $snapshot = $this->getSnapshotData();
        $dataTypes = $this->getModelsMetaData()->getDataTypes($this);

        foreach ($changedFields as $i => $field) {
            switch ($dataTypes[$field]) {
                case Column::TYPE_INTEGER :
                    if (!$this->$field instanceof RawValue) {
                        $this->$field = (int) $this->$field;
                    }
                    break;
                case Column::TYPE_DECIMAL:
                case Column::TYPE_DOUBLE:
                case Column::TYPE_FLOAT:
                $this->$field = (float) $this->$field;
                    break;
                default:
                    break;
            }
            if ($snapshot[$field] === $this->$field) {
                unset($changedFields[$i]);
            }
        }
        return $changedFields;
    }

    /**
     * Making possible using of thrown error messages
     * @param array $params
     * @return bool
     */
    public function catchableSave(array $params = []): bool
    {
        $success = false;
        try {
            $this->assign($params);
            $success = $this->save();
        } catch (\Exception $e) {
            $this->appendMessage(new Message($e->getMessage()));
        }
        return $success;
    }

    /**
     * Making possible using of thrown error messages
     * @return bool
     */
    public function catchableDelete(): bool
    {
        $success = false;
        try {
            $success = $this->delete();
        } catch (\Exception $e) {
            $this->appendMessage(new Message($e->getMessage()));
        }
        return $success;
    }

    protected static function getCacheKey($calledMethod, $argsHash = '')
    {
        return sprintf('%s_%s(%s)', str_replace('\\', '_', static::class), $calledMethod, $argsHash);
    }

    /**
     * @return \Phalcon\Db\Adapter\AdapterInterface
     */
    public static function getConnection()
    {
        return Di::getDefault()->getShared('db');
    }

    /**
     * @return \Phalcon\Mvc\Model\Manager
     */
    public static function modelsManager()
    {
        return Di::getDefault()->getShared('modelsManager');
    }

    /**
     * @return \Phalcon\Mvc\Model\Query\BuilderInterface|\Phalcon\Mvc\Model\Query\Builder
     */
    public static function buildQuery()
    {
        return self::modelsManager()->createBuilder();
    }

    /**
     * @param string $attr
     * @return bool
     */
    public function hasAttribute(string $attr): bool
    {
        return $this->getModelsMetaData()->hasAttribute($this, $attr);
    }
}
