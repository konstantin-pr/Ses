<span style="float:right;">
    <?php if ($this->pageCount): ?>
    <!-- First page link -->
    <?php if (isset($this->previous)): ?>
	  <a class="prev" spa="link" href="<?php echo $this->configUrl.'&page='.$this->first; ?>">
        first
      </a>
    <?php endif; ?>

    <!-- Previous page link -->
    <?php if (isset($this->previous)): ?>
      <a class="prev" spa="link" href="<?php echo $this->configUrl.'&page='.$this->previous; ?>">
        prev
      </a>
    <?php endif; ?>

	<?php foreach ($this->pagesInRange as $page): ?>
		<?php if ($page != $this->current): ?>
			<a spa="link" href="<?php echo $this->configUrl.'&page='.$page; ?>"><?php echo $page; ?></a>
		<?php else: ?>
			<a href="<?php echo $this->configUrl.'&page='.$this->current; ?>" spa="link" class="active"><?php echo $page; ?></a>
		<?php endif; ?>
	<?php endforeach; ?>


    <!-- Next page link -->
    <?php if (isset($this->next)): ?>
      <a class="next" spa="link" href="<?php echo $this->configUrl.'&page='.$this->next; ?>">
        next
      </a>
    <?php endif; ?>

    <!-- Last page link -->
    <?php if (isset($this->next)): ?>
      <a class="next" spa="link" href="<?php echo $this->configUrl.'&page='.$this->last; ?>">
        last
      </a>
    <?php endif; ?>

  <?php endif; ?>
</span>
