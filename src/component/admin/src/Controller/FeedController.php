<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Http\HttpFactory;

class FeedController extends FormController
{
    public function make()
    {
        // Rediriger vers le layout make
        $this->setRedirect(Route::_('index.php?option=com_joomnews&view=feed&layout=make&form=make', false));
    }

    public function save($key = null, $urlVar = null)
    {
        // Récupérer les données du formulaire
        $app = Factory::getApplication();
        $data = $app->input->get('jform', [], 'array');

        // Appeler la méthode saveFeedFromXML depuis le modéle
        $model = $this->getModel();
        // Gestion des eventuelles erreurs
        if (!$model->saveFeedFromXML($data)) {
            $app->enqueueMessage(Text::_('COM_JOOMNEWS_SAVE_FEED_ERROR'), 'error');
            return false;
        }

        // Redirection en cas de succés
        $this->setRedirect(Route::_('index.php?option=com_joomnews&view=feed&layout=make&form=make', false), Text::_('COM_JOOMNEWS_FEED_SAVED_SUCCESSFULLY'));
        return true;
    }

    public function saveAndBack()
    {
        $this->save();
        $this->setRedirect(Route::_('index.php?option=com_joomnews&view=feeds', false), Text::_('COM_JOOMNEWS_FEED_SAVED_SUCCESSFULLY'));
        return true;
    }
}
