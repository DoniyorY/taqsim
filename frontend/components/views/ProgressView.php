<section>
    <?php
    $html = '';
    $ol = '<ol class="progress-barr">';
    $html .= $ol;

    if ($status === 0) {
        $html .= '
                <li class="is-complete is-hovered text-left"><span>'.Yii::$app->params['progress_bar_registration'][Yii::$app->language].'</span></li>
                <li class="is-active"><span>'.Yii::$app->params['progress_bar_ordering'][Yii::$app->language].'</span></li>
                <li><span>'.Yii::$app->params['progress_bar_process'][Yii::$app->language].'</span></li>
                <li><span>'.Yii::$app->params['progress_bar_active'][Yii::$app->language].'</span></li>
                <li><span>'.Yii::$app->params['progress_bar_completed'][Yii::$app->language].'</span></li>
           ';
    }elseif ($status === 1){
        $html .= '<li class="is-complete is-hovered text-left"><span>'.Yii::$app->params['progress_bar_registration'][Yii::$app->language].'</span>
                </li>
                <li class="is-complete is-hovered"><span>'.Yii::$app->params['progress_bar_ordering'][Yii::$app->language].'</span></li>
                <li class="is-active "><span>'.Yii::$app->params['progress_bar_process'][Yii::$app->language].'</span></li>
                <li><span>'.Yii::$app->params['progress_bar_active'][Yii::$app->language].'</span></li>
                <li><span>'.Yii::$app->params['progress_bar_completed'][Yii::$app->language].'</span></li>';
    }elseif ($status === 2){
        $html .= '<li class="is-complete is-hovered text-left"><span>'.Yii::$app->params['progress_bar_registration'][Yii::$app->language].'</span>
                </li>
                <li class="is-complete is-hovered"><span>'.Yii::$app->params['progress_bar_ordering'][Yii::$app->language].'</span></li>
                <li class="is-complete is-hovered"><span>'.Yii::$app->params['progress_bar_process'][Yii::$app->language].'</span></li>
                <li class="is-active"><span>'.Yii::$app->params['progress_bar_active'][Yii::$app->language].'</span></li>
                <li><span>'.Yii::$app->params['progress_bar_completed'][Yii::$app->language].'</span></li>';
    }
    $html .= '</ol>';
    echo $html;
    ?>


</section>