<?php
/* Copyright (C) 2007-2012  Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) 2014       Juanjo Menent       <jmenent@2byte.es>
 * Copyright (C) 2015       Florian Henry       <florian.henry@open-concept.pro>
 * Copyright (C) 2015       Raphaël Doursenaud  <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    brasilncm/brasilncm.class.php
 * \ingroup brasilncm
 * \brief   This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *          Put some comments here
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
//require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * Class Brasilncm
 *
 * Put here description of your class
 * @see CommonObject
 */
class Brasilncm extends CommonObject
{
	/**
	 * @var string Id to identify managed objects
	 */
	public $element = 'brasilncm';
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'brasil_ncm';

	/**
	 * @var BrasilncmLine[] Lines
	 */
	public $lines = array();

	/**
	 */
	
	public $datec = '';
	public $fk_customcode;
	public $ncm_nr;
	public $ncm_descr;
	public $imp_import;
	public $ipi;
	public $pis;
	public $cofins;

	/**
	 */
	

	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct(DoliDB $db)
	{
		$this->db = $db;
		return 1;
	}

	/**
	 * Create object into database
	 *
	 * @param  User $user      User that creates
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 *
	 * @return int <0 if KO, Id of created object if OK
	 */
	public function create(User $user, $notrigger = false)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$error = 0;

		// Clean parameters
		
		if (isset($this->fk_customcode)) {
			 $this->fk_customcode = trim($this->fk_customcode);
		}
		if (isset($this->ncm_nr)) {
			 $this->ncm_nr = trim($this->ncm_nr);
		}
		if (isset($this->ncm_descr)) {
			 $this->ncm_descr = trim($this->ncm_descr);
		}
		if (isset($this->imp_import)) {
			 $this->imp_import = trim($this->imp_import);
		}
		if (isset($this->ipi)) {
			 $this->ipi = trim($this->ipi);
		}
		if (isset($this->pis)) {
			 $this->pis = trim($this->pis);
		}
		if (isset($this->cofins)) {
			 $this->cofins = trim($this->cofins);
		}

		

		// Check parameters
		// Put here code to add control on parameters values

		// Insert request
		$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . $this->table_element . '(';
		
		$sql.= 'datec,';
		$sql.= 'fk_customcode,';
		$sql.= 'ncm_nr,';
		$sql.= 'ncm_descr,';
		$sql.= 'imp_import,';
		$sql.= 'ipi,';
		$sql.= 'pis,';
		$sql.= 'cofins';

		
		$sql .= ') VALUES (';
		
		$sql .= ' '."'".$this->db->idate(dol_now())."'".',';
		$sql .= ' '.(! isset($this->fk_customcode)?'NULL':$this->fk_customcode).',';
		$sql .= ' '.(! isset($this->ncm_nr)?'NULL':$this->ncm_nr).',';
		$sql .= ' '.(! isset($this->ncm_descr)?'NULL':"'".$this->db->escape($this->ncm_descr)."'").',';
		$sql .= ' '.(! isset($this->imp_import)?'NULL':$this->imp_import).',';
		$sql .= ' '.(! isset($this->ipi)?'NULL':$this->ipi).',';
		$sql .= ' '.(! isset($this->pis)?'NULL':$this->pis).',';
		$sql .= ' '.(! isset($this->cofins)?'NULL':$this->cofins);

		
		$sql .= ')';

		$this->db->begin();

		$resql = $this->db->query($sql);
		if (!$resql) {
			$error ++;
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . join(',', $this->errors), LOG_ERR);
		}

		if (!$error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . $this->table_element);

			if (!$notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action to call a trigger.

				//// Call triggers
				//$result=$this->call_trigger('MYOBJECT_CREATE',$user);
				//if ($result < 0) $error++;
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			$this->db->rollback();

			return - 1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
	}

	/**
	 * Load object in memory from the database
	 *
	 * @param int    $id  Id object
	 * @param string $ref Ref
	 *
	 * @return int <0 if KO, 0 if not found, >0 if OK
	 */
	public function fetch($id, $ref = null)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$sql = 'SELECT';
		$sql .= ' t.rowid,';
		
		$sql .= " t.datec,";
		$sql .= " t.fk_customcode,";
		$sql .= " t.ncm_nr,";
		$sql .= " t.ncm_descr,";
		$sql .= " t.imp_import,";
		$sql .= " t.ipi,";
		$sql .= " t.pis,";
		$sql .= " t.cofins";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element . ' as t';
		if (null !== $ref) {
			$sql .= ' WHERE t.ref = ' . '\'' . $ref . '\'';
		} else {
			$sql .= ' WHERE t.rowid = ' . $id;
		}

		$resql = $this->db->query($sql);
		if ($resql) {
			$numrows = $this->db->num_rows($resql);
			if ($numrows) {
				$obj = $this->db->fetch_object($resql);

				$this->id = $obj->rowid;
				
				$this->datec = $this->db->jdate($obj->datec);
				$this->fk_customcode = $obj->fk_customcode;
				$this->ncm_nr = $obj->ncm_nr;
				$this->ncm_descr = $obj->ncm_descr;
				$this->imp_import = $obj->imp_import;
				$this->ipi = $obj->ipi;
				$this->pis = $obj->pis;
				$this->cofins = $obj->cofins;

				
			}
			$this->db->free($resql);

			if ($numrows) {
				return 1;
			} else {
				return 0;
			}
		} else {
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . join(',', $this->errors), LOG_ERR);

			return - 1;
		}
	}

	/**
	 * Load object in memory from the database
	 *
	 * @param string $sortorder Sort Order
	 * @param string $sortfield Sort field
	 * @param int    $limit     offset limit
	 * @param int    $offset    offset limit
	 * @param array  $filter    filter array
	 * @param string $filtermode filter mode (AND or OR)
	 *
	 * @return int <0 if KO, >0 if OK
	 */
	public function fetchAll($sortorder='', $sortfield='', $limit=0, $offset=0, array $filter = array(), $filtermode='AND')
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$sql = 'SELECT';
		$sql .= ' t.rowid,';
		
		$sql .= " t.datec,";
		$sql .= " t.fk_customcode,";
		$sql .= " t.ncm_nr,";
		$sql .= " t.ncm_descr,";
		$sql .= " t.imp_import,";
		$sql .= " t.ipi,";
		$sql .= " t.pis,";
		$sql .= " t.cofins";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element. ' as t';

		// Manage filter
		$sqlwhere = array();
		if (count($filter) > 0) {
			foreach ($filter as $key => $value) {
				$sqlwhere [] = $key . ' LIKE \'%' . $this->db->escape($value) . '%\'';
			}
		}
		if (count($sqlwhere) > 0) {
			$sql .= ' WHERE ' . implode(' '.$filtermode.' ', $sqlwhere);
		}
		
		if (!empty($sortfield)) {
			$sql .= $this->db->order($sortfield,$sortorder);
		}
		if (!empty($limit)) {
		 $sql .=  ' ' . $this->db->plimit($limit + 1, $offset);
		}
		$this->lines = array();

		$resql = $this->db->query($sql);
		if ($resql) {
			$num = $this->db->num_rows($resql);

			while ($obj = $this->db->fetch_object($resql)) {
				$line = new BrasilncmLine();

				$line->id = $obj->rowid;
				
				$line->datec = $this->db->jdate($obj->datec);
				$line->fk_customcode = $obj->fk_customcode;
				$line->ncm_nr = $obj->ncm_nr;
				$line->ncm_descr = $obj->ncm_descr;
				$line->imp_import = $obj->imp_import;
				$line->ipi = $obj->ipi;
				$line->pis = $obj->pis;
				$line->cofins = $obj->cofins;

				

				$this->lines[] = $line;
			}
			$this->db->free($resql);

			return $num;
		} else {
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . join(',', $this->errors), LOG_ERR);

			return - 1;
		}
	}

	/**
	 * Update object into database
	 *
	 * @param  User $user      User that modifies
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 *
	 * @return int <0 if KO, >0 if OK
	 */
	public function update(User $user, $notrigger = false)
	{
		$error = 0;

		dol_syslog(__METHOD__, LOG_DEBUG);

		// Clean parameters
		
		if (isset($this->fk_customcode)) {
			 $this->fk_customcode = trim($this->fk_customcode);
		}
		if (isset($this->ncm_nr)) {
			 $this->ncm_nr = trim($this->ncm_nr);
		}
		if (isset($this->ncm_descr)) {
			 $this->ncm_descr = trim($this->ncm_descr);
		}
		if (isset($this->imp_import)) {
			 $this->imp_import = trim($this->imp_import);
		}
		if (isset($this->ipi)) {
			 $this->ipi = trim($this->ipi);
		}
		if (isset($this->pis)) {
			 $this->pis = trim($this->pis);
		}
		if (isset($this->cofins)) {
			 $this->cofins = trim($this->cofins);
		}

		

		// Check parameters
		// Put here code to add a control on parameters values

		// Update request
		$sql = 'UPDATE ' . MAIN_DB_PREFIX . $this->table_element . ' SET';
		
		$sql .= ' datec = '.(! isset($this->datec) || dol_strlen($this->datec) != 0 ? "'".$this->db->idate($this->datec)."'" : 'null').',';
		$sql .= ' fk_customcode = '.(isset($this->fk_customcode)?$this->fk_customcode:"null").',';
		$sql .= ' ncm_nr = '.(isset($this->ncm_nr)?$this->ncm_nr:"null").',';
		$sql .= ' ncm_descr = '.(isset($this->ncm_descr)?"'".$this->db->escape($this->ncm_descr)."'":"null").',';
		$sql .= ' imp_import = '.(isset($this->imp_import)?$this->imp_import:"null").',';
		$sql .= ' ipi = '.(isset($this->ipi)?$this->ipi:"null").',';
		$sql .= ' pis = '.(isset($this->pis)?$this->pis:"null").',';
		$sql .= ' cofins = '.(isset($this->cofins)?$this->cofins:"null");

        
		$sql .= ' WHERE rowid=' . $this->id;

		$this->db->begin();

		$resql = $this->db->query($sql);
		if (!$resql) {
			$error ++;
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . join(',', $this->errors), LOG_ERR);
		}

		if (!$error && !$notrigger) {
			// Uncomment this and change MYOBJECT to your own tag if you
			// want this action calls a trigger.

			//// Call triggers
			//$result=$this->call_trigger('MYOBJECT_MODIFY',$user);
			//if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
			//// End call triggers
		}

		// Commit or rollback
		if ($error) {
			$this->db->rollback();

			return - 1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user      User that deletes
	 * @param bool $notrigger false=launch triggers after, true=disable triggers
	 *
	 * @return int <0 if KO, >0 if OK
	 */
	public function delete(User $user, $notrigger = false)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$error = 0;

		$this->db->begin();

		if (!$error) {
			if (!$notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action calls a trigger.

				//// Call triggers
				//$result=$this->call_trigger('MYOBJECT_DELETE',$user);
				//if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
				//// End call triggers
			}
		}

		if (!$error) {
			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . $this->table_element;
			$sql .= ' WHERE rowid=' . $this->id;

			$resql = $this->db->query($sql);
			if (!$resql) {
				$error ++;
				$this->errors[] = 'Error ' . $this->db->lasterror();
				dol_syslog(__METHOD__ . ' ' . join(',', $this->errors), LOG_ERR);
			}
		}

		// Commit or rollback
		if ($error) {
			$this->db->rollback();

			return - 1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Load an object from its id and create a new one in database
	 *
	 * @param int $fromid Id of object to clone
	 *
	 * @return int New id of clone
	 */
	public function createFromClone($fromid)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		global $user;
		$error = 0;
		$object = new Brasilncm($this->db);

		$this->db->begin();

		// Load source object
		$object->fetch($fromid);
		// Reset object
		$object->id = 0;

		// Clear fields
		// ...

		// Create clone
		$result = $object->create($user);

		// Other options
		if ($result < 0) {
			$error ++;
			$this->errors = $object->errors;
			dol_syslog(__METHOD__ . ' ' . join(',', $this->errors), LOG_ERR);
		}

		// End
		if (!$error) {
			$this->db->commit();

			return $object->id;
		} else {
			$this->db->rollback();

			return - 1;
		}
	}

	/**
	 * Initialise object with example values
	 * Id must be 0 if object instance is a specimen
	 *
	 * @return void
	 */
	public function initAsSpecimen()
	{
		$this->id = 0;
		
		$this->datec = '';
		$this->fk_customcode = '';
		$this->ncm_nr = '';
		$this->ncm_descr = '';
		$this->imp_import = '';
		$this->ipi = '';
		$this->pis = '';
		$this->cofins = '';

		
	}

}

/**
 * Class BrasilncmLine
 */
class BrasilncmLine
{
	/**
	 * @var int ID
	 */
	public $id;
	/**
	 * @var mixed Sample line property 1
	 */
	
	public $datec = '';
	public $fk_customcode;
	public $ncm_nr;
	public $ncm_descr;
	public $imp_import;
	public $ipi;
	public $pis;
	public $cofins;

	/**
	 * @var mixed Sample line property 2
	 */
	
}
