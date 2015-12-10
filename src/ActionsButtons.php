<?php

namespace starcode\yii\grid;

use Closure;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class ActionsButtons extends Widget
{
    /**
     * @var string the ID of the group actions form.
     */
    public $formId;

    /**
     * @var string the method of the group action form.
     */
    public $formMethod = 'post';

    /**
     * @var string the ID of the controller that should handle the group actions specified here.
     * If not set, it will use the currently active controller. This property is mainly used by
     * [[urlCreator]] to create URLs for different actions. The value of this property will be prefixed
     * to each action name to form the route of the action.
     */
    public $controller;

    public $template = '{form} {buttons}';

    /**
     * @var string the template used for composing each cell in the action column.
     * Tokens enclosed within curly brackets are treated as controller action IDs (also called *button names*
     * in the context of action column). They will be replaced by the corresponding button rendering callbacks
     * specified in [[buttons]]. For example, the token `{delete}` will be replaced by the result of
     * the callback `buttons['delete']`. If a callback cannot be found, the token will be replaced with an empty string.
     *
     * @see groupActionsButtons
     */
    public $buttonsTemplate = '{delete}';


    /**
     * @var array button rendering callbacks. The array keys are the button names (without curly brackets),
     * and the values are the corresponding button rendering callbacks. The callbacks should use the following
     * signature:
     *
     * ```php
     * function ($url) {
     *     // return the button HTML code
     * }
     * ```
     *
     * where `$url` is the URL that the column creates for the button, `$widget` current instance of ActionsButtons widget.
     *
     * You can add further style to the button, for example add CSS class:
     *
     * ```php
     * [
     *     'delete' => function ($url, $widget) {
     *         $options = array_merge([
     *             'form' => $widget->formId,
     *             'formaction' => $url,
     *             'formmethod' => $widget->formMethod,
     *             'name' => 'submit',
     *         ], $widget->buttonOptions);
     *         return Html::submitInput('Delete', $options);
     *     },
     * ],
     * ```
     */
    public $buttons = [];

    /**
     * @var callable a callback that creates a button URL using the controller information.
     * The signature of the callback should be the same as that of [[createUrl()]].
     * If this property is not set, button URLs will be created using [[createUrl()]].
     */
    public $urlCreator;

    /**
     * @var array html options to be applied to the [[initDefaultButtons()|default buttons]].
     */
    public $buttonOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $widget) {
                $options = array_merge([
                    'form' => $widget->formId,
                    'formaction' => $url,
                    'formmethod' => $widget->formMethod,
                    'name' => 'submit',
                    'class' => 'btn btn-danger',
//                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this items?'),
                ], $widget->buttonOptions);
                return Html::submitButton('<span class="glyphicon glyphicon-trash"></span>', $options);
            };
        }
    }


    /**
     * Creates a URL for the given action and model.
     * This method is called for each button and each row.
     * @param string $action the button name (or action ID)
     * @return string the created URL
     */
    public function createUrl($action)
    {
        if ($this->urlCreator instanceof Closure) {
            return call_user_func($this->urlCreator, $action);
        } else {
            $route = $this->controller ? $this->controller . '/' . $action : $action;
            return Url::toRoute([$route]);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();
        return strtr($this->template, [
            '{form}' => $this->renderForm(),
            '{buttons}' => $this->renderButtons(),
        ]);
    }

    public function renderButtons()
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                $url = $this->createUrl($name);

                return call_user_func($this->buttons[$name], $url, $this);
            } else {
                return '';
            }
        }, $this->buttonsTemplate);
    }

    public function renderForm()
    {
        $form = Html::beginForm('', $this->formMethod, ['id' => $this->formId]);
        $form .= Html::endForm();
        return $form;
    }
}