<?php if ($this->userIsAdmin): ?>
    <h1 class="header">Export User Data</h1>

	<div class="dark">
            <fieldset  class="form-dark">
            <legend spa="toggle">Export users' data</legend>
            <table ><tbody><tr><th><label>Download CSV file :</label></th><td>
 				<td><a href="<?php echo '/app/cms/' . $this->configUrl . "&type=exportusers&get=1&act=export" ?>" class="button0 button-red">export</a></td>

            </td></tr></tbody></table>
            </fieldset>
    </div>


    <?php if ($this->error) { ?>
    <div class="container" style="border:2px solid #FF0000;padding:5px;font-weight:bold;">
        <?php echo $this->error; ?>
    </div>
    <br>
    <?php } ?>


<?php endif; ?>