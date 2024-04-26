<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Toolbar\ToolbarHelper;

class ItemController extends FormController
{
    public function save($key = null, $urlVar = null): bool
    {
        // Appel de la méthode parent
        parent::save($key, $urlVar);

        // Récupération de l'ID de l'item et de l'ID du nouveau feed
        $itemId = Factory::getApplication()->input->getInt('id');

        // Récupération de l'ID du feed
        $data = $this->input->post->get('jform', [], 'array');
        $newFeedId = $data['feed_id'];

        // Appeler la méthode de mise à jour de la relation
        $model = $this->getModel();
        $model->updateFeedRelation($itemId, $newFeedId);

        return true;
    }


    public function myback()
    {
        Session::checkToken() or exit(Text::_('JINVALID_TOKEN'));
        $this->setRedirect(Route::_('index.php?option=com_joomnews&view=items', false));
        return true;
    }

    public function saveAndBack()
    {
        Session::checkToken() or exit(Text::_('JINVALID_TOKEN'));
        $this->save();
        $this->setRedirect(Route::_('index.php?option=com_joomnews&view=items', false), Text::_('COM_JOOMNEWS_ITEM_SAVED_SUCCESSFULLY'));
        return true;
    }

}
