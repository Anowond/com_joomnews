<?php
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

?>

<form action="<?= Route::_('index.php?option=com_joomnews&view=feed&layout=edit&id=' . (int) $this->item->id) ?>" method="post" name="adminForm" id="feed-form" class="form-validate">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col">
                       <?= $this->form->renderField('url') ?>
                       <?= $this->form->renderField('language') ?>
                       <?= $this->form->renderField('catid') ?>
                       <?= $this->form->renderField('thumbnail') ?>
                       <?= $this->form->renderField('comment') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="layout" value="make">
    <input type="hidden" name="task" value="">
    <?= HTMLHelper::_('form.token') ?>
</form>
