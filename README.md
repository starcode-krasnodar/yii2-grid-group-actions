# Yii2 Grid Group Actions
Yii2 extension GridView with group actions.
# Installation
Via composer
```sh
composer require "starcode/yii2-grid-group-actions:*"
```
or add composer.json
```json
{
    "require": {
        "starcode/yii2-grid-group-actions": "1.*"
    }
}
```
# Usage
Create group actions controller.
```php 

// ...

use Yii;
use starcode\yii\grid\ActiveGroupActionsController;

class UserGroupActionsController extends ActiveGroupActionsController
{
    public $modelClass = 'app\models\User';

    public function actionDelete()
    {
        $group = $this->getGroup();
        foreach ($group as $id) {
            $model = $this->findModel($id);
            $model->deleted = 1;
            $model->save(false);
        }
        return $this->goBack();
    }

    public function actionHardDelete()
    {
        $group = $this->getGroup();
        foreach ($group as $id) {
            $model = $this->findModel($id);
            $model->delete();
        }
        return $this->goBack();
    }

    public function actionRestore()
    {
        $group = $this->getGroup();
        foreach ($group as $id) {
            $model = $this->findModel($id);
            $model->deleted = 0;
            $model->save(false);
        }
        return $this->goBack();
    }
}
```
Output widget.
```php
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'actionsButtonsOptions' => [
        'controller' => 'user-group-actions',
        'buttonsTemplate' => '{restore} {delete} {hard-delete}',
        'buttons' => [
            'restore' => function($url, $widget) {
                $options = array_merge([
                    'form' => $widget->formId,
                    'formaction' => $url,
                    'formmethod' => $widget->formMethod,
                    'name' => 'submit',
                    'class' => 'btn btn-primary',
                ], $widget->buttonOptions);
                return Html::submitButton('<span class="glyphicon glyphicon-save"></span>', $options);
            },
            'hard-delete' => function($url, $widget) {
                $options = array_merge([
                    'form' => $widget->formId,
                    'formaction' => $url,
                    'formmethod' => $widget->formMethod,
                    'name' => 'submit',
                    'class' => 'btn btn-danger',
                ], $widget->buttonOptions);
                return Html::submitButton('<span class="glyphicon glyphicon-remove"></span>', $options);
            }
        ]
    ],
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'fullName',
        'email:email',
        [
            'class' => 'yii\grid\ActionColumn',
        ],
    ],
]); ?>
```