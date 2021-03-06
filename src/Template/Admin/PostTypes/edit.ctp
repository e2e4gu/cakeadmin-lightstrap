<?php
/**
 * CakeManager (http://cakemanager.org)
 * Copyright (c) http://cakemanager.org
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) http://cakemanager.org
 * @link          http://cakemanager.org CakeManager Project
 * @since         1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

$this->Html->addCrumb('Dashboard', ['action' => 'index', 'controller' => 'dashboard']);
$this->Html->addCrumb($type['alias'], ['action' => 'index', 'type' => $type['slug']]);
$this->Html->addCrumb('Edit ' . Inflector::singularize($type['alias']), '');
echo $this->Html->getCrumbList();
?>
<?= $this->Form->create($entity, $type['formFields']['_create']); ?>
<fieldset>
    <legend><?= __('Edit ' . Inflector::singularize($type['alias'])) ?></legend>
    <?php
    foreach ($type['formFields'] as $field => $options) {
        if (substr($field, 0, 1) !== '_') {
            if (in_array($options['on'], ['both', 'edit'])) {
                echo $this->Form->input($field, $options);
            }
        }
    }
    ?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
