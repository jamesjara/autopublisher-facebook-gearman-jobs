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

$jobshistorical_view = NULL; // Initialize page object first

class cjobshistorical_view extends cjobshistorical {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{A78C9F64-F701-4951-8B68-9678633E190C}";

	// Table name
	var $TableName = 'jobshistorical';

	// Page object name
	var $PageObjName = 'jobshistorical_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["idjobs"] <> "") {
			$this->RecKey["idjobs"] = $_GET["idjobs"];
			$KeyUrl .= "&idjobs=" . urlencode($this->RecKey["idjobs"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jobshistorical', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idjobs"] <> "") {
				$this->idjobs->setQueryStringValue($_GET["idjobs"]);
				$this->RecKey["idjobs"] = $this->idjobs->QueryStringValue;
			} else {
				$sReturnUrl = "jobshistoricallist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "jobshistoricallist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "jobshistoricallist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// exec
			$this->exec->ViewValue = $this->exec->CurrentValue;
			$this->exec->ViewCustomAttributes = "";

			// data_id
			$this->data_id->ViewValue = $this->data_id->CurrentValue;
			$this->data_id->ViewCustomAttributes = "";

			// finished
			$this->finished->ViewValue = $this->finished->CurrentValue;
			$this->finished->ViewValue = ew_FormatDateTime($this->finished->ViewValue, 5);
			$this->finished->ViewCustomAttributes = "";

			// resultado
			$this->resultado->ViewValue = $this->resultado->CurrentValue;
			$this->resultado->ViewCustomAttributes = "";

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

			// exec
			$this->exec->LinkCustomAttributes = "";
			$this->exec->HrefValue = "";
			$this->exec->TooltipValue = "";

			// data_id
			$this->data_id->LinkCustomAttributes = "";
			$this->data_id->HrefValue = "";
			$this->data_id->TooltipValue = "";

			// finished
			$this->finished->LinkCustomAttributes = "";
			$this->finished->HrefValue = "";
			$this->finished->TooltipValue = "";

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";
			$this->resultado->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "jobshistoricallist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("view");
		$Breadcrumb->Add("view", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($jobshistorical_view)) $jobshistorical_view = new cjobshistorical_view();

// Page init
$jobshistorical_view->Page_Init();

// Page main
$jobshistorical_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jobshistorical_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var jobshistorical_view = new ew_Page("jobshistorical_view");
jobshistorical_view.PageID = "view"; // Page ID
var EW_PAGE_ID = jobshistorical_view.PageID; // For backward compatibility

// Form object
var fjobshistoricalview = new ew_Form("fjobshistoricalview");

// Form_CustomValidate event
fjobshistoricalview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjobshistoricalview.ValidateRequired = true;
<?php } else { ?>
fjobshistoricalview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $jobshistorical_view->ExportOptions->Render("body") ?>
<?php if (!$jobshistorical_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($jobshistorical_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $jobshistorical_view->ShowPageHeader(); ?>
<?php
$jobshistorical_view->ShowMessage();
?>
<form name="fjobshistoricalview" id="fjobshistoricalview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="jobshistorical">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_jobshistoricalview" class="table table-bordered table-striped">
<?php if ($jobshistorical->idjobs->Visible) { // idjobs ?>
	<tr id="r_idjobs"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_idjobs"><?php echo $jobshistorical->idjobs->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->idjobs->CellAttributes() ?>><span id="el_jobshistorical_idjobs" class="control-group">
<span<?php echo $jobshistorical->idjobs->ViewAttributes() ?>>
<?php echo $jobshistorical->idjobs->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->status->Visible) { // status ?>
	<tr id="r_status"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_status"><?php echo $jobshistorical->status->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->status->CellAttributes() ?>><span id="el_jobshistorical_status" class="control-group">
<span<?php echo $jobshistorical->status->ViewAttributes() ?>>
<?php echo $jobshistorical->status->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_type"><?php echo $jobshistorical->type->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->type->CellAttributes() ?>><span id="el_jobshistorical_type" class="control-group">
<span<?php echo $jobshistorical->type->ViewAttributes() ?>>
<?php echo $jobshistorical->type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->dataId->Visible) { // dataId ?>
	<tr id="r_dataId"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_dataId"><?php echo $jobshistorical->dataId->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->dataId->CellAttributes() ?>><span id="el_jobshistorical_dataId" class="control-group">
<span<?php echo $jobshistorical->dataId->ViewAttributes() ?>>
<?php echo $jobshistorical->dataId->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->datetime->Visible) { // datetime ?>
	<tr id="r_datetime"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_datetime"><?php echo $jobshistorical->datetime->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->datetime->CellAttributes() ?>><span id="el_jobshistorical_datetime" class="control-group">
<span<?php echo $jobshistorical->datetime->ViewAttributes() ?>>
<?php echo $jobshistorical->datetime->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_id"><?php echo $jobshistorical->id->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->id->CellAttributes() ?>><span id="el_jobshistorical_id" class="control-group">
<span<?php echo $jobshistorical->id->ViewAttributes() ?>>
<?php echo $jobshistorical->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->exec->Visible) { // exec ?>
	<tr id="r_exec"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_exec"><?php echo $jobshistorical->exec->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->exec->CellAttributes() ?>><span id="el_jobshistorical_exec" class="control-group">
<span<?php echo $jobshistorical->exec->ViewAttributes() ?>>
<?php echo $jobshistorical->exec->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->data_id->Visible) { // data_id ?>
	<tr id="r_data_id"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_data_id"><?php echo $jobshistorical->data_id->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->data_id->CellAttributes() ?>><span id="el_jobshistorical_data_id" class="control-group">
<span<?php echo $jobshistorical->data_id->ViewAttributes() ?>>
<?php echo $jobshistorical->data_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->finished->Visible) { // finished ?>
	<tr id="r_finished"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_finished"><?php echo $jobshistorical->finished->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->finished->CellAttributes() ?>><span id="el_jobshistorical_finished" class="control-group">
<span<?php echo $jobshistorical->finished->ViewAttributes() ?>>
<?php echo $jobshistorical->finished->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->resultado->Visible) { // resultado ?>
	<tr id="r_resultado"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_resultado"><?php echo $jobshistorical->resultado->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->resultado->CellAttributes() ?>><span id="el_jobshistorical_resultado" class="control-group">
<span<?php echo $jobshistorical->resultado->ViewAttributes() ?>>
<?php echo $jobshistorical->resultado->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fjobshistoricalview.Init();
</script>
<?php
$jobshistorical_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jobshistorical_view->Page_Terminate();
?>
