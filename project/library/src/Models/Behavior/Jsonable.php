<?php

namespace Library\Models\Behavior;

use Phalcon\Db\RawValue;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\{ Behavior, Exception };

class Jsonable extends Behavior
{
    /**
     * @param string $eventType
     * @param ModelInterface $model
     * @throws Exception
     */
    public function notify($eventType, ModelInterface $model): void
    {
        /** @var \Phalcon\Mvc\Model $model */
        if (! \is_string($eventType)) {
            throw new Exception('Invalid parameter type');
        }
        $options = $this->getOptions();
        $field = $options['field'];
        $jsonOptions = $options['jsonOptions'] ?? JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT;
        switch ($eventType) {
            case 'afterFetch':
            case 'afterSave':
                $model->{$field} = self::decodeField($model->{$field});
                break;
            case 'beforeSave':
                $value = self::encodeField($model->{$field}, $jsonOptions);
                $model->writeAttribute($field, $value);
                break;
            default:
                break;
        }
    }

    public static function encodeField($field, $jsonOptions = 0)
    {
        $field = (array) $field;
        return $field ? json_encode($field, $jsonOptions) : new RawValue('NULL');
    }

    public static function decodeField($field)
    {
        if ($field) {
            return json_decode($field, true) ?? [];
        }
        return null;
    }
}
