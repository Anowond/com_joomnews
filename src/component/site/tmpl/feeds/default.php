<?php
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

?>

<form action="">
    <div class="items-limit-box">
        <?= $this->pagination->getLimitBox() ?>  
    </div>
    <div>
        <?php foreach ($this->items as $item) : ?>
            <div class="col card my-3" style="width: 18rem;">
                <img src="<?= $item->thumbnail ?>" class="card-img-top" alt="feed thumbnail">
                <div class="card-body">
                    <h5 class="card-title"><?= $item->name ?></h5>
                    <?php if ($item->comment) :?>
                        <p class="card-text"><?= $item->comment ?></p>
                    <?php else :?>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <?php endif;?>
                    <a href="<?= $item->url ?>" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        <?php endforeach?>
    </div>
    <div><?= $this->pagination->getResultsCounter() ?></div>
    <?= $this->pagination->getListFooter() ?>
    <input type="hidden" name="task" value="feeds">
    <?= HTMLHelper::_('form.token') ?> 
</form>

