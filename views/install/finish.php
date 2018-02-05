<?php
use yii\helpers\Url;

$asset = \yii\easyii2\assets\EmptyAsset::register($this);;

$this->title = Yii::t('easyii2/install', 'Installation completed');
?>
<div class="container">
    <div id="wrapper" class="col-md-6 col-md-offset-3 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    <?= Yii::t('easyii2/install', 'Installation completed') ?>
                </div>
                <div class="panel-body text-center">
                    <a href="<?= Url::to(['/admin']) ?>">Go to control panel</a>
                </div>
            </div>
            <div class="text-center">
                <a class="logo" href="http://easyii2cms.com" target="_blank" title="easyii2CMS homepage">
                    <img src="<?= $asset->baseUrl ?>/img/logo_20.png">easyii2CMS
                </a>
            </div>
        </div>
    </div>
</div>
