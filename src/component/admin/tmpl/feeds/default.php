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

<form action="<?= Route::_('index.php?option=com_joomnews&view=feeds') ?>" method="post" name="adminForm" id="adminForm">
<div class="row">
    <div class="col-md">
        <?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]) ?>
    </div>
    <?php if (empty($this->items)) : ?>
		<div class="alert alert-info">
			<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
			<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
</div>
    <div class="table-responsive">
        <table class="table table-striped">
            <caption><?= Text::_('COM_JOOMNEWS_FEEDS_LIST') ?></caption>
            <thead>
                <tr>
                    <th class="text-center"><?= HTMLHelper::_('grid.checkall') ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_FEEDS_LIST_NAME', 'a.name', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_FEEDS_LIST_URL', 'a.url', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_FEEDS_LIST_LAST_UPDATED', 'a.last_updated', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_FEEDS_LIST_NB_ITEMS', 'a.nb_items', $listDirn, $listOrder); ?></th>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_FEEDS_LIST_COMMENT', 'a.comment', $listDirn, $listOrder); ?></th>
                    <?php if ($pluginEnabled) : ?>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_FEEDS_LIST_LANGUAGE', 'a.language', $listDirn, $listOrder); ?></th>
                    <?php endif; ?>
                    <th class="text-center"><?php echo HTMLHelper::_('searchtools.sort', 'COM_JOOMNEWS_FEEDS_LIST_ID', 'a.id', $listDirn, $listOrder); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item) : ?>
                    <!-- Assign the count result retrived from getItemCountPerFeed method to nb_items -->
                    <?php $item->nb_items = $this->getModel()->getItemCountPerFeed($item->id); ?>
                    <tr>
                        <td class="text-center">
                            <?= HTMLHelper::_('grid.id', $i, $item->id) ?></td>
                        <td class="text-center">
                            <?= JHtml::_('jgrid.published', $item->state, $i, 'feeds.', true, 'cb') ?></td>
                            <td class="text-center">
                                <a href="index.php?option=com_joomnews&view=items&id=<?= $item->id ?>"><?= $item->name ?></a></td>
                                <td class="text-center">
                                    <?= $item->url ?></td>
                                    <td class="text-center">
                                        <?= $item->last_updated ?></td>
                                        <td class="text-center">
                                            <?= $item->nb_items ?></td>
                                            <td class="text-center">
                                                <?= $item->comment ?></td>
                                                <?php if ($pluginEnabled) : ?>
                                                <td class="text-center">
                                                    <!-- Verify if the MultiLanguage plugin is activated and display the flag gif related to the item language -->
                                                        <?php if (!empty($item->language)) {
                                                            $item->language_image = strtolower($item->language);
                                                            $item->language_image = str_replace('-', '_', $item->language_image);
                                                            $item->language_title = $item->language;
                                                        } else {
                                                            $item->language_image = '';
                                                            $item->language_title = $item->language;
                                                        }?>
                                <?php echo HTMLHelper::_('image', 'mod_languages/' . $item->language_image . '.gif', '', ['class' => 'me-1'], true) ?></td>
                                <?php endif ?>
                                <td class="text-center">
                                    <?= $item->id ?></td>
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
