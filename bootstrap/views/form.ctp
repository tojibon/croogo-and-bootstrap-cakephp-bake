<?php
$i18nDomain = $plugin ? Inflector::underscore($plugin) : 'default';


$is_admin = strpos($action, 'admin');

if ($is_admin === false) {
    //Front end
?>
<div class="<?php echo $pluralVar; ?> form">
<?php echo "<?php echo \$this->Form->create('{$modelClass}'); ?>\n"; ?>
	<fieldset>
		<legend><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></legend>
<?php
		echo "\t<?php\n";
		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field === $primaryKey) {
				continue;
			} elseif (!in_array($field, array('created', 'modified', 'updated', 'created_by', 'updated_by'))) {
                
                $is_date_field = strpos($field, 'date');
                if ($is_date_field === false) {
                    $is_date_field = strpos($field, 'date_time');
                    if ($is_date_field === false) {
                        $is_date_field_class = '';
                        $is_date_field_type = '';
                    } else {
                        $is_date_field_class = ' datepickertime';
                        $is_date_field_type = ", 'type'=>'text'";
                    }
                } else {
                    $is_date_field_class = ' datepicker';
                    $is_date_field_type = ", 'type'=>'text'";
                }
                
                echo "\t\techo \$this->Form->input('{$field}', array('class'=>'form-control {$is_date_field_class}' {$is_date_field_type}, 'div'=>array('class'=>'form-group')));\n";
			}
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
                
                $is_date_field = strpos($assocName, 'date');
                if ($is_date_field === false) {
                    $is_date_field = strpos($assocName, 'date_time');
                    if ($is_date_field === false) {
                        $is_date_field_class = '';
                        $is_date_field_type = '';
                    } else {
                        $is_date_field_class = ' datepickertime';
                        $is_date_field_type = ", 'type'=>'text'";
                    }
                } else {
                    $is_date_field_class = ' datepicker';
                    $is_date_field_type = ", 'type'=>'text'";
                }
                
				echo "\t\techo \$this->Form->input('{$assocName}', array('class'=>'form-control {$is_date_field_class}' {$is_date_field_type}, 'div'=>array('class'=>'form-group')));\n";
			}
		}
		echo "\t?>\n";
?>
	</fieldset>
<?php
    
    echo "<?php\n";
    
    echo "echo \$this->Form->button(__d('croogo', 'Apply'), array('name' => 'apply', 'class'=>'btn btn-primary'));";
    echo "echo '&nbsp;';";
	echo "echo \$this->Form->button(__d('croogo', 'Save'), array('button' => 'primary', 'class'=>'btn btn-primary'));";
    echo "echo '&nbsp;';";
	echo "echo \$this->Form->button(__d('croogo', 'Save & New'), array('button' => 'success', 'name' => 'save_and_new', 'class'=>'btn btn-primary'));";
    echo "echo '&nbsp;';";
	echo "echo \$this->Html->link(__d('croogo', 'Cancel'), array('action' => 'index'), array('button' => 'danger', 'class'=>'btn btn-danger'));";
    echo "echo '&nbsp;';";
	echo "echo \$this->Form->end();\n";
    
	echo "?>\n";
?>
</div>
<div class="actions">
	<h3><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
	<ul>

<?php if (strpos($action, 'add') === false): ?>
		<li><?php echo "<?php echo \$this->Form->postLink(__('Delete'), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), array('confirm' => __('Are you sure you want to delete # %s?', \$this->Form->value('{$modelClass}.{$primaryKey}')))); ?>"; ?></li>
<?php endif; ?>
		<li><?php echo "<?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index')); ?>"; ?></li>
<?php
		$done = array();
		foreach ($associations as $type => $data) {
			foreach ($data as $alias => $details) {
				if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
					echo "\t\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), array('controller' => '{$details['controller']}', 'action' => 'index')); ?> </li>\n";
					echo "\t\t<li><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add')); ?> </li>\n";
					$done[] = $details['controller'];
				}
			}
		}
?>
	</ul>
</div>
<?php
    
    
    
    
} else {
$header = <<<EOF
<?php
\$this->viewVars['title_for_layout'] = __d('$i18nDomain', '$pluralHumanName');
\$this->extend('/Common/admin_edit');

\$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('$i18nDomain', '${pluralHumanName}'), array('action' => 'index'));

if (\$this->action == 'admin_edit') {
	\$this->Html->addCrumb(\$this->request->data['$modelClass']['$displayField'], '/' . \$this->request->url);
	\$this->viewVars['title_for_layout'] = __d('$i18nDomain', '$pluralHumanName') . ': ' . \$this->request->data['$modelClass']['$displayField'];
} else {
	\$this->Html->addCrumb(__d('croogo', 'Add'), '/' . \$this->request->url);
}

\$this->append('form-start', \$this->Form->create('{$modelClass}'));


EOF;
echo $header;

$primaryTab = strtolower(Inflector::slug($singularHumanName, '-'));

echo "\$this->append('tab-heading');\n";
	echo "\techo \$this->Croogo->adminTab(__d('$i18nDomain', '$singularHumanName'), '#$primaryTab');\n";
	echo "\techo \$this->Croogo->adminTabs();\n";
echo "\$this->end();\n\n";

echo "\$this->append('tab-content');\n\n";

	echo "\techo \$this->Html->tabStart('{$primaryTab}');\n\n";

	echo "\t\techo \$this->Form->input('{$primaryKey}');\n\n";

	foreach ($fields as $field):
		if ($field == $primaryKey):
			continue;
		elseif (!in_array($field, array('created', 'modified', 'updated', 'created_by', 'updated_by'))):
			$fieldLabel = strrpos($field, '_id', -3) ? substr($field, 0, -3) : $field;
			$fieldLabel = Inflector::humanize($fieldLabel);
    
            $is_date_field = strpos($field, 'date');
            if ($is_date_field === false) {
                $is_date_field = strpos($field, 'date_time');
                if ($is_date_field === false) {
                    $is_date_field_class = '';
                    $is_date_field_type = '';
                } else {
                    $is_date_field_class = ' datepickertime';
                    $is_date_field_type = ", 'type'=>'text'";
                }
            } else {
                $is_date_field_class = ' datepicker';
                $is_date_field_type = ", 'type'=>'text'";
            }
    
    
			echo <<<EOF
		echo \$this->Form->input('{$field}', array(
			'label' =>  __d('$i18nDomain', '$fieldLabel'),
            'class'=>'input-block-level {$is_date_field_class}' 
            {$is_date_field_type}
		));\n
EOF;
		endif;
	endforeach;

	if (!empty($associations['hasAndBelongsToMany'])):
		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData):
    
            $is_date_field = strpos($assocName, 'date');
            if ($is_date_field === false) {
                $is_date_field = strpos($assocName, 'date_time');
                if ($is_date_field === false) {
                    $is_date_field_class = '';
                    $is_date_field_type = '';
                } else {
                    $is_date_field_class = ' datepickertime';
                    $is_date_field_type = ", 'type'=>'text'";
                }
            } else {
                $is_date_field_class = ' datepicker';
                $is_date_field_type = ", 'type'=>'text'";
            }
    
			echo "\t\techo \$this->Form->input('{$assocName}', array('class'=>'input-block-level {$is_date_field_class}' {$is_date_field_type}));\n";
		endforeach;
	endif;

	echo "\n";
	echo "\techo \$this->Html->tabEnd();\n\n";

	echo "\techo \$this->Croogo->adminTabs();\n\n";

echo "\$this->end();\n\n";

echo <<<EOF
\$this->append('panels');
	echo \$this->Html->beginBox(__d('croogo', 'Publishing')) .
		\$this->Form->button(__d('croogo', 'Apply'), array('name' => 'apply')) .
		\$this->Form->button(__d('croogo', 'Save'), array('button' => 'primary')) .
		\$this->Form->button(__d('croogo', 'Save & New'), array('button' => 'success', 'name' => 'save_and_new')) .
		\$this->Html->link(__d('croogo', 'Cancel'), array('action' => 'index'), array('button' => 'danger'));
	echo \$this->Html->endBox();

	echo \$this->Croogo->adminBoxes();
\$this->end();


EOF;

echo "\$this->append('form-end', \$this->Form->end());\n";
}