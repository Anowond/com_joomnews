<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\View\Items;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

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

        if ($canDo->get('core.edit.state')) {

            ToolbarHelper::editList('item.edit');

            $dropdown = $toolbar->dropdownButton('status-group')
                ->text('JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);

            $childbar = $dropdown->getChildToolbar();

            $childbar->publish('items.publish')->listCheck(true);

            $childbar->unpublish('items.unpublish')->listCheck(true);

            $childbar->archive('items.archive')->listCheck(true);

            $childbar->trash('items.trash')->listCheck(true);

        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            $toolbar->delete('items.delete')
                ->text('JTOOLBAR_EMPTY_TRASH')
                ->message('GLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }

        if ($canDo->get('core.create')) {
            ToolbarHelper::back(href:'index.php?option=com_joomnews&view=feeds');
        }

        if ($canDo->get('core.create')) {
            $toolbar->preferences('com_joomnews');
        }

    }
}
