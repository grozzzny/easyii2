<?php
use yii\easyii2\widgets\Photos;

$this->title = $model->title;
?>

<?= $this->render('@easyii2/views/category/_menu') ?>

<?= Photos::widget(['model' => $model])?>