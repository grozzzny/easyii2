<?php
use yii\web\View;
/**
 * @var View $this
 * @var \yii\easyii2\components\FastModelInterface $model
 * @var \yii\data\ActiveDataProvider $data
 */
$this->title = Yii::t('easyii2', $model::getNameModel());
?>

<?= $this->render('_menu', ['current_model' => $model]) ?>

<? if($data->count > 0) : ?>

    <?= $this->render($model::getSlugModel().'/_list', [
        'data' => $data,
        'model' => $model
    ]) ?>

    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>

<? else : ?>
    <p><?= Yii::t('easyii2', 'No records found') ?></p>
<? endif; ?>