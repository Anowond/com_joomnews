<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\View\Feed;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    public $state;
    public $form;
    public $item;

    public function display($tpl = null): void
    {
        $this->form = $this->get('Form');
        $this->state = $this->get('State');
        $this->item = $this->get('Item');

        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    public function addToolbar()
    {
        Factory::getApplication()->input->set('hidemainmenu', 1);
        $canDo = ContentHelper::getActions('com_joomnews');
        $isNew = ($this->item->id == 0);
        $toolbar = Toolbar::getInstance();
        if ($isNew) {
            ToolbarHelper::title(Text::_('COM_JOOMNEWS_CREATE_TITLE'));
        } else {
            ToolbarHelper::title(Text::_('COM_JOOMNEWS_FEED_TITLE'));
        }

        if ($canDo->get('core.create')) {
            if ($isNew) {
                $toolbar->apply('feed.save');
            } else {
                $toolbar->apply('feed.apply');
            }
            $toolbar->save('feed.saveAndBack');
        }
        $toolbar->cancel('feed.cancel', 'JTOOLBAR_CLOSE');
    }
}
