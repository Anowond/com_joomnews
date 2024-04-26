<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\View\Feeds;

use Joomla\Component\Content\Administrator\Helper\ContentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Toolbar\ToolbarFactoryInterface;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
    public $filterForm;
    public $state;
    public $items = [];
    public $pagination;
    public $activeFilters = [];

    public function display($tpl = null): void
    {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode('\n', $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance('toolbar');
        ToolbarHelper::title(Text::_('COM_JOOMNEWS_PAGE_TITLE'));
        $canDo = ContentHelper::getActions('com_joomnews');

        if ($canDo->get('core.create')) {
            ToolbarHelper::custom('feed.make', 'icon-new', 'icon-new', Text::_('COM_JOOMNEWS_FEEDS_MAKE'), false);
        }

        if ($canDo->get('core.edit.state')) {

            ToolbarHelper::editList('feed.edit');

            $dropdown = $toolbar->dropdownButton('status-group')
                ->text('JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);

            $childbar = $dropdown->getChildToolbar();

            $childbar->publish('feeds.publish')->listCheck(true);

            $childbar->unpublish('feeds.unpublish')->listCheck(true);

            $childbar->archive('feeds.archive')->listCheck(true);

            $childbar->trash('feeds.trash')->listCheck(true);

        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            $toolbar->delete('feeds.delete')
                ->text('JTOOLBAR_EMPTY_TRASH')
                ->message('GLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }

        if ($canDo->get('core.create')) {
            $toolbar->preferences('com_joomnews');
        }

    }

}
