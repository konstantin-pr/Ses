<?php if ($this->pageCount > 1): ?>

<div class="cf-item-pages">
    <span style="float:left;">Pages</span>
    <span style="float:right;">
        <!-- First link -->
        <?php if (isset($this->first) && $this->current !== $this->first): ?>
        <a spa="link" href="<?php echo $this->url . '&page=' . $this->first; ?>" class="next">First</a>
        <?php endif; ?>

        <!-- Previous link -->
        <?php if (isset($this->previous)): ?>
        <a spa="link" href="<?php echo $this->url . '&page=' . $this->previous; ?>" class="next">Prev</a>
        <?php endif; ?>

        <!-- Pages -->
        <?php if ($this->pageCount > 1): ?>
        <?php foreach ($this->pagesInRange as $page): ?>
        <a spa="link" href="<?php echo $this->url . '&page=' . $page; ?>"<?php if ($page == $this->current) echo ' class="active"' ?>><?php echo $page; ?></a>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Next link -->
        <?php if (isset($this->next)): ?>
        <a spa="link" href="<?php echo $this->url . '&page=' . $this->next; ?>" class="next">Next</a>
        <?php endif; ?>

        <!-- Last link -->
        <?php if (isset($this->last) && $this->current !== $this->last): ?>
        <a spa="link" href="<?php echo $this->url . '&page=' . $this->last; ?>" class="next">Last</a>
        <?php endif; ?>
    </span>
</div>

<?php endif; ?>