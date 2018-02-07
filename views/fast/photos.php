<?php
use yii\easyii\widgets\Photos;
use yii\web\View;
/**
 * @var View $this
 * @var \yii\easyii2\components\FastModelInterface $model
 */
$this->title = 'Добавить фотографии';
?>

<?= $this->render('_menu', ['current_model' => $model]) ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= Photos::widget(['model' => $model])?>