<?php
use yii\helpers\Html;

$this->title = 'Help Center';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="help-index">
     <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>

     <p>

          This is the Help Center page. You may modify the following file to customize its content;x
          This is the Help Center page. You may modify the following file to customize its content;x
          This is the Help Center page. You may modify the following file to customize its content;x
          This is the Help Center page. You may modify the following file to customize its content;x
          This is the Help Center page. You may modify the following file to customize its content;x
          This is the Help Center page. You may modify the following file to customize its content;x
     </p>

    <?= Html::a('Account Setting', ['help/account-setting'], ['class' => 'btn btn-primary'])?>
     <?= Html::a('Privacy', ['site/index'], ['class' => 'btn btn-secondary'])?>

</div>
