<?php

use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\PluginHelper;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$states = array(
    '0' => Text::_('JUNPUBLISHED'),
    '1' => Text::_('JPUBLISHED'),
    '2' => Text::_('JARCHIVED'),
    '-2' => Text::_('JTRASHED'),
);
$pluginEnabled = PluginHelper::isEnabled('system', 'languagefilter');
?>

<form action="<?= Route::_('index.php?option=com_joomnews&view=items') ?>" method="post" name="adminForm" id="adminForm">
<div class="row">
    <div class="col-md">
        <?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]) ?>
    </div>
</div>
<?php if (empty($this->items)) : ?>
		<div class="alert alert-info">
			<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
			<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
<?php else : ?>
    <div class="table-responsive">
    <table class="table table-striped">
            <caption><?= Text::_('COM_JOOMNEWS_ITEMS_LIST') ?></caption>
            <thead>
                <tr>
                    <td class="text-center"><?= HTMLHelper::_('grid.checkall') ?></td>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_ITEM_NAME', 'a.name', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_ITEM_URL', 'a.url', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_ITEM_THUMBNAIL', 'a.thumbnail', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_ITEM_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_ITEM_DATE', 'a.date', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_ITEM_ID', 'a.id', $listDirn, $listOrder); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item) : ?>
                    <tr>
                    <td class="text-center">
                            <?= HTMLHelper::_('grid.id', $i, $item->id) ?>
                        </td>
                        <td class="text-center">
                            <?= JHtml::_('jgrid.published', $item->state, $i, 'items.', true, 'cb') ?>
                        </td>
                                <td class="text-center">
                                    <a href="index.php?option=com_joomnews&view=item&layout=edit&id=<?= $item->id ?>"><?= $item->name ?></a>
                                </td>
                                <td class="text-center">
                                    <?= $item->url ?>
                                </td>
                                <td class="text-center">
                                    <?= $item->thumbnail ?>
                                </td>
                                <td class="text-center">
                                    <?= $item->description ?>
                                </td>
                                <td class="text-center">
                                    <?= $item->date ?>
                                </td>
                                <td class="text-center">
                                    <?= $item->id ?>
                                </td>
                    </tr>
                    <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?= $this->pagination->getListFooter() ?>
    <?php endif ?>
    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?= HTMLHelper::_('form.token') ?>
</form>