<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\MVC\Model\AdminModel;

class FeedModel extends AdminModel
{
    // Méthode de chargement du formulaire en fonction du $form venant du template
    public function getForm($data = [], $loadData = true)
    {
        $app = Factory::getApplication();
        $layout = $app->input->getString('layout');

        if ($layout == 'make') {
            $form = $this->loadForm(
                'com_joomnews.make',
                'make',
                [
                    'control' => 'jform',
                    'load_data' => $loadData,
                ],
            );
        } else {
            $form = $this->loadForm(
                'com_joomnews.feed',
                'feed',
                [
                    'control' => 'jform',
                    'load_data' => $loadData,
                ],
            );
        }

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function loadFormData()
    {
        $app = Factory::getApplication();
        $data = $app->getUserState(
            'com_joomnews.edit.feed.data',
            [],
        );

        if (empty($data)) {
            $data = $this->getItem();
            // Réorganisation de la donnée du thumbnail pour l'affichage
            if ($data->thumbnail) {
                $thumbnail = explode('#', $data->thumbnail);
                $data->thumbnail = $thumbnail[0];
            }
        }

        return $data;
    }

    public function save($data)
    {

        // unset tags
        unset($data['tags']);

        // récupération d'une instance de la BDD
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Vérification si l'on met à jour un enregistrement éxistant ou si l'on en créé un nouveau
        if (empty($data['id'])) {
            // Création d'un nouvel enregistrement
            $columns = array_keys($data);
            $values = array_values($data);
            $quotedValues = array_map(function ($value) use ($db) {
                return $db->quote($value);
            }, $values);

            $query->insert($db->quoteName('#__joomnews_feeds'))
                  ->columns($db->quoteName($columns))
                  ->values(implode(',', $quotedValues));
        } else {
            // Mise à jour d'un enregistrement existant
            $conditions = $db->quoteName('id') . ' = ' . $db->quote($data['id']);
            $query->update($db->quoteName('#__joomnews_feeds'))
                  ->set($this->arrayToQuerySet($data, $db))
                  ->where($conditions);
        }

        // Execution de la requête
        $db->setQuery($query);
        $db->execute();

        return true;
    }

    protected function arrayToQuerySet(array $data, $db)
    {
        $set = [];
        foreach ($data as $key => $value) {
            // Skip the 'id' field as it's used in the WHERE clause and not in SET
            if ($key === 'id') {
                continue;
            }
            $set[] = $db->quoteName($key) . ' = ' . $db->quote($value);
        }
        return implode(', ', $set);
    }

    // Enregistre un flux à partir du formulaire make.xml
    public function saveFeedFromXML($data)
    {
        // Récupération d'un éventuel id d'un flux éxistant à partir de l'input hidden 'id' du formulaire
        $app = Factory::getApplication();
        $id = $app->input->getInt('id');

        // HttpFactory récupére le contenu XML
        $url = HttpFactory::getHttp();
        $response = $url->get($data['url']);
        if ($response->code != 200) {
            throw new Exception(Text::_('COM_JOOMNEWS_ERROR_FEED_NOT_FOUND'));
        }
        $xmlContent = simplexml_load_string($response->body);

        // Vérification si on a affaire à un Flux RSS classique ou a un flux venant de Youtube
        if (isset($xmlContent->channel)) {

            $tabUrl = explode('/', $xmlContent->channel->link);
            $date = $xmlContent->channel->lastBuildDate;
            $formatedDate = date_format(date_create($date), 'Y-m-d');

            $feedData = [
                'name' => $tabUrl[2],
                'slug' => OutputFilter::stringURLSafe((string) $tabUrl[2]),
                'url' => $data['url'],
                'thumbnail' => $data['thumbnail']['imagefile'],
                'owner' => $tabUrl[2],
                'last_updated' => $formatedDate,
                'nb_items' => count($xmlContent->channel->item),
                'catid' => $data['catid'],
                'comment' => $data['comment'],
                'language' => $data['language'],
            ];
        } else {
            $date = $xmlContent->published;
            $dateFormatted = date_format(date_create($date), 'Y-m-d');

            $feedData = [
                'name' => (string) $xmlContent->author->name,
                'slug' => OutputFilter::stringURLSafe((string) $xmlContent->author->name),
                'url' => $data['url'],
                'thumbnail' => $data['thumbnail']['imagefile'],
                'owner' => (string) $xmlContent->author->name,
                'last_updated' => $dateFormatted,
                'nb_items' => count($xmlContent->entry),
                'catid' => $data['catid'],
                'comment' => $data['comment'],
                'language' => $data['language'],
            ];
        }

        // Enregistrement en BDD du feed
        $table = $this->getTable('Feed', 'JoomnewsTable');

        // Si $id n'est pas null, mettre à jour le feed
        if ($id != null && !$table->load($id)) {
            throw new Exception(Text::_('COM_JOOMNEWS_ERROR_FEED_ID_NOT_FOUND'));
        }

        // Appliquer les données du feed
        if (!$table->bind($feedData)) {
            throw new Exception($table->getError());
        }

        if (!$table->check()) {
            throw new Exception($table->getError());
        }

        if (!$table->store()) {
            throw new Exception($table->getError());
        }

        // Si nouveau feed, récupérer l'id aprés enregistrement et enregistrer les items.
        if ($id == 0) {
            $feedId = $table->id;
            $this->saveItemsFromXMLFeed($xmlContent, $feedId, $data);
        }

        return true;
    }

    // Enregsitre les items d'un flux à partir du formulaire make.xml
    public function saveItemsFromXMLFeed($XMLContent, $feedId, $data)
    {
        // Vérification si on a affaire à un Flux RSS classique ou a un flux venant de Youtube
        if ($XMLContent->channel) {
            for($i = 0; $i < count($XMLContent->channel->item); $i++) {
                $items[] = $XMLContent->channel->item[$i];
            }
            foreach ($items as $item) {

                $formatedDate = date_format(date_create($item->pubDate), 'Y-m-d');

                $itemData = [
                    'name' => (string) $item->title,
                    'slug' => OutputFilter::stringURLSafe((string) $item->title),
                    'url' => (string) $item->link,
                    'thumbnail' => $data['thumbnail']['imagefile'],
                    'description' => (string) $item->description,
                    'date' => $formatedDate,
                    'feed_id' => $feedId,
                ];

                $databaseData[] = $itemData;
                $itemData = [];
            }
        } else {
            for($i = 0; $i < count($XMLContent->entry); $i++) {
                $items[] = $XMLContent->entry[$i];
            }
            foreach ($items as $item) {

                $mediaGroup = $item->children('media', true)->group;
                $formatedDate = date_format(date_create($item->published), 'Y-m-d');

                $itemData = [
                    'name' => (string) $item->title,
                    'slug' => OutputFilter::stringURLSafe((string) $item->title),
                    'url' => (string) $mediaGroup->content->attributes()->url,
                    'thumbnail' => (string) $mediaGroup->thumbnail->attributes()->url,
                    'decription' => (string) $mediaGroup->description,
                    'date' => $formatedDate,
                    'feed_id' => $feedId,
                ];

                $databaseData[] = $itemData;
                $itemData = [];
            }
        }

        // dd($databaseData);
        // Enregistrement en BDD des items
        foreach ($databaseData as $items) {
            // Récupéraion de la table des items
            $table = $this->getTable('FeedsItems', 'JoomnewsTable');
            // Lier les données de l'item à la table
            if (!$table->bind($items)) {
                throw new Exception($table->getError());
            }
            // Vérfier les données de l'item avant enregistrement
            if (!$table->check()) {
                throw new Exception($table->getError());
            }
            // Stocker les données de l'item dans la BDD
            if (!$table->store()) {
                throw new Exception($table->getError());
            }
        }

        return true;
    }
}
