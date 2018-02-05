<?php
$this->title = Yii::t('easyii2/subscribe', 'View subscribe history');
$this->registerCss('.subscribe-view dt{margin-bottom: 10px;}');
?>
<?= $this->render('_menu') ?>

<dl class="dl-horizontal subscribe-view">
    <dt><?= Yii::t('easyii2/subscribe', 'Subject') ?></dt>
    <dd><?= $model->subject ?></dd>

    <dt><?= Yii::t('easyii2', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($model->time, 'medium') ?></dd>

    <dt><?= Yii::t('easyii2/subscribe', 'Sent') ?></dt>
    <dd><?= $model->sent ?></dd>

    <dt><?= Yii::t('easyii2/subscribe', 'Body') ?></dt>
    <dd></dd>
</dl>
<?= $model->body ?>