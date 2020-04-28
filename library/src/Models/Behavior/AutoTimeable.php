<?php

namespace Library\Models\Behavior;

use Phalcon\Db\RawValue;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\{ Behavior, Exception };

class AutoTimeable extends Behavior
{
    /**
     * @param string $eventType
     * @param ModelInterface|\Phalcon\Mvc\Model $model
     * @throws Exception
     */
    public function notify($eventType, ModelInterface $model): void
    {
        if (! \is_string($eventType)) {
            throw new Exception('Invalid parameter type');
        }
        switch ($eventType) {
            case 'beforeValidationOnCreate':
                $options = $this->getOptions($eventType);
                if (! isset($options['field'])) {
                    throw new Exception('The option \'field\' is required');
                }
                $metaData = $model->getModelsMetaData();
                /** @noinspection NotOptimalIfConditionsInspection */
                if ($metaData->hasAttribute($model, $options['field']) && $model->{$options['field']} === null) {
                    $model->writeAttribute($options['field'], new RawValue('NOW()'));
                }
                break;
            case 'beforeValidationOnUpdate':
                $options = $this->getOptions($eventType);
                if (! isset($options['field'])) {
                    throw new Exception('The option \'field\' is required');
                }
                $metaData = $model->getModelsMetaData();
                if ($metaData->hasAttribute($model, $options['field'])) {
                    $model->writeAttribute($options['field'], new RawValue('NOW()'));
                }
                break;
            default:
                break;
        }
    }
}
