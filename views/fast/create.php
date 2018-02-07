<?
use yii\web\View;
/**
 * @var View $this
 * @var \yii\easyii2\components\FastModelInterface $model
 */
?>
<? $this->title = Yii::t('easyii2', 'Create');?>

<?= $this->render('_menu', ['current_model' => $model]) ?>

<?= $this->render($model::getSlugModel().'/_form', ['model' => $model]) ?>