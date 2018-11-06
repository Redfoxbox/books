<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="books-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'ISBN')->textInput() ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


<?php
$initialPreview = [];
$initialPreviewConfig = [];
foreach($model_img as $images){
$initialPreview[] = Html::img("/uploads/".$images->attributes["img"]);
$initialPreviewConfig[] = [
	'url' => \yii\helpers\Url::toRoute(['books/deleteimage', 'id' => $images->id]),
    'key' => $images->id,
	'extra' => array("id"=>$images->id),

];
}

?>
<br>
<div class="form-group">
<div class="row">
    <div class="col-md-12">
        <?php
		
		echo $form->field($img_update, 'img[]')->widget(FileInput::classname(), [
            'options'=>['accept'=>'image/*','multiple' => true],
            'pluginOptions' => [
				'previewFileType' => "image",
                'overwriteInitial'=>false,
                'maxFileSize'=>2800,
                'fileActionSettings' => [
                    'fileActionSettings' => [
                        'showZoom' => false,
                        'showDelete' => true,
                    ],
                ],
                'browseClass' => 'btn btn-success',
                'uploadClass' => 'btn btn-info',
                'removeClass' => 'btn btn-danger',
                'showRemove' => false,
                'showUpload' => false,
                'initialPreview' => $initialPreview,
                'initialPreviewConfig' => $initialPreviewConfig,

            ],
]);

        ?>

    </div>
	</div>
	</div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


