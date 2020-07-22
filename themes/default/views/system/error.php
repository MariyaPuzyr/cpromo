<div class="error-body">
  <div class="content-wrapper d-flex align-items-center text-center error-page bg-primary h-100">
    <div class="row flex-grow">
      <div class="col-lg-11 mx-auto text-white">
        <div class="row align-items-center d-flex flex-row">
          <div class="col-lg-6 text-lg-right pr-lg-4">
            <h1 class="display-1 mb-0"><?php echo $code; ?></h1>
          </div>
          <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
            <h3 class="font-weight-light"><?php echo CHtml::encode($message); ?></h3>
          </div>
        </div>
      </div>
    </div>



      <?php #$this->pageTitle = Yii::app()->name . ' - Error';?>
 <!--   <h2>Error <?php /*echo $code; */?></h2>

    <div class="error">
        <?php /*echo CHtml::encode($message); */?>
    </div>-->
  </div>
</div>