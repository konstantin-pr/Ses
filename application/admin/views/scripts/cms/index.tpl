<div id="CmsContentBox">
    <div class="lefttab">
        <div><a href="<?php echo $this->configUrl ?>&type=imex" spa="link" target="ContentBox"
                ondata="selectTab(this);">Copy Import/Export</a></div>
    </div>
    <div class="container0" id="ContentBox">
        <?php echo $this->render('imex.tpl'); ?>
    </div>

    <script type="text/javascript">
        function selectTab(item) {
            $('.lefttab:not(.tabdeactive)').addClass('tabdeactive');
            $(item).parent().parent().removeClass('tabdeactive');
        }
    </script>
</div>