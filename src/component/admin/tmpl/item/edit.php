<?php
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

?>

<form action="<?= Route::_('index.php?option=com_joomnews&view=item&layout=edit&id=' . (int) $this->item->id) ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col">
                        <?= $this->form->renderField('name') ?>
                        <?= $this->form->renderField('slug') ?>
                        <?= $this->form->renderField('url') ?>
                        <?= $this->form->renderField('thumbnail') ?>
                        <?= $this->form->renderField('description') ?>
                        <?= $this->form->renderField('feed_id') ?>
                        <?= $this->form->renderField('language') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value="">
    <?= HTMLHelper::_('form.token') ?>
</form>