<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive'); ?>

<form action="<?php echo JRoute::_('index.php?option=com_qlue404&view=custom&layout=edit&id='. (int)$this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	
	<div class="row-fluid">
		<div class="span8 form-horizontal">
			<fieldset class="adminform">
		        <legend><?php echo JText::_( 'COM_QLUE404_DETAILS' ); ?></legend>

				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('id'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('title'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('title'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('error'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('error'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('published'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('published'); ?>
					</div>
				</div>
				
				<div class="control-group">
		        	<div class="control-label">
						<?php echo $this->form->getLabel('description'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('description'); ?>
					</div>
				</div>
				
		    </fieldset>
		</div>
    
	    <div class="span4 form-horizontal">

			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_QLUE404_PANEL_PARAMS'); ?></legend>

				<?php foreach ($this->form->getFieldset('params') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>

			</fieldset>
			
			<fieldset class="adminform">
				<legend><?php echo JText::_('HTACCESS_EDIT'); ?></legend>
				<?php echo JText::_('QLUE404_EXTEND_DESC'); ?>
				<?php echo JText::_('QLUE404_EXTEND_MAIN'); ?>
				<br />
				<?php $path = basename(JPATH_ROOT); ?>
				<?php echo JText::sprintf('QLUE404_EXTEND_SUB', $path, $path, $path); ?>
			</fieldset>
		</div>

	</div>
    
    <div>
    	<?php foreach ($this->form->getFieldset('hidden') as $field) : ?>
			<?php echo $field->input; ?>
		<?php endforeach; ?>
    	<input type="hidden" name="task" value="custom.edit" />
    	<input type="hidden" name="layout" value="edit" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
        
</form>