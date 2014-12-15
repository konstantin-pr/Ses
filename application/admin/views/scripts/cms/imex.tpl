<?php if ($this->userIsAdmin): ?>
    <h1 class="header">Generate copy file</h1>
    <div>
        <a href="<?php echo '/app/cms/' . $this->configUrl . "&type=generate" ?>" class="button1" spa="link" target="generateResult">generate</a>
        <span id="generateResult"></span>
    </div>
    <br/>
    <h1 class="header">
        Import
    </h1>
    <div>
        <button spa="dialog" width='800' href="dialogcontent://" class="button1">Inport</button>
    </div>
    <br/>
    <h1>
        Export
    </h1>
    <div>
        <a href="<?php echo '/app/cms/' . $this->configUrl . "&type=export&get=1" ?>" class="button1">export</a>
    </div>

    <?php if(file_exists($this->app_path.'/application/tmp/backup.json')):?>
        <br/>
        <h1>
            Restore from backups
        </h1>
        <div>
            <a href="<?php echo '/app/cms/' . $this->configUrl . "&type=showBackups" ?>" spa="dialog" class="button1" width='800'>restore</a>
        </div>
    <?php endif;?>

    <script type="text/javascript">
        var copy_import = {
            "result": function (data) {
                console.log(data);
            }
        }
    </script>

    <script id="dialogcontent" type="text/tmpl">

        <form action="<?php echo $this->configUrl . "&type=compare" ?>" type="POST" class='form-light' target="_self">
            <h1>Import</h1>
            <fieldset>
                <legend></legend>
                <table>
                    <tbody>
                    <tr>
                        <th><label>File</label><i>Select json file</i></th>
                        <td><a href="<?php echo $this->configUrl . "&type=upload&get=1" ?>" id="import" spa="file" >import</a></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><button class='button1' spa='submit'>Next -></button></td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>

        </form>




    </script>
<?php endif; ?>