<?php echo $this->render('css.tpl')?>
<?php echo $this->render('js.tpl')?>
<?php echo $this->partial('messages.tpl', array('messages' => $this->messages)) ?>
<?php echo $this->render('filter.tpl', array('columns' => $this->columns)) ?>
<?php echo $this->paginationControl($this->items, 'Sliding', 'pagination.tpl', array('url' => $this->configUrl . '&search=' . $this->search . '&sort=' . $this->sort . '&filter=' . $this->filter)) ?>

<?php foreach ($this->items as $this->item): ?>
    <?php echo $this->render('item.show.tpl') ?>
<?php endforeach ?>

<?php echo $this->paginationControl($this->items, 'Sliding', 'pagination.tpl', array('url' => $this->configUrl . '&search=' . $this->search . '&sort=' . $this->sort . '&filter=' . $this->filter)) ?>
