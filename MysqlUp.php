<?php
/**
 * MySQL Up - Enhanced Update Functionality For Mysql DBO Source
 *
 * Copyright 2013, Paul Furiani <paul@furiani.net>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author: Unfaiir <unfaiir@furiani.net>
 * @copyright Copyright 2013-2014
 * @link http://cakephp.org CakePHP(tm) Project
 * @package Datasources.Model.Datasource.Database
 */

App::uses('Mysql', 'Model/Datasource/Database');

/**
 * DBO implementation for the MySQL DBMS with enhanced updateAll() functionality.  Specificially, this adapter allows joins to be used in updateAll().
 *
 * A DboSource adapter for MySQL that enables additional features with updateAll()
 *
 * @package Datasources.Model.Datasource.Database
*/
class MysqlUp extends Mysql {

/**
 * Datasource Description
 *
 * @var string
 */
	public $description = 'MySQL Up - Enhanced Update Functionality For Mysql DBO Source';
	
/**
 * Generates and executes an SQL UPDATE statement for given model, fields, and values.
 *
 * @param Model $model
 * @param array $fields
 * @param array $values
 * @param mixed $conditions
 * @return array
 */
	public function update(Model $model, $fields = array(), $values = null, $conditions = null) {
		$query = array('joins' => array());
		if (isset($conditions['joins'])) {
			$query['joins'] = $conditions['joins'];
			unset($conditions['joins']);
			if(!$conditions) {
				$conditions = true;
			}
		}
		
		if (!$this->_useAlias) {
			return parent::update($model, $fields, $values, $conditions);
		}

		if (!$values) {
			$combined = $fields;
		} else {
			$combined = array_combine($fields, $values);
		}

		$alias = $joins = false;
		$fields = $this->_prepareUpdateFields($model, $combined, empty($conditions), !empty($conditions));
		$fields = implode(', ', $fields);
		$table = $this->fullTableName($model);

		if (!empty($conditions)) {
			$alias = $this->name($model->alias);
			if ($model->name == $model->alias) {
				if (!empty($query['joins'])) {
					$count = count($query['joins']);
					for ($i = 0; $i < $count; $i++) {
						if (is_array($query['joins'][$i])) {
							$query['joins'][$i] = $this->buildJoinStatement($query['joins'][$i]);
						}
					}
				}
				$joins = implode(' ', array_merge($this->_getJoins($model), $query['joins']));
			}
		}
		$conditions = $this->conditions($this->defaultConditions($model, $conditions, $alias), true, true, $model);

		if ($conditions === false) {
			return false;
		}
		
		if (!$this->execute($this->renderStatement('update', compact('table', 'alias', 'joins', 'fields', 'conditions')))) {
			$model->onError();
			return false;
		}
		return true;
	}
}
