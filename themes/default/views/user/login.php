<?php print_r(Yii::app()->user->getIsSuperuser()); ?>
<?php $this->pageTitle = Yii::t('controllers', 'user_login_login_title'); ?>
<div class="login-page d-flex align-items-center auth">
  <div class="row flex-grow w-100">
      <div class="col-lg-6 mx-auto">
          <div class="auth-form-light text-left p-5">
            <div class="brand-logo"><?=CHtml::image($this->assetsBase.DIRECTORY_SEPARATOR.'img/logo_new.png'); ?></div>
            <h4 class="pb-3"><?= Yii::t('controllers', 'user_login_login_title'); ?></h4>
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
              echo $form->passwordFieldGroup($model, 'password');
              ?>
            <div class="row">
              <div class="col-md-6">
                  <?php echo $form->textFieldGroup($model, 'verifyCode'); ?>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <?php $this->widget('CCaptcha', [
                        'showRefreshButton' => true,
                        'buttonLabel' => '<i class="fas fa-sync-alt"></i>',
                        'buttonOptions' => [
                            'style' => 'color: #e4e7ed;!important'
                        ]
                    ]); ?>
                </div>
              </div>
            </div>
              <?php
              $this->widget('bootstrap.widgets.TbButton', [
                  'context' => 'primary',
                  'block' => true,
                  'buttonType' => 'submit',
                  'label' => Yii::t('controllers', 'user_login_btn_signin'),
                  'htmlOptions' => [
                      'class' => 'auth-form-btn mb-3'
                  ],
              ]);
              echo '<div class="text-center mt-4 mb-3 font-weight-light forgot-password">';
              echo CHtml::link(Yii::t('controllers', 'user_login_btn_recovery'), $this->createUrl('/recovery')).'<br />';
              if(!Yii::app()->settings->get('system', 'enableLockRegister'))
                  echo CHtml::link(Yii::t('controllers', 'user_login_btn_register'), $this->createUrl('/register'));
              echo '</div>';
              $this->endWidget(); unset($form);
              ?>

            <center><?php $this->widget('LangSelect', ['type' => 'list']); ?></center>

              <!--<div class="login-box">
                  <div class="login-logo text-center">
                      <?/*=CHtml::image($this->assetsBase.DIRECTORY_SEPARATOR.'img/logo_new.png'); */?>
                  </div><br><br>
                  <h2 class="text-center mb-4"><?/*= Yii::t('controllers', 'user_login_login_title'); */?></h2>
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
                      echo $form->passwordFieldGroup($model, 'password');
                  */?>
                  <div class="row">
                      <div class="col-md-6">
                          <?php /*echo $form->textFieldGroup($model, 'verifyCode'); */?>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <?php /*$this->widget('CCaptcha', [
                              'showRefreshButton' => true,
                              'buttonLabel' => '<i class="fas fa-sync-alt"></i>',
                              'buttonOptions' => [
                                  'style' => 'color: #e4e7ed;!important'
                              ]
                          ]); */?>
                          </div>
                      </div>
                  </div>
                  <?php
/*                      $this->widget('bootstrap.widgets.TbButton', [
                          'context' => 'primary',
                          'block' => true,
                          'buttonType' => 'submit',
                          'label' => Yii::t('controllers', 'user_login_btn_signin'),
                          'htmlOptions' => [
                              'class' => 'mb-3 big-btn'
                          ],
                      ]);
                      echo '<h6 class="login-extlink">';
                          echo CHtml::link(Yii::t('controllers', 'user_login_btn_recovery'), $this->createUrl('/recovery')).'<br />';
                          if(!Yii::app()->settings->get('system', 'enableLockRegister'))
                              echo CHtml::link(Yii::t('controllers', 'user_login_btn_register'), $this->createUrl('/register'));
                      echo '</h6>';
                      $this->endWidget(); unset($form);
                  */?>
                  <hr style="width: 30%" />
                  <center><?php /*$this->widget('LangSelect', ['type' => 'list']); */?></center>
              </div>-->
          </div>
      </div>
  </div>
</div>








