<?php

// Global variable for table object
$getjobs = NULL;

//
// Table class for getjobs
//
class cgetjobs extends cTable {
	var $id;
	var $status;
	var $type;
	var $targetid;
	var $sessionid;
	var $datetime;
	var $dataId;
	var $credencial;
	var $app;
	var $secret;
	var $target;
	var $message;
	var $picture;
	var $link;
	var $description;
	var $action_name;
	var $name;
	var $action_link;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'getjobs';
		$this->TableName = 'getjobs';
		$this->TableType = 'VIEW';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('getjobs', 'getjobs', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// status
		$this->status = new cField('getjobs', 'getjobs', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// type
		$this->type = new cField('getjobs', 'getjobs', 'x_type', 'type', '`type`', '`type`', 3, -1, FALSE, '`type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['type'] = &$this->type;

		// targetid
		$this->targetid = new cField('getjobs', 'getjobs', 'x_targetid', 'targetid', '`targetid`', '`targetid`', 3, -1, FALSE, '`targetid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->targetid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['targetid'] = &$this->targetid;

		// sessionid
		$this->sessionid = new cField('getjobs', 'getjobs', 'x_sessionid', 'sessionid', '`sessionid`', '`sessionid`', 200, -1, FALSE, '`sessionid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sessionid'] = &$this->sessionid;

		// datetime
		$this->datetime = new cField('getjobs', 'getjobs', 'x_datetime', 'datetime', '`datetime`', 'DATE_FORMAT(`datetime`, \'%Y/%m/%d\')', 135, 9, FALSE, '`datetime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->datetime->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['datetime'] = &$this->datetime;

		// dataId
		$this->dataId = new cField('getjobs', 'getjobs', 'x_dataId', 'dataId', '`dataId`', '`dataId`', 3, -1, FALSE, '`dataId`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dataId->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dataId'] = &$this->dataId;

		// credencial
		$this->credencial = new cField('getjobs', 'getjobs', 'x_credencial', 'credencial', '`credencial`', '`credencial`', 3, -1, FALSE, '`credencial`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->credencial->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['credencial'] = &$this->credencial;

		// app
		$this->app = new cField('getjobs', 'getjobs', 'x_app', 'app', '`app`', '`app`', 200, -1, FALSE, '`app`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['app'] = &$this->app;

		// secret
		$this->secret = new cField('getjobs', 'getjobs', 'x_secret', 'secret', '`secret`', '`secret`', 200, -1, FALSE, '`secret`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['secret'] = &$this->secret;

		// target
		$this->target = new cField('getjobs', 'getjobs', 'x_target', 'target', '`target`', '`target`', 200, -1, FALSE, '`target`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['target'] = &$this->target;

		// message
		$this->message = new cField('getjobs', 'getjobs', 'x_message', 'message', '`message`', '`message`', 201, -1, FALSE, '`message`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['message'] = &$this->message;

		// picture
		$this->picture = new cField('getjobs', 'getjobs', 'x_picture', 'picture', '`picture`', '`picture`', 201, -1, FALSE, '`picture`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['picture'] = &$this->picture;

		// link
		$this->link = new cField('getjobs', 'getjobs', 'x_link', 'link', '`link`', '`link`', 201, -1, FALSE, '`link`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['link'] = &$this->link;

		// description
		$this->description = new cField('getjobs', 'getjobs', 'x_description', 'description', '`description`', '`description`', 201, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['description'] = &$this->description;

		// action_name
		$this->action_name = new cField('getjobs', 'getjobs', 'x_action_name', 'action_name', '`action_name`', '`action_name`', 201, -1, FALSE, '`action_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['action_name'] = &$this->action_name;

		// name
		$this->name = new cField('getjobs', 'getjobs', 'x_name', 'name', '`name`', '`name`', 201, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name'] = &$this->name;

		// action_link
		$this->action_link = new cField('getjobs', 'getjobs', 'x_action_link', 'action_link', '`action_link`', '`action_link`', 201, -1, FALSE, '`action_link`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['action_link'] = &$this->action_link;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`getjobs`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`getjobs`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "getjobslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "getjobslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("getjobsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("getjobsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "getjobsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("getjobsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("getjobsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("getjobsdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id"]; // id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->status->setDbValue($rs->fields('status'));
		$this->type->setDbValue($rs->fields('type'));
		$this->targetid->setDbValue($rs->fields('targetid'));
		$this->sessionid->setDbValue($rs->fields('sessionid'));
		$this->datetime->setDbValue($rs->fields('datetime'));
		$this->dataId->setDbValue($rs->fields('dataId'));
		$this->credencial->setDbValue($rs->fields('credencial'));
		$this->app->setDbValue($rs->fields('app'));
		$this->secret->setDbValue($rs->fields('secret'));
		$this->target->setDbValue($rs->fields('target'));
		$this->message->setDbValue($rs->fields('message'));
		$this->picture->setDbValue($rs->fields('picture'));
		$this->link->setDbValue($rs->fields('link'));
		$this->description->setDbValue($rs->fields('description'));
		$this->action_name->setDbValue($rs->fields('action_name'));
		$this->name->setDbValue($rs->fields('name'));
		$this->action_link->setDbValue($rs->fields('action_link'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// status
		// type
		// targetid
		// sessionid
		// datetime
		// dataId
		// credencial
		// app
		// secret
		// target
		// message
		// picture
		// link
		// description
		// action_name
		// name
		// action_link
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// type
		if (strval($this->type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->type->CurrentValue, EW_DATATYPE_NUMBER);
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->type, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->type->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->type->ViewValue = $this->type->CurrentValue;
			}
		} else {
			$this->type->ViewValue = NULL;
		}
		$this->type->ViewCustomAttributes = "";

		// targetid
		if (strval($this->targetid->CurrentValue) <> "") {
			$sFilterWrk = "`domainid`" . ew_SearchString("=", $this->targetid->CurrentValue, EW_DATATYPE_NUMBER);
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `domainid`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `targets`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `domainid`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `targets`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->targetid, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->targetid->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->targetid->ViewValue = $this->targetid->CurrentValue;
			}
		} else {
			$this->targetid->ViewValue = NULL;
		}
		$this->targetid->ViewCustomAttributes = "";

		// sessionid
		$this->sessionid->ViewValue = $this->sessionid->CurrentValue;
		$this->sessionid->ViewCustomAttributes = "";

		// datetime
		$this->datetime->ViewValue = $this->datetime->CurrentValue;
		$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 9);
		$this->datetime->ViewCustomAttributes = "";

		// dataId
		if (strval($this->dataId->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->dataId->CurrentValue, EW_DATATYPE_NUMBER);
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `posts`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `posts`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->dataId, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->dataId->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->dataId->ViewValue = $this->dataId->CurrentValue;
			}
		} else {
			$this->dataId->ViewValue = NULL;
		}
		$this->dataId->ViewCustomAttributes = "";

		// credencial
		if (strval($this->credencial->CurrentValue) <> "") {
			$sFilterWrk = "`domainid`" . ew_SearchString("=", $this->credencial->CurrentValue, EW_DATATYPE_NUMBER);
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `domainid`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `credenciales`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `domainid`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `credenciales`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->credencial, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->credencial->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->credencial->ViewValue = $this->credencial->CurrentValue;
			}
		} else {
			$this->credencial->ViewValue = NULL;
		}
		$this->credencial->ViewCustomAttributes = "";

		// app
		$this->app->ViewValue = $this->app->CurrentValue;
		$this->app->ViewCustomAttributes = "";

		// secret
		$this->secret->ViewValue = $this->secret->CurrentValue;
		$this->secret->ViewCustomAttributes = "";

		// target
		$this->target->ViewValue = $this->target->CurrentValue;
		$this->target->ViewCustomAttributes = "";

		// message
		$this->message->ViewValue = $this->message->CurrentValue;
		$this->message->ViewCustomAttributes = "";

		// picture
		$this->picture->ViewValue = $this->picture->CurrentValue;
		$this->picture->ViewCustomAttributes = "";

		// link
		$this->link->ViewValue = $this->link->CurrentValue;
		$this->link->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// action_name
		$this->action_name->ViewValue = $this->action_name->CurrentValue;
		$this->action_name->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// action_link
		$this->action_link->ViewValue = $this->action_link->CurrentValue;
		$this->action_link->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// type
		$this->type->LinkCustomAttributes = "";
		$this->type->HrefValue = "";
		$this->type->TooltipValue = "";

		// targetid
		$this->targetid->LinkCustomAttributes = "";
		$this->targetid->HrefValue = "";
		$this->targetid->TooltipValue = "";

		// sessionid
		$this->sessionid->LinkCustomAttributes = "";
		$this->sessionid->HrefValue = "";
		$this->sessionid->TooltipValue = "";

		// datetime
		$this->datetime->LinkCustomAttributes = "";
		$this->datetime->HrefValue = "";
		$this->datetime->TooltipValue = "";

		// dataId
		$this->dataId->LinkCustomAttributes = "";
		$this->dataId->HrefValue = "";
		$this->dataId->TooltipValue = "";

		// credencial
		$this->credencial->LinkCustomAttributes = "";
		$this->credencial->HrefValue = "";
		$this->credencial->TooltipValue = "";

		// app
		$this->app->LinkCustomAttributes = "";
		$this->app->HrefValue = "";
		$this->app->TooltipValue = "";

		// secret
		$this->secret->LinkCustomAttributes = "";
		$this->secret->HrefValue = "";
		$this->secret->TooltipValue = "";

		// target
		$this->target->LinkCustomAttributes = "";
		$this->target->HrefValue = "";
		$this->target->TooltipValue = "";

		// message
		$this->message->LinkCustomAttributes = "";
		$this->message->HrefValue = "";
		$this->message->TooltipValue = "";

		// picture
		$this->picture->LinkCustomAttributes = "";
		$this->picture->HrefValue = "";
		$this->picture->TooltipValue = "";

		// link
		$this->link->LinkCustomAttributes = "";
		$this->link->HrefValue = "";
		$this->link->TooltipValue = "";

		// description
		$this->description->LinkCustomAttributes = "";
		$this->description->HrefValue = "";
		$this->description->TooltipValue = "";

		// action_name
		$this->action_name->LinkCustomAttributes = "";
		$this->action_name->HrefValue = "";
		$this->action_name->TooltipValue = "";

		// name
		$this->name->LinkCustomAttributes = "";
		$this->name->HrefValue = "";
		$this->name->TooltipValue = "";

		// action_link
		$this->action_link->LinkCustomAttributes = "";
		$this->action_link->HrefValue = "";
		$this->action_link->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->targetid->Exportable) $Doc->ExportCaption($this->targetid);
				if ($this->sessionid->Exportable) $Doc->ExportCaption($this->sessionid);
				if ($this->datetime->Exportable) $Doc->ExportCaption($this->datetime);
				if ($this->dataId->Exportable) $Doc->ExportCaption($this->dataId);
				if ($this->credencial->Exportable) $Doc->ExportCaption($this->credencial);
				if ($this->app->Exportable) $Doc->ExportCaption($this->app);
				if ($this->secret->Exportable) $Doc->ExportCaption($this->secret);
				if ($this->target->Exportable) $Doc->ExportCaption($this->target);
				if ($this->message->Exportable) $Doc->ExportCaption($this->message);
				if ($this->picture->Exportable) $Doc->ExportCaption($this->picture);
				if ($this->link->Exportable) $Doc->ExportCaption($this->link);
				if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				if ($this->action_name->Exportable) $Doc->ExportCaption($this->action_name);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->action_link->Exportable) $Doc->ExportCaption($this->action_link);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->targetid->Exportable) $Doc->ExportCaption($this->targetid);
				if ($this->sessionid->Exportable) $Doc->ExportCaption($this->sessionid);
				if ($this->datetime->Exportable) $Doc->ExportCaption($this->datetime);
				if ($this->dataId->Exportable) $Doc->ExportCaption($this->dataId);
				if ($this->credencial->Exportable) $Doc->ExportCaption($this->credencial);
				if ($this->app->Exportable) $Doc->ExportCaption($this->app);
				if ($this->secret->Exportable) $Doc->ExportCaption($this->secret);
				if ($this->target->Exportable) $Doc->ExportCaption($this->target);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->status->Exportable) $Doc->ExportField($this->status);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->targetid->Exportable) $Doc->ExportField($this->targetid);
					if ($this->sessionid->Exportable) $Doc->ExportField($this->sessionid);
					if ($this->datetime->Exportable) $Doc->ExportField($this->datetime);
					if ($this->dataId->Exportable) $Doc->ExportField($this->dataId);
					if ($this->credencial->Exportable) $Doc->ExportField($this->credencial);
					if ($this->app->Exportable) $Doc->ExportField($this->app);
					if ($this->secret->Exportable) $Doc->ExportField($this->secret);
					if ($this->target->Exportable) $Doc->ExportField($this->target);
					if ($this->message->Exportable) $Doc->ExportField($this->message);
					if ($this->picture->Exportable) $Doc->ExportField($this->picture);
					if ($this->link->Exportable) $Doc->ExportField($this->link);
					if ($this->description->Exportable) $Doc->ExportField($this->description);
					if ($this->action_name->Exportable) $Doc->ExportField($this->action_name);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->action_link->Exportable) $Doc->ExportField($this->action_link);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->status->Exportable) $Doc->ExportField($this->status);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->targetid->Exportable) $Doc->ExportField($this->targetid);
					if ($this->sessionid->Exportable) $Doc->ExportField($this->sessionid);
					if ($this->datetime->Exportable) $Doc->ExportField($this->datetime);
					if ($this->dataId->Exportable) $Doc->ExportField($this->dataId);
					if ($this->credencial->Exportable) $Doc->ExportField($this->credencial);
					if ($this->app->Exportable) $Doc->ExportField($this->app);
					if ($this->secret->Exportable) $Doc->ExportField($this->secret);
					if ($this->target->Exportable) $Doc->ExportField($this->target);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
