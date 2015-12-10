<?php

namespace starcode\yii\grid;

class GridView extends \yii\grid\GridView
{
    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{errors}`: the filter model error summary. See [[renderErrors()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     * - `{actions}`: the group actions. See [[renderActions()]].
     */
    public $layout = "{summary}\n{actions}\n{items}\n{pager}";

    /**
     * @var array the [[ActionsButtons]] widget options.
     */
    public $actionsButtonsOptions = [];

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case "{actions}":
                return $this->renderActions();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * Render the group actions.
     */
    public function renderActions()
    {
        return ActionsButtons::widget($this->actionsButtonsOptions);
    }
}