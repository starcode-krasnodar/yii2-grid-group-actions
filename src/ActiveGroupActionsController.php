<?php

namespace starcode\yii\grid;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\web\Controller;

class ActiveGroupActionsController extends Controller
{
    public $groupName = 'group';
    public $modelClass;

    public function init()
    {
        parent::init();
        if (empty($this->modelClass)) {
            throw new InvalidConfigException(get_class($this) . '::$modelClass must be set.');
        }
        if (empty($this->groupName)) {
            throw new InvalidConfigException(get_class($this) . '::$groupName must be set.');
        }
    }

    public function actions()
    {
        return [
            'delete' => [
                'class' => GroupAction::className(),
                'modelClass' => $this->modelClass,
                'groupName' => $this->groupName,
                'run' => function(ActiveRecordInterface $model) {
                    $model->delete();
                },
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }

    public function checkAccess($action, $group)
    {
    }
}