<?php

namespace HeptaTechnologies\Component\Joomnews\Site\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class FeedsModel extends ListModel
{
    public function populateState($ordering = 'name', $direction = 'ASC')
    {
        $app = Factory::getApplication();

        $value = $app->input->get('limit', $app->get('list_limit', 0), 'uint');
        $this->setState('list.limit', $value);

        $value = $app->input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $value);

        parent::populateState($ordering, $direction);
    }

    public function getListQuery()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select(
            $this->getState(
                'list.select',
                [
                    $db->quoteName('a.id'),
                    $db->quoteName('a.name'),
                    $db->quoteName('a.slug'),
                    $db->quoteName('a.url'),
                    $db->quoteName('a.thumbnail'),
                    $db->quoteName('a.owner'),
                    $db->quoteName('a.last_updated'),
                    $db->quoteName('a.nb_items'),
                    $db->quoteName('a.catid'),
                    $db->quoteName('a.comment'),
                    $db->quoteName('a.language'),
                    $db->quoteName('a.state'),
                ],
            ),
        )->from($db->quoteName('#__joomnews_feeds', 'a'));

        $orderCol = $this->state->get('list.ordering', 'a.name');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }
}
