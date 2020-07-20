<div class="col-md-2 mb-3 photo">
    <?= CHtml::image($data['preview'], '', ['style' => 'width: 100%; height: 87px!important']);?>
    <span class="text-muted small text-justify"><?= $data['name'] ? $data['name'].' # '.$data['description'] : $data['description']; ?></span>
    <hr class="mb-0 mt-0"/>
    <div class="text-right">
        <span class="editPhoto small text-primary text-link"><i class="fas fa-pencil-alt"></i></span>
        <span class="deletePhoto small text-primary text-link"><i class="fas fa-trash"></i></span>
    </div>
</div>