<?php

namespace starcode\yii\grid;

use Yii;
use yii\base\InvalidValueException;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ActiveGroupActionsController extends Controller
{
    public $groupName = 'group';
    public $modelClass;

    public function init()
    {
        parent::init();
        if ($this->modelClass == null) {
            throw new InvalidValueException('modelClass is empty.');
        }
    }

    public function actionDelete()
    {
        $group = $this->getGroup();
        foreach ($group as $id) {
            $model = $this->findModel($id);
            $model->delete();
        }
        return $this->goBack();
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return Yii::$app->request->getBodyParam($this->groupName);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /* @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}