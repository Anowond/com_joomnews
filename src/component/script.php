<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Installer\InstallerScript;

class Com_JoomnewsInstallerScript extends InstallerScript
{
    // Vérification du type d'installation
    public function postflight($type, $parent)
    {
        if ($type == 'install') {
            $this->createDefaultCategory();
        }
    }

    protected function createDefaultCategory()
    {
        // Récupération de la table
        $table = Table::getInstance('Category');
        $table->load(array('extension' => 'com_joomnews', 'title' => Text::_('COM_JOOMNEWS_DEFAULT_CATEGORY')));

        // Vérifie si la catégorie éxiste déjà
        if (!$table->id) {
            $table->title = 'Uncategorised';
            $table->alias = OutputFilter::stringURLSafe('Uncategorised');
            $table->extension = 'com_joomnews';
            $table->description = "";
            $table->published = 1;
            $table->access = 1;
            $table->setLocation(1, 'last-child');
            $table->path = 'uncategorised';
            $table->hits = 0;
            $table->language = "*";

            $params = json_encode([
                'category_layout' => "",
                'image' => "",
                'image_alt' => "",
            ]);
            $table->params = $params;

            $metadata = json_encode([
                'author' => "",
                'robots' => "",
            ]);
            $table->metadata = $metadata;

            // En cas d'erreur lors de l'enregistrement
            if (!$table->store()) {
                Factory::getApplication()->enqueueMessage($table->getError(), 'error');
            }
            $table->rebuildPath($table->id);

        }
    }
}
