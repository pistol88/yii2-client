<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use pistol88\client\models\Category;

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;

\pistol88\client\assets\BackendAsset::register($this);
?>
<div class="model-index">

    <div class="row">
        <div class="col-md-2">
            <?= Html::a('Добавить клиента', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-10">
            <?=$this->render('../parts/menu');?>
        </div>
    </div>
    
    <?php
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            [
                'attribute' => 'image',
                'filter' => false,
                'content' => function ($image) {
                    if($image->image && $image = $image->image->getUrl('100x100')) {
                        return "<img src=\"{$image}\" class=\"thumb\" />";
                    }
                }
            ],
            ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 55px;']],
            'name',
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    $module->clientStatuses,
                    ['class' => 'form-control', 'prompt' => 'Статус']
                ),
                'content' => function($model) use ($module) {
                    return @$module->clientStatuses[$model->status];
                }
            ],
            [
                'attribute' => 'category_id',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'category_id',
                    Category::buildTextTree(),
                    ['class' => 'form-control', 'prompt' => 'Категория']
                ),
                'value' => 'category.name'
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 125px;']],
        ],
    ]); ?>

</div>
