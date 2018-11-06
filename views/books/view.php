<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\VarDumper;
/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;




?>
<div class="books-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ISBN',
            'author',
            'title',
        ],
    ]) ?>
	
	
	<?= DetailView::widget([
        'model' => $model_img,
        'attributes' => [
			[
    'attribute'=>'img',
	 'format' => 'html',
    'value'=>function ($model_img) {
			$imagehelper="";
			foreach($model_img as $key => $value){
				$imagehelper.=Html::img("/uploads/".$value->attributes["img"], ['alt' => 'Cover']);
			}
                return $imagehelper;
            }
			],
        ],
    ]) ?>

</div>
