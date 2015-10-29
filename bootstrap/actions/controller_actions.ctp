<?php $i18nDomain = (isset($plugin) && !empty($plugin)) ? strtolower(chop($plugin, '.')) : 'default'; ?>
	public function <?php echo $admin ?>index() {
		$this-><?php echo $currentModelName ?>->recursive = 0;
		$this->set('<?php echo $pluralName ?>', $this->paginate());
	}

	public function <?php echo $admin ?>view($id = null) {
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException(__d('croogo', 'Invalid %s', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')));
		}
		$options = array('conditions' => array('<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id));
		$this->set('<?php echo $singularName; ?>', $this-><?php echo $currentModelName; ?>->find('first', $options));
	}

<?php $compact = array(); ?>
	public function <?php echo $admin ?>add() {
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->saveAssociated($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__d('croogo', '%s has been saved', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')), 'default', array('class' => 'success'));
				$redirectTo = array('action' => 'index');
				if (isset($this->request->data['apply'])) {
					$redirectTo = array('action' => 'edit', $this-><?php echo $currentModelName; ?>->id);
				}
				if (isset($this->request->data['save_and_new'])) {
					$redirectTo = array('action' => 'add');
				}
				return $this->redirect($redirectTo);
<?php else: ?>
				$this->flash(__d('croogo', '%s has been saved', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__d('croogo', '%s could not be saved. Please, try again.', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')), 'default', array('class' => 'error'));
<?php endif; ?>
			}
		}
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(" . join(', ', $compact) . "));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
	public function <?php echo $admin; ?>edit($id = null) {
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException(__d('croogo', 'Invalid %s', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this-><?php echo $currentModelName; ?>->saveAssociated($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__d('croogo', '%s has been saved', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')), 'default', array('class' => 'success'));
				$redirectTo = array('action' => 'index');
				if (isset($this->request->data['apply'])) {
					$redirectTo = array('action' => 'edit', $id);
				}
				if (isset($this->request->data['save_and_new'])) {
					$redirectTo = array('action' => 'add');
				}
				return $this->redirect($redirectTo);
<?php else: ?>
				$this->flash(__d('croogo', '%s has been saved', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__d('croogo', '%s could not be saved. Please, try again.', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')), 'default', array('class' => 'error'));
<?php endif; ?>
			}
		} else {
			$options = array('conditions' => array('<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id));
			$this->request->data = $this-><?php echo $currentModelName; ?>->find('first', $options);
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(" . join(', ', $compact) . "));\n";
		endif;
	?>
	}

	public function <?php echo $admin; ?>delete($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			throw new NotFoundException(__d('croogo', 'Invalid %s', __d('<?php echo $i18nDomain; ?>', '<?php echo strtolower($singularHumanName); ?>')));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this-><?php echo $currentModelName; ?>->delete()) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(__d('croogo', '%s deleted', __d('<?php echo $i18nDomain; ?>', '<?php echo ucfirst(strtolower($singularHumanName)); ?>')), 'default', array('class' => 'success'));
			return $this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash((__d('croogo', '%s deleted', __d('<?php echo $i18nDomain; ?>', '<?php echo ucfirst(strtolower($singularHumanName)); ?>')), array('action' => 'index'));
<?php endif; ?>
		}
<?php if ($wannaUseSession): ?>
		$this->Session->setFlash(__d('croogo', '%s was not deleted', __d('<?php echo $i18nDomain; ?>', '<?php echo ucfirst(strtolower($singularHumanName)); ?>')), 'default', array('class' => 'error'));
<?php else: ?>
		$this->flash(__d('croogo', '%s was not deleted', __d('<?php echo $i18nDomain; ?>', '<?php echo ucfirst(strtolower($singularHumanName)); ?>')), array('action' => 'index'));
<?php endif; ?>
		return $this->redirect(array('action' => 'index'));
	}
