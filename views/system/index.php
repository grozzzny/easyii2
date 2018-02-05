<?php
use yii\easyii2\models\Setting;
use yii\helpers\Url;

$this->title = Yii::t('easyii2', 'System');
?>

<h4><?= Yii::t('easyii2', 'Current version') ?>: <b><?= Setting::get('easyii2_version') ?></b>
    <?php if(\yii\easyii2\AdminModule::VERSION > floatval(Setting::get('easyii2_version'))) : ?>
        <a href="<?= Url::to(['/admin/system/update']) ?>" class="btn btn-success"><?= Yii::t('easyii2', 'Update') ?></a>
    <?php endif; ?>
</h4>

<br>

<p>
    <a href="<?= Url::to(['/admin/system/flush-cache']) ?>" class="btn btn-default"><i class="glyphicon glyphicon-flash"></i> <?= Yii::t('easyii2', 'Flush cache') ?></a>
</p>

<br>

<p>
    <a href="<?= Url::to(['/admin/system/clear-assets']) ?>" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i> <?= Yii::t('easyii2', 'Clear assets') ?></a>
</p>