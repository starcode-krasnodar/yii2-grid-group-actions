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
    public $modelClass = 'app\models\User';
    
    // ...
    public function actions()
    {
        return [
            'delete-group' => [
                 'class' => GroupAction::className(),
                 'run' => function($model) {
                      $model->delete();                 
                 }
            ],
            'publish-group' => [
                'class' => GroupAction::className(),
                'changeAttributes' => ['status' => User::STATUS_ACTIVE],
            ],
        ];
    }
    // ...
```
Output widget.
```php
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'actionsButtonsOptions' => [
        'buttonsTemplate' => '{delete-group}',
        'buttons' => [
            'delete-group' => function($url, $widget) {
                $options = array_merge([
                    'form' => $widget->formId,
                    'formaction' => $url,
                    'formmethod' => $widget->formMethod,
                    'name' => 'submit',
                    'class' => 'btn btn-danger',
                ], $widget->buttonOptions);
                return Html::submitButton('<span class="glyphicon glyphicon-trash"></span>', $options);
            },
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