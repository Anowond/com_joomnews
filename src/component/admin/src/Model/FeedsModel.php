<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Categories\Categories;

class FeedsModel extends ListModel
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'state', 'a.state',
                'name', 'a.name',
                'url', 'a.url',
                'last_updated', 'a.last_updated',
                'nb_items', 'a.nb_items',
                'comment', 'a.comment',
                'language', 'a.language',
            ];
        }
        parent::__construct($config);
    }

    public function populateState($ordering = 'name', $direction = 'ASC')
    {
        $app = Factory::getApplication();

        $value = $app->input->get('limit', $app->get('list_limit', 0), 'uint');
        $this->setState('list.limit', $value);

        $value = $app->input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $value);

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $publish = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published');
        $this->setState('filter.published', $publish);

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

        $search = $this->getState('filter.search');

        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
            $query->where('(a.name LIKE ' . $search . ')');
        }

        // Filter by published state
        $published = (string) $this->getState('filter.published');

        if (is_numeric($published)) {
            $query->where($db->quoteName('a.state') . ' = :published')
            ->bind(':published', $published, ParameterType::INTEGER);
        } elseif ($published === '') {
            $query->whereIn($db->quoteName('a.state'), array(0, 1));
        }

        $orderCol = $this->state->get('list.ordering', 'a.name');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

    public function publish($pks, $value = 1)
    {
        // Convert the primary keys to an array if not already.
        if (!is_array($pks)) {
            $pks = array($pks);
        }

        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__joomnews_feeds'))
            ->set($db->quoteName('state') . ' = ' . (int) $value)
            ->where($db->quoteName('id') . ' IN (' . implode(',', $pks) . ')');
        $db->setQuery($query);
        $db->execute();

        return true;
    }

    public function unpublish($pks)
    {
        return $this->publish($pks, 0);
    }

    public function archive($pks)
    {
        return $this->publish($pks, 2);
    }

    public function trash($pks)
    {
        return $this->publish($pks, -2);
    }

    public function delete($pks)
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
        ->delete($db->quoteName('#__joomnews_feeds'))
        ->where($db->quoteName('id') . ' IN (' . implode(',', $pks) . ')');
        $db->setQuery($query);
        $db->execute();

        return true;
    }

    // Count the number of items.
    public function getItemCountPerFeed($feedId)
    {
        // Initiate the database object.
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        // Count the number of objects of feeds_items table for the given feed id.
        $query->select('COUNT(*)')
              ->from($db->quoteName('#__joomnews_feeds_items'))
              ->where($db->quoteName('feed_id') . ' = ' . (int) $feedId);

        // Execute the query
        $db->setQuery($query);
        return $db->loadResult();
    }
}
