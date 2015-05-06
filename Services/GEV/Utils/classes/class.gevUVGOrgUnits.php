<?php

/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */#

/**
* Manipulate and query the Org-Units in the UVG.
*
* @author	Richard Klees <richard.klees@concepts-and-training.de>
* @version	$Id$
*/

require_once("Services/GEV/Utils/classes/class.gevSettings.php");
require_once("Modules/OrgUnit/classes/PersonalOrgUnit/class.ilPersonalOrgUnits.php");

class gevUVGOrgUnits extends ilPersonalOrgUnits {
	static $instance;

	protected function __construct() {
		global $ilDB, $tree;
		$this->db = $ilDB;
		$this->tree = $tree;
		
		$this->gev_settings = gevSettings::getInstance();
		
		parent::__construct( $this->gev_settings->getDBVPOUBaseUnitId()
						   , $this->gev_settings->getDBVPOUTemplateUnitId()
						   );
	}
	
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public function getClassName() {
		return "gevUVGOrgUnits";
	}

	public function createOrgUnitFor($a_superior_id) {
		$orgu_id = parent::createOrgUnitFor($a_superior_id);
		$this->moveToBDFromIV($orgu_id);
	}
	
	/**
	 * Moves the given personal org unit to the appropriate location in the UVG-structure,
	 * i.e. moves to a subunit where name equals the name found in iv. Throws when given
	 * org unit is no child of the base org unit.
	 *
	 * @param iObjOrgUnit $a_orgu
	 */
	public function moveToBDFromIV(ilObjOrgUnit $a_orgu) {
		$owner = $this->getOwnerOfOrgUnit($a_orgu->getId());
		$job_number = $this->getJobNumberOf($owner);

		if ($job_number) {
			$target_ref_id = $this->getBDOrgUnitRefIdFor($owner);
		}
		else {
			$target_ref_id = $this->base_ref_id;
		}
		
		$ref_id = $a_orgu->getRefId();
		if (!$ref_id) {
			$this->PersonalOrgUnit(
					"moveToBDFromIV",
					"Could not find ref_id for ".$a_orgu->getId().".");
		}
		
		$this->tree->moveTree($ref_id, $target_ref_id);
	}
	
	protected function getJobNumberOf($a_user_id) {
		require_once("Services/GEV/Utils/classes/class.gevUserUtils.php");
		return gevUserUtils::getInstance($a_user_id)->getJobNumber();
	}
	
	protected function getBDOrgUnitRefIdFor($a_user_id) {
		require_once("Services/GEV/Utils/classes/class.gevUserUtils.php");
		$bd_name = gevUserUtils::getInstance($a_user_id)->getBDFromIV();
		if (!$bd_name) {
			$this->ilPersonalOrgUnitsError("getBDOrgUnitRefIdFor", "Could not find BD-Name for $a_user_id.");
		}
		
		$children = $this->tree->getChilds($this->base_ref_id);
		foreach ($children as $child_ref_id) {
			$child_obj_id = ilObject::_lookupObjectId($child_ref);
			if (ilObject::_lookupTitle($child_obj_id) == $bd_name) {
				return $child_ref_id;
			}
		}
		
		// Apparently there is no org unit beneath the base that matches the desired name.
		// We need to create a new one
		return $this->createBDOrgUnit($bd_name)->getRefId();
	}
	
	protected function createBDOrgUnit($a_bd_name) {
		require_once("Modules/OrgUnit/classes/class.ilObjOrgUnit.php");
		require_once("Services/GEV/Utils/classes/class.gevOrgUnitUtils.php");
		
		$orgu = new ilObjOrgUnit();
		$orgu->setTitle($a_bd_name);
		$orgu->create();
		$orgu->createReference();
		$orgu->update();
		$orgu->putInTree($this->base_ref_id);
		$orgu->initDefaultRoles();
		
		$orgutils = gevOrgUnitUtils::getInstance($id);
		$orgutils->setType(gevSettings::ORG_TYPE_DEFAULT);
		
		return $orgu;
	}
}

?>