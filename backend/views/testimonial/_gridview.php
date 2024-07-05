<?php
use common\models\Testimonial;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;


/** @var yii\web\View $this */
/** @var backend\models\TestimonialSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\Project[] $projects */
?>

<?php  ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn'],

        'id',
        [
            'attribute' => 'project_id',
            'format' => 'raw',
            // 'filter' => $projects,
            'value' => function($model) {
                /** @var $model \commom\models\Testimonial */
                return Html::a($model->project->name, ['project/view', 'id' => $model->project_id]);
            }
        ],
        'project_id',
        'customer_image_id',
        'title',
        'customer_name',
        //'review:ntext',
        //'rating',
        [
            'class' => ActionColumn::className(),
            // 'urlCreator' => function ($action, Testimonial $model, $key, $index, $column) {
            //     return Url::toRoute([$action, 'id' => $model->id]);
            //  }
            'controller' => 'testimonial'
        ],
    ],
]); 

?>