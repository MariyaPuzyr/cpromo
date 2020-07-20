<?php
    if($type == 'list') {
        $lastElement = end($languages);
        foreach(Yii::app()->params->languages as $key => $name) {
            if($key != $currentLang) {
                echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/assets/img/flags/'.$key.'.png', $name, ['class' => 'lang_head']), $this->getOwner()->createMultilanguageReturnUrl($key), ['data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => $name]);
                if($key != $lastElement) echo '&nbsp;&nbsp;';
            }
        }
    } elseif($type == 'drop') {
        echo '<div class="btn-group" role="group">';
            echo '<button class="btn btn-default border btn-withoutBG shadow-sm mr-1" data-toggle="dropdown" type="button" aria-expanded="false">'.CHtml::image(Yii::app()->theme->baseUrl.'/assets/img/flags/'.Yii::app()->language.'.svg', Yii::app()->params->languages[Yii::app()->language], ['class' => 'lang_head']).'</button>';
            echo '<ul class="dropdown-menu">';
                foreach(Yii::app()->params->languages as $key => $name){
                    if($key != $currentLang){
                        echo '<li>'.CHtml::link(CHtml::image(Yii::app()->theme->getBaseUrl().'/assets/img/flags/'.$key.'.svg', '', ['class' => 'lang_head mr-10']).' '.$name, $this->getOwner()->createMultilanguageReturnUrl($key)).'</li>';
                    }
                }
            echo '</ul>';
        echo '</div>';
    } elseif($type == 'drop2') {
        echo '<div class="btn-group" role="group">';
            echo '<button class="btn btn-default btn-withoutBG langBtn" data-toggle="dropdown" type="button" aria-expanded="false">'.CHtml::image(Yii::app()->theme->baseUrl.'/assets/img/flags/'.Yii::app()->language.'.png', Yii::app()->params->languages[Yii::app()->language], ['class' => 'lang_head']).'</button>';
            echo '<div class="dropdown-menu dropdown-menu-right">';
                echo '<ul class="">';
                    foreach(Yii::app()->params->languages as $key => $name){
                        if($key != $currentLang){
                            echo '<li style="text-align: center">'.CHtml::link(CHtml::image(Yii::app()->theme->getBaseUrl().'/assets/img/flags/'.$key.'.png', '', ['class' => 'lang_head mr-10']).' '.$name, $this->getOwner()->createMultilanguageReturnUrl($key)).'</li>';
                        }
                    }
                echo '</ul>';
            echo '</div>'; 
        echo '</div>';
    } elseif($type == 'promo') {
        echo '<div class="lng_dropdown">';
            echo '<select name="countries" id="lng_select" onchange="if (this.value) window.location.href = this.value">';
                echo '<option value='.Yii::app()->language.' data-image="'.Yii::app()->theme->baseUrl.'/assets/img/flags/'.Yii::app()->language.'.png" data-title="'.Yii::app()->params->languages[Yii::app()->language].'">'.strtoupper(Yii::app()->language).'</option>';
                foreach(Yii::app()->params->languages as $key => $name){
                    if($key != Yii::app()->language)
                        echo '<option value='.$this->getOwner()->createMultilanguageReturnUrl($key).' data-image="'.Yii::app()->theme->getBaseUrl().'/assets/img/flags/'.$key.'.png" data-title="'.$name.'">'.strtoupper($key).'</option>';
                }
            echo '</select>';
        echo '</div>';
    }