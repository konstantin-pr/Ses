<?php if ($this->userIsAdmin): ?>
    <h1 class="header">Gifts Available:</h1>
 
    <br/>

    <div>
        <table>
            <tbody>
	        <tr>
    	    <td><label>Winning Time</label></td>
    		    <td><label>Win Time</label></td>
            <td><label>Winner's Email</label></td>

            </tr>
            <?php foreach($this->gifts as $gift): ?>
            <tr>
            <td><?php echo $gift['winning_time']->format('Y-m-d H:i:s a') ?></td>
		    <td><?php 
            if ($gift['user']['date_win']){
                echo $gift['user']['date_win']->format('Y-m-d H:i:s a'); 
            }
            else {
                echo "Available";
            }
            ?></td>
            <td><?php echo $gift['user']['email'] ?></td>
            </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h1 class="header">

    <script type="text/javascript">
        var copy_import = {
            "result": function (data) {
                console.log(data);
            }
        }
    </script>

    <script id="dialogcontent" type="text/tmpl">

<?php endif; ?>