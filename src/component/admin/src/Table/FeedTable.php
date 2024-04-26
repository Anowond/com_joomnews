<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\Table;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

class FeedTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__joomnews_feeds', 'id', $db);
    }
}
