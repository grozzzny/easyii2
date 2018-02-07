<?php
use yii\helpers\Url;
use yii\web\View;
/**
 * @var View $this
 * @var \yii\easyii2\components\FastModelInterface $current_model
 * @var \yii\easyii2\components\FastModelInterface $model
 */

$action = $this->context->action->id;
$module = $this->context->module;

$items = [];
foreach ($module->models as $model){
    $items[] = [
        'label' => $model::getNameModel(),
        'url' => ['a/', 'slug' => $model::getSlugModel()],
        'active' => $model::getSlugModel() == $current_model::getSlugModel(),
    ];
}

?>

<?=\yii\bootstrap\Nav::widget([
    'items' => $items,
    'options' => ['class' =>'nav nav-tabs', 'style'=> 'margin-bottom: 30px;']
]);?>



<ul class="nav nav-pills">

    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/admin/'.$module->id, 'slug' => $current_model::getSlugModel()]) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii2', 'List') ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to([
        '/admin/'.$module->id.'/a/create',
        'slug' => $current_model::getSlugModel()
        ]) ?>">
            <?= Yii::t('easyii2', 'Create') ?>
        </a>
    </li>

    <? if($action === 'index'):?>

        <?= $this->render($current_model::getSlugModel().'/_filter', [
            'current_model' => $current_model
        ]) ?>

    <? endif;?>

</ul>
<br/>