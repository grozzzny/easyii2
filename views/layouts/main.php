<?php
use yii\easyii2\assets\AdminLteAsset;
use yii\helpers\Html;
use yii\easyii2\assets\AdminAsset;
use yii\easyii2\assets\AdminlteEasyiicmsAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AdminAsset::register($this);
$adminLteAsset = AdminLteAsset::register($this);
AdminlteEasyiicmsAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition <?= $adminLteAsset->skin == '_all-skins' ? 'skin-blue' : $adminLteAsset->skin?> sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render('header') ?>

    <?= $this->render('left') ?>

    <?= $this->render('content', ['content' => $content]) ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

