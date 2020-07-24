<?php $this->pageTitle = Yii::t('controllers', 'user_recovery_title'); ?>

<?php $this->pageTitle = Yii::t('controllers', 'user_login_login_title'); ?>
<div class="login-page d-flex align-items-center auth">
  <div class="row flex-grow w-100">
      <div class="col-lg-6 mx-auto">
          <div class="auth-form-light text-left p-5">
            <div class="brand-logo"><?=CHtml::image($this->assetsBase.DIRECTORY_SEPARATOR.'img/logo_new.png'); ?></div>
            <h4 class="pb-3"><?= Yii::t('controllers', 'user_recovery_title'); ?></h4>
              <?php
              $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                  'id' => get_class($model),
                  'enableClientValidation' => false,
                  'enableAjaxValidation' => true,
                  'htmlOptions' => ['class' => 'form-signin'],
                  'floating' => true,
                  'withOutPlaceholder' => true,
                  'clientOptions' => [
                      'validateOnSubmit' => true,
                      'validateOnChange' => false,
                      'hideErrorMessage' => false,
                  ]
              ]);
              echo $form->textFieldGroup($model, 'username');
              ?>
            <div class="row">
              <div class="col-md-7">
                  <?= $form->passwordFieldGroup($model, 'verifyCode'); ?>
              </div>
              <div class="col-md-5 text-center">
                  <?php $this->widget('CCaptcha', [
                      'showRefreshButton' => true,
                      'buttonLabel' => '<i class="fas fa-sync-alt"></i>',
                      'buttonOptions' => [
                          'style' => 'color: #e4e7ed;!important'
                      ]
                  ]); ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <?php
                  $this->widget('bootstrap.widgets.TbButton', [
                      'context' => 'primary',
                      'block' => true,
                      'buttonType' => 'submit',
                      'label' => Yii::t('controllers', 'user_recovery_btn_recovery'),
                      'htmlOptions' => [
                          'class' => 'auth-form-btn mb-3'
                      ],
                  ]);

                  echo '<div class="text-center mt-4 mb-3 font-weight-light forgot-password">';
                  echo CHtml::link(Yii::t('controllers', 'user_register_btn_login'), Yii::app()->user->loginUrl).'<br />';
                  if(!Yii::app()->settings->get('system', 'enableLockRegister'))
                      echo CHtml::link(Yii::t('controllers', 'user_login_btn_register'), $this->createUrl('/register'));
                  echo '</div>';
                  ?>
              </div>
            </div>
            <?php $this->endWidget(); unset($form);?>
            <center><?php $this->widget('LangSelect', ['type' => 'list']); ?></center>


              <!--<div class="login-box">
                  <div class="login-logo text-center">
                      <?/*=CHtml::image($this->assetsBase.DIRECTORY_SEPARATOR.'img/logo_new.png'); */?>
                  </div><br><br>
                  <h2 class="text-center mb-4"><?/*= Yii::t('controllers', 'user_recovery_title'); */?></h2>
                  <?php
/*                      $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                          'id' => get_class($model),
                          'enableClientValidation' => false,
                          'enableAjaxValidation' => true,
                          'htmlOptions' => ['class' => 'form-signin'],
                          'floating' => true,
                          'withOutPlaceholder' => true,
                          'clientOptions' => [
                              'validateOnSubmit' => true,
                              'validateOnChange' => false,
                              'hideErrorMessage' => false,
                          ]
                      ]);
                      echo $form->textFieldGroup($model, 'username');
                  */?>
                  <div class="row">
                      <div class="col-md-7">
                          <?/*= $form->passwordFieldGroup($model, 'verifyCode'); */?>
                      </div>
                      <div class="col-md-5 text-center">
                          <?php /*$this->widget('CCaptcha', [
                              'showRefreshButton' => true,
                              'buttonLabel' => '<i class="fas fa-sync-alt"></i>',
                              'buttonOptions' => [
                                  'style' => 'color: #e4e7ed;!important'
                              ]
                          ]); */?>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <?php
/*                              $this->widget('bootstrap.widgets.TbButton', [
                                  'context' => 'primary',
                                  'block' => true,
                                  'buttonType' => 'submit',
                                  'label' => Yii::t('controllers', 'user_recovery_btn_recovery'),
                                  'htmlOptions' => [
                                      'class' => 'mb-3 mt-3 big-btn'
                                  ],
                              ]);

                              echo '<h6 class="login-extlink">';
                                  echo CHtml::link(Yii::t('controllers', 'user_register_btn_login'), Yii::app()->user->loginUrl).'<br />';
                                  if(!Yii::app()->settings->get('system', 'enableLockRegister'))
                                      echo CHtml::link(Yii::t('controllers', 'user_login_btn_register'), $this->createUrl('/register'));
                              echo '</h6>';
                          */?>
                      </div>
                  </div>
              </div>
              <?php /*$this->endWidget(); unset($form);*/?>
              <hr style="width: 30%" />
              <center><?php /*$this->widget('LangSelect', ['type' => 'list']); */?></center>-->
          </div>
      </div>
  </div>
</div>
</div>