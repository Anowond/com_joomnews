<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\View\Item;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Toolbar\ToolbarFactoryInterface;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    public $form;
    public $state;
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
        ToolbarHelper::title(Text::_('COM_JOOMNEWS_ITEM_TITLE'));

        if ($canDo->get('core.create')) {
            if ($isNew) {
                $toolbar->apply('item.save');
            } else {
                $toolbar->apply('item.apply');
            }
            $toolbar->save('item.save');
        }
        $toolbar->cancel('item.cancel', 'JTOOLBAR_CLOSE');
    }


}
