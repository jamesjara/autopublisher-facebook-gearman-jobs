<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "jobsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$jobs_delete = NULL; // Initialize page object first

class cjobs_delete extends cjobs {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{A78C9F64-F701-4951-8B68-9678633E190C}";

	// Table name
	var $TableName = 'jobs';

	// Page object name
	var $PageObjName = 'jobs_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (jobs)
		if (!isset($GLOBALS["jobs"])) {
			$GLOBALS["jobs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jobs"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jobs', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();
		$UserProfile->LoadProfile(@$_SESSION[EW_SESSION_USER_PROFILE]);

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("jobslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in jobs class, jobsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->status->setDbValue($rs->fields('status'));
		$this->type->setDbValue($rs->fields('type'));
		$this->targetid->setDbValue($rs->fields('targetid'));
		$this->credencial->setDbValue($rs->fields('credencial'));
		$this->dataId->setDbValue($rs->fields('dataId'));
		$this->datetime->setDbValue($rs->fields('datetime'));
		$this->sessionid->setDbValue($rs->fields('sessionid'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->status->DbValue = $row['status'];
		$this->type->DbValue = $row['type'];
		$this->targetid->DbValue = $row['targetid'];
		$this->credencial->DbValue = $row['credencial'];
		$this->dataId->DbValue = $row['dataId'];
		$this->datetime->DbValue = $row['datetime'];
		$this->sessionid->DbValue = $row['sessionid'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// status
		// type
		// targetid
		// credencial
		// dataId
		// datetime
		// sessionid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// datetime
			$this->datetime->ViewValue = $this->datetime->CurrentValue;
			$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 9);
			$this->datetime->ViewCustomAttributes = "";

			// sessionid
			$this->sessionid->ViewValue = $this->sessionid->CurrentValue;
			$this->sessionid->ViewCustomAttributes = "";

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

			// credencial
			$this->credencial->LinkCustomAttributes = "";
			$this->credencial->HrefValue = "";
			$this->credencial->TooltipValue = "";

			// dataId
			$this->dataId->LinkCustomAttributes = "";
			$this->dataId->HrefValue = "";
			$this->dataId->TooltipValue = "";

			// datetime
			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";
			$this->datetime->TooltipValue = "";

			// sessionid
			$this->sessionid->LinkCustomAttributes = "";
			$this->sessionid->HrefValue = "";
			$this->sessionid->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "jobslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($jobs_delete)) $jobs_delete = new cjobs_delete();

// Page init
$jobs_delete->Page_Init();

// Page main
$jobs_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jobs_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var jobs_delete = new ew_Page("jobs_delete");
jobs_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = jobs_delete.PageID; // For backward compatibility

// Form object
var fjobsdelete = new ew_Form("fjobsdelete");

// Form_CustomValidate event
fjobsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjobsdelete.ValidateRequired = true;
<?php } else { ?>
fjobsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fjobsdelete.Lists["x_type"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fjobsdelete.Lists["x_targetid"] = {"LinkField":"x_domainid","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fjobsdelete.Lists["x_credencial"] = {"LinkField":"x_domainid","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fjobsdelete.Lists["x_dataId"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($jobs_delete->Recordset = $jobs_delete->LoadRecordset())
	$jobs_deleteTotalRecs = $jobs_delete->Recordset->RecordCount(); // Get record count
if ($jobs_deleteTotalRecs <= 0) { // No record found, exit
	if ($jobs_delete->Recordset)
		$jobs_delete->Recordset->Close();
	$jobs_delete->Page_Terminate("jobslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $jobs_delete->ShowPageHeader(); ?>
<?php
$jobs_delete->ShowMessage();
?>
<form name="fjobsdelete" id="fjobsdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="jobs">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($jobs_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_jobsdelete" class="ewTable ewTableSeparate">
<?php echo $jobs->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_jobs_id" class="jobs_id"><?php echo $jobs->id->FldCaption() ?></span></td>
		<td><span id="elh_jobs_status" class="jobs_status"><?php echo $jobs->status->FldCaption() ?></span></td>
		<td><span id="elh_jobs_type" class="jobs_type"><?php echo $jobs->type->FldCaption() ?></span></td>
		<td><span id="elh_jobs_targetid" class="jobs_targetid"><?php echo $jobs->targetid->FldCaption() ?></span></td>
		<td><span id="elh_jobs_credencial" class="jobs_credencial"><?php echo $jobs->credencial->FldCaption() ?></span></td>
		<td><span id="elh_jobs_dataId" class="jobs_dataId"><?php echo $jobs->dataId->FldCaption() ?></span></td>
		<td><span id="elh_jobs_datetime" class="jobs_datetime"><?php echo $jobs->datetime->FldCaption() ?></span></td>
		<td><span id="elh_jobs_sessionid" class="jobs_sessionid"><?php echo $jobs->sessionid->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$jobs_delete->RecCnt = 0;
$i = 0;
while (!$jobs_delete->Recordset->EOF) {
	$jobs_delete->RecCnt++;
	$jobs_delete->RowCnt++;

	// Set row properties
	$jobs->ResetAttrs();
	$jobs->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$jobs_delete->LoadRowValues($jobs_delete->Recordset);

	// Render row
	$jobs_delete->RenderRow();
?>
	<tr<?php echo $jobs->RowAttributes() ?>>
		<td<?php echo $jobs->id->CellAttributes() ?>><span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_id" class="control-group jobs_id">
<span<?php echo $jobs->id->ViewAttributes() ?>>
<?php echo $jobs->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobs->status->CellAttributes() ?>><span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_status" class="control-group jobs_status">
<span<?php echo $jobs->status->ViewAttributes() ?>>
<?php echo $jobs->status->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobs->type->CellAttributes() ?>><span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_type" class="control-group jobs_type">
<span<?php echo $jobs->type->ViewAttributes() ?>>
<?php echo $jobs->type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobs->targetid->CellAttributes() ?>><span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_targetid" class="control-group jobs_targetid">
<span<?php echo $jobs->targetid->ViewAttributes() ?>>
<?php echo $jobs->targetid->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobs->credencial->CellAttributes() ?>><span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_credencial" class="control-group jobs_credencial">
<span<?php echo $jobs->credencial->ViewAttributes() ?>>
<?php echo $jobs->credencial->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobs->dataId->CellAttributes() ?>><span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_dataId" class="control-group jobs_dataId">
<span<?php echo $jobs->dataId->ViewAttributes() ?>>
<?php echo $jobs->dataId->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobs->datetime->CellAttributes() ?>><span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_datetime" class="control-group jobs_datetime">
<span<?php echo $jobs->datetime->ViewAttributes() ?>>
<?php echo $jobs->datetime->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobs->sessionid->CellAttributes() ?>><span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_sessionid" class="control-group jobs_sessionid">
<span<?php echo $jobs->sessionid->ViewAttributes() ?>>
<?php echo $jobs->sessionid->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$jobs_delete->Recordset->MoveNext();
}
$jobs_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fjobsdelete.Init();
</script>
<?php
$jobs_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jobs_delete->Page_Terminate();
?>
