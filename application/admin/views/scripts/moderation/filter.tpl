<form action="<?php echo $this->configUrl?>" class="form-dark">
    <label>Filter</label>
    <a spa="link" href="<?php echo $this->configUrl?>" class="button0">All</a>
    <a spa="link" href="<?php echo $this->configUrl . '&filter=0'?>" class="button0 button-white">Pending</a>
    <a spa="link" href="<?php echo $this->configUrl . '&filter=1'?>" class="button0 button-green">Approved</a>
    <a spa="link" href="<?php echo $this->configUrl . '&filter=2'?>" class="button0 button-red">Rejected</a>

    <input name="page" type="hidden" value="<?php echo $this->page ?>" />

    <label>Search
        <input name="search" type="text" value="<?php echo $this->search ?>" />
    </label>

    <button spa="submit" class="button0">Submit</button>
    <label>Found: <?php echo $this->itemsCount?></label>
</form>