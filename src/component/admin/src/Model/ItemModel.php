<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

class ItemModel extends AdminModel
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm(
            'com_joomnews.item',
            'item',
            [
                'control' => 'jform',
                'load_data' => $loadData,
            ],
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $app = Factory::getApplication();
        $data = $app->getUserState(
            'com_joomnews.edit.item.data',
            [],
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    // Mettre à jour un enregistrement d'item
    public function updateFeedRelation($itemId, $newFeedId)
    {
        // Ajouter la nouvelle relation
        $db = Factory::getContainer()->get('db');
        $query = $db->getQuery(true);
        $query->update($db->quoteName('#__joomnews_feeds_items'))
            ->set($db->quoteName('feed_id') . '=' . (int) $newFeedId)
            ->where($db->quoteName('id') . '=' . (int) $itemId);
        $db->setQuery($query);
        $db->execute();

    }
}
