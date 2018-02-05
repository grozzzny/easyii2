<?php
use yii\easyii2\widgets\Photos;

$this->title = Yii::t('easyii2', 'Photos') . ' ' . $model->title;
?>

<?= $this->render('_menu', ['category' => $model->category]) ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= Photos::widget(['model' => $model])?>