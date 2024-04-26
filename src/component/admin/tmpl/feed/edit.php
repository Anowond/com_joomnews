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
                        <?= $this->form->renderField('name') ?>
                        <?= $this->form->renderField('slug') ?>
                        <?= $this->form->renderField('url') ?>
                        <?= $this->form->renderField('thumbnail') ?>
                        <?= $this->form->renderField('owner') ?>
                        <?= $this->form->renderField('last_updated') ?>
                        <?= $this->form->renderField('catid') ?>
                        <?= $this->form->renderField('comment') ?>
                        <?= $this->form->renderField('language') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="<?= $this->item->id ?>">
    <input type="hidden" name="task" value="">
    <?= HTMLHelper::_('form.token') ?>
</form>