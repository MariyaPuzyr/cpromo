<?php
/**
 * @var $this GalleryManager
 * @var $model GalleryPhoto
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
?>
<?php echo CHtml::openTag('div', $this->htmlOptions); ?>
    <!-- Gallery Toolbar -->
    <div class="btn-toolbar gform">
        <span class="btn btn-success fileinput-button">
            <i class="icon-plus icon-white"></i>
            <?php echo Yii::t('galleryManager.main', 'Add…');?>
            <input type="file" name="image" class="afile" accept="image/*" multiple="multiple"/>
        </span>

        <div class="btn-group">
            <label class="btn">
                <input type="checkbox" style="margin: 0;" class="select_all"/>
                <?php echo Yii::t('galleryManager.main', 'Select all');?>
            </label>
            <span class="btn disabled edit_selected"><i class="fas fa-pencil-alt"></i> <?php echo Yii::t('galleryManager.main', 'Edit');?></span>
            <span class="btn disabled remove_selected"><i class="fas fa-trash"></i> <?php echo Yii::t('galleryManager.main', 'Remove');?></span>
        </div>
    </div>
    <hr/>
    <!-- Gallery Photos -->
    <div class="sorter">
        <div class="images"></div>
        <br style="clear: both;"/>
    </div>

    <!-- Modal window to edit photo information -->
    <style>
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal-dialog {
            margin: 2px auto;
            z-index: 1100 !important;
        }
    </style>
    <div class="modal" tabindex="-1" role="dialog" id="editor-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary save-changes"><?php echo Yii::t('galleryManager.main', 'Save changes')?></a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo Yii::t('galleryManager.main', 'Close')?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="overlay">
        <div class="overlay-bg">&nbsp;</div>
        <div class="drop-hint">
            <span class="drop-hint-info"><?php echo Yii::t('galleryManager.main', 'Drop Files Here…')?></span>
        </div>
    </div>
    <div class="progress-overlay">
        <div class="overlay-bg">&nbsp;</div>
        <!-- Upload Progress Modal-->
        <div class="modal progress-modal">
            <div class="modal-header">
                <h3><?php echo Yii::t('galleryManager.main', 'Uploading images…')?></h3>
            </div>
            <div class="modal-body">
                <div class="progress progress-striped active">
                    <div class="bar upload-progress"></div>
                </div>
            </div>
        </div>
    </div>
<?php echo CHtml::closeTag('div'); ?>