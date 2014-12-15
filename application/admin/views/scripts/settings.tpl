<div id="settings_container">
	<?php if($this->error) : ?>
	<div>
		<?php echo $this->error; ?>
	</div>
	<?php endif; ?>
	<form action="<?php echo $this->configUrl . '&type=set'; ?>">
		<?php foreach($this->form->getElements() as $element) : ?>
		<?php echo $element->render(); ?>
		<?php endforeach; ?>
		<div class="container1 dark">
			<button spa="submit" class="button0">Save</button>
			<span class="or">or</span>
			<button spa="cancel" class="button0">Cancel</button>
		</div>
	</form>
</div>