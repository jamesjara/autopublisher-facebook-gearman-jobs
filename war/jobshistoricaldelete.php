<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "jobshistoricalinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$jobshistorical_delete = NULL; // Initialize page object first

class cjobshistorical_delete extends cjobshistorical {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{A78C9F64-F701-4951-8B68-9678633E190C}";

	// Table name
	var $TableName = 'jobshistorical';

	// Page object name
	var $PageObjName = 'jobshistorical_delete';

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

		// Table object (jobshistorical)
		if (!isset($GLOBALS["jobshistorical"])) {
			$GLOBALS["jobshistorical"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jobshistorical"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jobshistorical', TRUE);

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
		$this->idjobs->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("jobshistoricallist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in jobshistorical class, jobshistoricalinfo.php

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
		$this->idjobs->setDbValue($rs->fields('idjobs'));
		$this->status->setDbValue($rs->fields('status'));
		$this->type->setDbValue($rs->fields('type'));
		$this->dataId->setDbValue($rs->fields('dataId'));
		$this->datetime->setDbValue($rs->fields('datetime'));
		$this->id->setDbValue($rs->fields('id'));
		$this->exec->setDbValue($rs->fields('exec'));
		$this->data_id->setDbValue($rs->fields('data_id'));
		$this->finished->setDbValue($rs->fields('finished'));
		$this->resultado->setDbValue($rs->fields('resultado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idjobs->DbValue = $row['idjobs'];
		$this->status->DbValue = $row['status'];
		$this->type->DbValue = $row['type'];
		$this->dataId->DbValue = $row['dataId'];
		$this->datetime->DbValue = $row['datetime'];
		$this->id->DbValue = $row['id'];
		$this->exec->DbValue = $row['exec'];
		$this->data_id->DbValue = $row['data_id'];
		$this->finished->DbValue = $row['finished'];
		$this->resultado->DbValue = $row['resultado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idjobs
		// status
		// type
		// dataId
		// datetime
		// id
		// exec
		// data_id
		// finished
		// resultado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idjobs
			$this->idjobs->ViewValue = $this->idjobs->CurrentValue;
			$this->idjobs->ViewCustomAttributes = "";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// dataId
			$this->dataId->ViewValue = $this->dataId->CurrentValue;
			$this->dataId->ViewCustomAttributes = "";

			// datetime
			$this->datetime->ViewValue = $this->datetime->CurrentValue;
			$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 5);
			$this->datetime->ViewCustomAttributes = "";

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// data_id
			$this->data_id->ViewValue = $this->data_id->CurrentValue;
			$this->data_id->ViewCustomAttributes = "";

			// finished
			$this->finished->ViewValue = $this->finished->CurrentValue;
			$this->finished->ViewValue = ew_FormatDateTime($this->finished->ViewValue, 5);
			$this->finished->ViewCustomAttributes = "";

			// idjobs
			$this->idjobs->LinkCustomAttributes = "";
			$this->idjobs->HrefValue = "";
			$this->idjobs->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// dataId
			$this->dataId->LinkCustomAttributes = "";
			$this->dataId->HrefValue = "";
			$this->dataId->TooltipValue = "";

			// datetime
			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";
			$this->datetime->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// data_id
			$this->data_id->LinkCustomAttributes = "";
			$this->data_id->HrefValue = "";
			$this->data_id->TooltipValue = "";

			// finished
			$this->finished->LinkCustomAttributes = "";
			$this->finished->HrefValue = "";
			$this->finished->TooltipValue = "";
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
				$sThisKey .= $row['idjobs'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "jobshistoricallist.php", $this->TableVar);
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
if (!isset($jobshistorical_delete)) $jobshistorical_delete = new cjobshistorical_delete();

// Page init
$jobshistorical_delete->Page_Init();

// Page main
$jobshistorical_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jobshistorical_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var jobshistorical_delete = new ew_Page("jobshistorical_delete");
jobshistorical_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = jobshistorical_delete.PageID; // For backward compatibility

// Form object
var fjobshistoricaldelete = new ew_Form("fjobshistoricaldelete");

// Form_CustomValidate event
fjobshistoricaldelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjobshistoricaldelete.ValidateRequired = true;
<?php } else { ?>
fjobshistoricaldelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($jobshistorical_delete->Recordset = $jobshistorical_delete->LoadRecordset())
	$jobshistorical_deleteTotalRecs = $jobshistorical_delete->Recordset->RecordCount(); // Get record count
if ($jobshistorical_deleteTotalRecs <= 0) { // No record found, exit
	if ($jobshistorical_delete->Recordset)
		$jobshistorical_delete->Recordset->Close();
	$jobshistorical_delete->Page_Terminate("jobshistoricallist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $jobshistorical_delete->ShowPageHeader(); ?>
<?php
$jobshistorical_delete->ShowMessage();
?>
<form name="fjobshistoricaldelete" id="fjobshistoricaldelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="jobshistorical">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($jobshistorical_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_jobshistoricaldelete" class="ewTable ewTableSeparate">
<?php echo $jobshistorical->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_jobshistorical_idjobs" class="jobshistorical_idjobs"><?php echo $jobshistorical->idjobs->FldCaption() ?></span></td>
		<td><span id="elh_jobshistorical_status" class="jobshistorical_status"><?php echo $jobshistorical->status->FldCaption() ?></span></td>
		<td><span id="elh_jobshistorical_type" class="jobshistorical_type"><?php echo $jobshistorical->type->FldCaption() ?></span></td>
		<td><span id="elh_jobshistorical_dataId" class="jobshistorical_dataId"><?php echo $jobshistorical->dataId->FldCaption() ?></span></td>
		<td><span id="elh_jobshistorical_datetime" class="jobshistorical_datetime"><?php echo $jobshistorical->datetime->FldCaption() ?></span></td>
		<td><span id="elh_jobshistorical_id" class="jobshistorical_id"><?php echo $jobshistorical->id->FldCaption() ?></span></td>
		<td><span id="elh_jobshistorical_data_id" class="jobshistorical_data_id"><?php echo $jobshistorical->data_id->FldCaption() ?></span></td>
		<td><span id="elh_jobshistorical_finished" class="jobshistorical_finished"><?php echo $jobshistorical->finished->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$jobshistorical_delete->RecCnt = 0;
$i = 0;
while (!$jobshistorical_delete->Recordset->EOF) {
	$jobshistorical_delete->RecCnt++;
	$jobshistorical_delete->RowCnt++;

	// Set row properties
	$jobshistorical->ResetAttrs();
	$jobshistorical->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$jobshistorical_delete->LoadRowValues($jobshistorical_delete->Recordset);

	// Render row
	$jobshistorical_delete->RenderRow();
?>
	<tr<?php echo $jobshistorical->RowAttributes() ?>>
		<td<?php echo $jobshistorical->idjobs->CellAttributes() ?>><span id="el<?php echo $jobshistorical_delete->RowCnt ?>_jobshistorical_idjobs" class="control-group jobshistorical_idjobs">
<span<?php echo $jobshistorical->idjobs->ViewAttributes() ?>>
<?php echo $jobshistorical->idjobs->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobshistorical->status->CellAttributes() ?>><span id="el<?php echo $jobshistorical_delete->RowCnt ?>_jobshistorical_status" class="control-group jobshistorical_status">
<span<?php echo $jobshistorical->status->ViewAttributes() ?>>
<?php echo $jobshistorical->status->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobshistorical->type->CellAttributes() ?>><span id="el<?php echo $jobshistorical_delete->RowCnt ?>_jobshistorical_type" class="control-group jobshistorical_type">
<span<?php echo $jobshistorical->type->ViewAttributes() ?>>
<?php echo $jobshistorical->type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobshistorical->dataId->CellAttributes() ?>><span id="el<?php echo $jobshistorical_delete->RowCnt ?>_jobshistorical_dataId" class="control-group jobshistorical_dataId">
<span<?php echo $jobshistorical->dataId->ViewAttributes() ?>>
<?php echo $jobshistorical->dataId->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobshistorical->datetime->CellAttributes() ?>><span id="el<?php echo $jobshistorical_delete->RowCnt ?>_jobshistorical_datetime" class="control-group jobshistorical_datetime">
<span<?php echo $jobshistorical->datetime->ViewAttributes() ?>>
<?php echo $jobshistorical->datetime->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobshistorical->id->CellAttributes() ?>><span id="el<?php echo $jobshistorical_delete->RowCnt ?>_jobshistorical_id" class="control-group jobshistorical_id">
<span<?php echo $jobshistorical->id->ViewAttributes() ?>>
<?php echo $jobshistorical->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobshistorical->data_id->CellAttributes() ?>><span id="el<?php echo $jobshistorical_delete->RowCnt ?>_jobshistorical_data_id" class="control-group jobshistorical_data_id">
<span<?php echo $jobshistorical->data_id->ViewAttributes() ?>>
<?php echo $jobshistorical->data_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $jobshistorical->finished->CellAttributes() ?>><span id="el<?php echo $jobshistorical_delete->RowCnt ?>_jobshistorical_finished" class="control-group jobshistorical_finished">
<span<?php echo $jobshistorical->finished->ViewAttributes() ?>>
<?php echo $jobshistorical->finished->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$jobshistorical_delete->Recordset->MoveNext();
}
$jobshistorical_delete->Recordset->Close();
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
fjobshistoricaldelete.Init();
</script>
<?php
$jobshistorical_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jobshistorical_delete->Page_Terminate();
?>
