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

$jobshistorical_add = NULL; // Initialize page object first

class cjobshistorical_add extends cjobshistorical {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{A78C9F64-F701-4951-8B68-9678633E190C}";

	// Table name
	var $TableName = 'jobshistorical';

	// Page object name
	var $PageObjName = 'jobshistorical_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["idjobs"] != "") {
				$this->idjobs->setQueryStringValue($_GET["idjobs"]);
				$this->setKey("idjobs", $this->idjobs->CurrentValue); // Set up key
			} else {
				$this->setKey("idjobs", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("jobshistoricallist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "jobshistoricalview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
		$this->type->CurrentValue = 0;
		$this->dataId->CurrentValue = 0;
		$this->datetime->CurrentValue = NULL;
		$this->datetime->OldValue = $this->datetime->CurrentValue;
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->exec->CurrentValue = NULL;
		$this->exec->OldValue = $this->exec->CurrentValue;
		$this->data_id->CurrentValue = NULL;
		$this->data_id->OldValue = $this->data_id->CurrentValue;
		$this->finished->CurrentValue = NULL;
		$this->finished->OldValue = $this->finished->CurrentValue;
		$this->resultado->CurrentValue = NULL;
		$this->resultado->OldValue = $this->resultado->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->dataId->FldIsDetailKey) {
			$this->dataId->setFormValue($objForm->GetValue("x_dataId"));
		}
		if (!$this->datetime->FldIsDetailKey) {
			$this->datetime->setFormValue($objForm->GetValue("x_datetime"));
			$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 5);
		}
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->exec->FldIsDetailKey) {
			$this->exec->setFormValue($objForm->GetValue("x_exec"));
		}
		if (!$this->data_id->FldIsDetailKey) {
			$this->data_id->setFormValue($objForm->GetValue("x_data_id"));
		}
		if (!$this->finished->FldIsDetailKey) {
			$this->finished->setFormValue($objForm->GetValue("x_finished"));
			$this->finished->CurrentValue = ew_UnFormatDateTime($this->finished->CurrentValue, 5);
		}
		if (!$this->resultado->FldIsDetailKey) {
			$this->resultado->setFormValue($objForm->GetValue("x_resultado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->status->CurrentValue = $this->status->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->dataId->CurrentValue = $this->dataId->FormValue;
		$this->datetime->CurrentValue = $this->datetime->FormValue;
		$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 5);
		$this->id->CurrentValue = $this->id->FormValue;
		$this->exec->CurrentValue = $this->exec->FormValue;
		$this->data_id->CurrentValue = $this->data_id->FormValue;
		$this->finished->CurrentValue = $this->finished->FormValue;
		$this->finished->CurrentValue = ew_UnFormatDateTime($this->finished->CurrentValue, 5);
		$this->resultado->CurrentValue = $this->resultado->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idjobs")) <> "")
			$this->idjobs->CurrentValue = $this->getKey("idjobs"); // idjobs
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->status->FldTitle()));

			// type
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);
			$this->type->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->type->FldTitle()));

			// dataId
			$this->dataId->EditCustomAttributes = "";
			$this->dataId->EditValue = ew_HtmlEncode($this->dataId->CurrentValue);
			$this->dataId->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dataId->FldTitle()));

			// datetime
			$this->datetime->EditCustomAttributes = "";
			$this->datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime->CurrentValue, 5));
			$this->datetime->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->datetime->FldTitle()));

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldTitle()));

			// exec
			$this->exec->EditCustomAttributes = "";
			$this->exec->EditValue = $this->exec->CurrentValue;
			$this->exec->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->exec->FldTitle()));

			// data_id
			$this->data_id->EditCustomAttributes = "";
			$this->data_id->EditValue = ew_HtmlEncode($this->data_id->CurrentValue);
			$this->data_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->data_id->FldTitle()));

			// finished
			$this->finished->EditCustomAttributes = "";
			$this->finished->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->finished->CurrentValue, 5));
			$this->finished->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->finished->FldTitle()));

			// resultado
			$this->resultado->EditCustomAttributes = "";
			$this->resultado->EditValue = $this->resultado->CurrentValue;
			$this->resultado->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->resultado->FldTitle()));

			// Edit refer script
			// status

			$this->status->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// dataId
			$this->dataId->HrefValue = "";

			// datetime
			$this->datetime->HrefValue = "";

			// id
			$this->id->HrefValue = "";

			// exec
			$this->exec->HrefValue = "";

			// data_id
			$this->data_id->HrefValue = "";

			// finished
			$this->finished->HrefValue = "";

			// resultado
			$this->resultado->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->status->FldCaption());
		}
		if (!ew_CheckInteger($this->status->FormValue)) {
			ew_AddMessage($gsFormError, $this->status->FldErrMsg());
		}
		if (!$this->type->FldIsDetailKey && !is_null($this->type->FormValue) && $this->type->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->type->FldCaption());
		}
		if (!ew_CheckInteger($this->type->FormValue)) {
			ew_AddMessage($gsFormError, $this->type->FldErrMsg());
		}
		if (!$this->dataId->FldIsDetailKey && !is_null($this->dataId->FormValue) && $this->dataId->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dataId->FldCaption());
		}
		if (!ew_CheckInteger($this->dataId->FormValue)) {
			ew_AddMessage($gsFormError, $this->dataId->FldErrMsg());
		}
		if (!ew_CheckDate($this->datetime->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id->FormValue)) {
			ew_AddMessage($gsFormError, $this->id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->data_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->data_id->FldErrMsg());
		}
		if (!ew_CheckDate($this->finished->FormValue)) {
			ew_AddMessage($gsFormError, $this->finished->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, 0, FALSE);

		// type
		$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, 0, strval($this->type->CurrentValue) == "");

		// dataId
		$this->dataId->SetDbValueDef($rsnew, $this->dataId->CurrentValue, 0, strval($this->dataId->CurrentValue) == "");

		// datetime
		$this->datetime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime->CurrentValue, 5), NULL, FALSE);

		// id
		$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, NULL, FALSE);

		// exec
		$this->exec->SetDbValueDef($rsnew, $this->exec->CurrentValue, NULL, FALSE);

		// data_id
		$this->data_id->SetDbValueDef($rsnew, $this->data_id->CurrentValue, NULL, FALSE);

		// finished
		$this->finished->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->finished->CurrentValue, 5), NULL, FALSE);

		// resultado
		$this->resultado->SetDbValueDef($rsnew, $this->resultado->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->idjobs->setDbValue($conn->Insert_ID());
			$rsnew['idjobs'] = $this->idjobs->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "jobshistoricallist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($jobshistorical_add)) $jobshistorical_add = new cjobshistorical_add();

// Page init
$jobshistorical_add->Page_Init();

// Page main
$jobshistorical_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jobshistorical_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var jobshistorical_add = new ew_Page("jobshistorical_add");
jobshistorical_add.PageID = "add"; // Page ID
var EW_PAGE_ID = jobshistorical_add.PageID; // For backward compatibility

// Form object
var fjobshistoricaladd = new ew_Form("fjobshistoricaladd");

// Validate form
fjobshistoricaladd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($jobshistorical->status->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobshistorical->status->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_type");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($jobshistorical->type->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_type");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobshistorical->type->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dataId");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($jobshistorical->dataId->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dataId");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobshistorical->dataId->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_datetime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobshistorical->datetime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobshistorical->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_data_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobshistorical->data_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_finished");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobshistorical->finished->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fjobshistoricaladd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjobshistoricaladd.ValidateRequired = true;
<?php } else { ?>
fjobshistoricaladd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $jobshistorical_add->ShowPageHeader(); ?>
<?php
$jobshistorical_add->ShowMessage();
?>
<form name="fjobshistoricaladd" id="fjobshistoricaladd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="jobshistorical">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_jobshistoricaladd" class="table table-bordered table-striped">
<?php if ($jobshistorical->status->Visible) { // status ?>
	<tr id="r_status"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_status"><?php echo $jobshistorical->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $jobshistorical->status->CellAttributes() ?>><span id="el_jobshistorical_status" class="control-group">
<input type="text" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?php echo $jobshistorical->status->PlaceHolder ?>" value="<?php echo $jobshistorical->status->EditValue ?>"<?php echo $jobshistorical->status->EditAttributes() ?>>
</span><?php echo $jobshistorical->status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_type"><?php echo $jobshistorical->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $jobshistorical->type->CellAttributes() ?>><span id="el_jobshistorical_type" class="control-group">
<input type="text" data-field="x_type" name="x_type" id="x_type" size="30" placeholder="<?php echo $jobshistorical->type->PlaceHolder ?>" value="<?php echo $jobshistorical->type->EditValue ?>"<?php echo $jobshistorical->type->EditAttributes() ?>>
</span><?php echo $jobshistorical->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->dataId->Visible) { // dataId ?>
	<tr id="r_dataId"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_dataId"><?php echo $jobshistorical->dataId->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $jobshistorical->dataId->CellAttributes() ?>><span id="el_jobshistorical_dataId" class="control-group">
<input type="text" data-field="x_dataId" name="x_dataId" id="x_dataId" size="30" placeholder="<?php echo $jobshistorical->dataId->PlaceHolder ?>" value="<?php echo $jobshistorical->dataId->EditValue ?>"<?php echo $jobshistorical->dataId->EditAttributes() ?>>
</span><?php echo $jobshistorical->dataId->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->datetime->Visible) { // datetime ?>
	<tr id="r_datetime"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_datetime"><?php echo $jobshistorical->datetime->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->datetime->CellAttributes() ?>><span id="el_jobshistorical_datetime" class="control-group">
<input type="text" data-field="x_datetime" name="x_datetime" id="x_datetime" placeholder="<?php echo $jobshistorical->datetime->PlaceHolder ?>" value="<?php echo $jobshistorical->datetime->EditValue ?>"<?php echo $jobshistorical->datetime->EditAttributes() ?>>
</span><?php echo $jobshistorical->datetime->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_id"><?php echo $jobshistorical->id->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->id->CellAttributes() ?>><span id="el_jobshistorical_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $jobshistorical->id->PlaceHolder ?>" value="<?php echo $jobshistorical->id->EditValue ?>"<?php echo $jobshistorical->id->EditAttributes() ?>>
</span><?php echo $jobshistorical->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->exec->Visible) { // exec ?>
	<tr id="r_exec"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_exec"><?php echo $jobshistorical->exec->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->exec->CellAttributes() ?>><span id="el_jobshistorical_exec" class="control-group">
<textarea data-field="x_exec" name="x_exec" id="x_exec" cols="35" rows="4" placeholder="<?php echo $jobshistorical->exec->PlaceHolder ?>"<?php echo $jobshistorical->exec->EditAttributes() ?>><?php echo $jobshistorical->exec->EditValue ?></textarea>
</span><?php echo $jobshistorical->exec->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->data_id->Visible) { // data_id ?>
	<tr id="r_data_id"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_data_id"><?php echo $jobshistorical->data_id->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->data_id->CellAttributes() ?>><span id="el_jobshistorical_data_id" class="control-group">
<input type="text" data-field="x_data_id" name="x_data_id" id="x_data_id" size="30" placeholder="<?php echo $jobshistorical->data_id->PlaceHolder ?>" value="<?php echo $jobshistorical->data_id->EditValue ?>"<?php echo $jobshistorical->data_id->EditAttributes() ?>>
</span><?php echo $jobshistorical->data_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->finished->Visible) { // finished ?>
	<tr id="r_finished"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_finished"><?php echo $jobshistorical->finished->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->finished->CellAttributes() ?>><span id="el_jobshistorical_finished" class="control-group">
<input type="text" data-field="x_finished" name="x_finished" id="x_finished" placeholder="<?php echo $jobshistorical->finished->PlaceHolder ?>" value="<?php echo $jobshistorical->finished->EditValue ?>"<?php echo $jobshistorical->finished->EditAttributes() ?>>
</span><?php echo $jobshistorical->finished->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobshistorical->resultado->Visible) { // resultado ?>
	<tr id="r_resultado"<?php echo $jobshistorical->RowAttributes() ?>>
		<td><span id="elh_jobshistorical_resultado"><?php echo $jobshistorical->resultado->FldCaption() ?></span></td>
		<td<?php echo $jobshistorical->resultado->CellAttributes() ?>><span id="el_jobshistorical_resultado" class="control-group">
<textarea data-field="x_resultado" name="x_resultado" id="x_resultado" cols="35" rows="4" placeholder="<?php echo $jobshistorical->resultado->PlaceHolder ?>"<?php echo $jobshistorical->resultado->EditAttributes() ?>><?php echo $jobshistorical->resultado->EditValue ?></textarea>
</span><?php echo $jobshistorical->resultado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fjobshistoricaladd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$jobshistorical_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jobshistorical_add->Page_Terminate();
?>
