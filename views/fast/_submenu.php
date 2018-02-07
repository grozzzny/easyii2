<?php
use yii\helpers\Url;
use yii\web\View;
/**
 * @var View $this
 * @var \yii\easyii2\components\FastModelInterface|\yii\easyii2\components\ActiveRecord $model
 */

$action = $this->context->action->id;
$module = $this->context->module->id;
?>

<ul class="nav nav-tabs">
    <li <?= ($action === 'edit') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $model->primaryKey, 'slug' => $model::getSlugModel()]) ?>">
            <?= Yii::t('easyii2', 'Edit') ?>
        </a>
    </li>

<? if(false):?>

    <? if($model::SUBMENU_PHOTOS): ?>
    <li <?= ($action === 'photos') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/admin/'.$module.'/a/photos', 'id' => $model->primaryKey, 'slug' => $model::getSlugModel()]) ?>">
            <span class="glyphicon glyphicon-camera"></span>
            <?= Yii::t('easyii', 'Photos') ?>
        </a>
    </li>
    <? endif;?>

    <? if($model::SUBMENU_FILES): ?>
        <li <?= ($action === 'files') ? 'class="active"' : '' ?>>
            <a href="<?= Url::to(['/admin/'.$module.'/a/files', 'id' => $model->primaryKey, 'slug' => $model::getSlugModel()]) ?>">
                Аудиозаписи
            </a>
        </li>
    <? endif;?>

<? endif;?>

</ul>
<br>