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

$jobs_edit = NULL; // Initialize page object first

class cjobs_edit extends cjobs {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{A78C9F64-F701-4951-8B68-9678633E190C}";

	// Table name
	var $TableName = 'jobs';

	// Page object name
	var $PageObjName = 'jobs_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("jobslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("jobslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->targetid->FldIsDetailKey) {
			$this->targetid->setFormValue($objForm->GetValue("x_targetid"));
		}
		if (!$this->credencial->FldIsDetailKey) {
			$this->credencial->setFormValue($objForm->GetValue("x_credencial"));
		}
		if (!$this->dataId->FldIsDetailKey) {
			$this->dataId->setFormValue($objForm->GetValue("x_dataId"));
		}
		if (!$this->datetime->FldIsDetailKey) {
			$this->datetime->setFormValue($objForm->GetValue("x_datetime"));
			$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 9);
		}
		if (!$this->sessionid->FldIsDetailKey) {
			$this->sessionid->setFormValue($objForm->GetValue("x_sessionid"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->targetid->CurrentValue = $this->targetid->FormValue;
		$this->credencial->CurrentValue = $this->credencial->FormValue;
		$this->dataId->CurrentValue = $this->dataId->FormValue;
		$this->datetime->CurrentValue = $this->datetime->FormValue;
		$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 9);
		$this->sessionid->CurrentValue = $this->sessionid->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// type
			$this->type->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "es":
					$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `domains`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `domains`";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->type->EditValue = $arwrk;

			// targetid
			$this->targetid->EditCustomAttributes = "";
			if (trim(strval($this->targetid->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`domainid`" . ew_SearchString("=", $this->targetid->CurrentValue, EW_DATATYPE_NUMBER);
			}
			switch (@$gsLanguage) {
				case "es":
					$sSqlWrk = "SELECT `domainid`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `domainid` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `targets`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `domainid`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `domainid` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `targets`";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->targetid->EditValue = $arwrk;

			// credencial
			$this->credencial->EditCustomAttributes = "";
			if (trim(strval($this->credencial->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`domainid`" . ew_SearchString("=", $this->credencial->CurrentValue, EW_DATATYPE_NUMBER);
			}
			switch (@$gsLanguage) {
				case "es":
					$sSqlWrk = "SELECT `domainid`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `domainid` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `credenciales`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `domainid`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `domainid` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `credenciales`";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->credencial->EditValue = $arwrk;

			// dataId
			$this->dataId->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "es":
					$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `posts`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `posts`";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->dataId->EditValue = $arwrk;

			// datetime
			$this->datetime->EditCustomAttributes = "";
			$this->datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime->CurrentValue, 9));
			$this->datetime->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->datetime->FldTitle()));

			// sessionid
			$this->sessionid->EditCustomAttributes = "";
			$this->sessionid->EditValue = ew_HtmlEncode($this->sessionid->CurrentValue);
			$this->sessionid->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->sessionid->FldTitle()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// targetid
			$this->targetid->HrefValue = "";

			// credencial
			$this->credencial->HrefValue = "";

			// dataId
			$this->dataId->HrefValue = "";

			// datetime
			$this->datetime->HrefValue = "";

			// sessionid
			$this->sessionid->HrefValue = "";
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
		if (!$this->type->FldIsDetailKey && !is_null($this->type->FormValue) && $this->type->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->type->FldCaption());
		}
		if (!$this->dataId->FldIsDetailKey && !is_null($this->dataId->FormValue) && $this->dataId->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dataId->FldCaption());
		}
		if (!$this->datetime->FldIsDetailKey && !is_null($this->datetime->FormValue) && $this->datetime->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->datetime->FldCaption());
		}
		if (!ew_CheckDate($this->datetime->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, 0, $this->type->ReadOnly);

			// targetid
			$this->targetid->SetDbValueDef($rsnew, $this->targetid->CurrentValue, NULL, $this->targetid->ReadOnly);

			// credencial
			$this->credencial->SetDbValueDef($rsnew, $this->credencial->CurrentValue, NULL, $this->credencial->ReadOnly);

			// dataId
			$this->dataId->SetDbValueDef($rsnew, $this->dataId->CurrentValue, 0, $this->dataId->ReadOnly);

			// datetime
			$this->datetime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime->CurrentValue, 9), NULL, $this->datetime->ReadOnly);

			// sessionid
			$this->sessionid->SetDbValueDef($rsnew, $this->sessionid->CurrentValue, NULL, $this->sessionid->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "jobslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($jobs_edit)) $jobs_edit = new cjobs_edit();

// Page init
$jobs_edit->Page_Init();

// Page main
$jobs_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jobs_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var jobs_edit = new ew_Page("jobs_edit");
jobs_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = jobs_edit.PageID; // For backward compatibility

// Form object
var fjobsedit = new ew_Form("fjobsedit");

// Validate form
fjobsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_type");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($jobs->type->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dataId");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($jobs->dataId->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_datetime");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($jobs->datetime->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_datetime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobs->datetime->FldErrMsg()) ?>");

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
fjobsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjobsedit.ValidateRequired = true;
<?php } else { ?>
fjobsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fjobsedit.Lists["x_type"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fjobsedit.Lists["x_targetid"] = {"LinkField":"x_domainid","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":["x_type"],"FilterFields":["x_domainid"],"Options":[]};
fjobsedit.Lists["x_credencial"] = {"LinkField":"x_domainid","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":["x_type"],"FilterFields":["x_domainid"],"Options":[]};
fjobsedit.Lists["x_dataId"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $jobs_edit->ShowPageHeader(); ?>
<?php
$jobs_edit->ShowMessage();
?>
<form name="fjobsedit" id="fjobsedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="jobs">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_jobsedit" class="table table-bordered table-striped">
<?php if ($jobs->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $jobs->RowAttributes() ?>>
		<td><span id="elh_jobs_id"><?php echo $jobs->id->FldCaption() ?></span></td>
		<td<?php echo $jobs->id->CellAttributes() ?>><span id="el_jobs_id" class="control-group">
<span<?php echo $jobs->id->ViewAttributes() ?>>
<?php echo $jobs->id->EditValue ?></span>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($jobs->id->CurrentValue) ?>">
</span><?php echo $jobs->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobs->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $jobs->RowAttributes() ?>>
		<td><span id="elh_jobs_type"><?php echo $jobs->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $jobs->type->CellAttributes() ?>><span id="el_jobs_type" class="control-group">
<?php $jobs->type->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_targetid','x_credencial']); " . @$jobs->type->EditAttrs["onchange"]; ?>
<select data-field="x_type" id="x_type" name="x_type"<?php echo $jobs->type->EditAttributes() ?>>
<?php
if (is_array($jobs->type->EditValue)) {
	$arwrk = $jobs->type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($jobs->type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fjobsedit.Lists["x_type"].Options = <?php echo (is_array($jobs->type->EditValue)) ? ew_ArrayToJson($jobs->type->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $jobs->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobs->targetid->Visible) { // targetid ?>
	<tr id="r_targetid"<?php echo $jobs->RowAttributes() ?>>
		<td><span id="elh_jobs_targetid"><?php echo $jobs->targetid->FldCaption() ?></span></td>
		<td<?php echo $jobs->targetid->CellAttributes() ?>><span id="el_jobs_targetid" class="control-group">
<select data-field="x_targetid" id="x_targetid" name="x_targetid"<?php echo $jobs->targetid->EditAttributes() ?>>
<?php
if (is_array($jobs->targetid->EditValue)) {
	$arwrk = $jobs->targetid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($jobs->targetid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "es":
		$sSqlWrk = "SELECT `domainid`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `targets`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `domainid`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `targets`";
		$sWhereWrk = "{filter}";
		break;
}

// Call Lookup selecting
$jobs->Lookup_Selecting($jobs->targetid, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_targetid" id="s_x_targetid" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`domainid` = {filter_value}"); ?>&t0=3&f1=<?php echo ew_Encrypt("`domainid` IN ({filter_value})"); ?>&t1=3">
</span><?php echo $jobs->targetid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobs->credencial->Visible) { // credencial ?>
	<tr id="r_credencial"<?php echo $jobs->RowAttributes() ?>>
		<td><span id="elh_jobs_credencial"><?php echo $jobs->credencial->FldCaption() ?></span></td>
		<td<?php echo $jobs->credencial->CellAttributes() ?>><span id="el_jobs_credencial" class="control-group">
<select data-field="x_credencial" id="x_credencial" name="x_credencial"<?php echo $jobs->credencial->EditAttributes() ?>>
<?php
if (is_array($jobs->credencial->EditValue)) {
	$arwrk = $jobs->credencial->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($jobs->credencial->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "es":
		$sSqlWrk = "SELECT `domainid`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `credenciales`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `domainid`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `credenciales`";
		$sWhereWrk = "{filter}";
		break;
}

// Call Lookup selecting
$jobs->Lookup_Selecting($jobs->credencial, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_credencial" id="s_x_credencial" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`domainid` = {filter_value}"); ?>&t0=3&f1=<?php echo ew_Encrypt("`domainid` IN ({filter_value})"); ?>&t1=3">
</span><?php echo $jobs->credencial->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobs->dataId->Visible) { // dataId ?>
	<tr id="r_dataId"<?php echo $jobs->RowAttributes() ?>>
		<td><span id="elh_jobs_dataId"><?php echo $jobs->dataId->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $jobs->dataId->CellAttributes() ?>><span id="el_jobs_dataId" class="control-group">
<select data-field="x_dataId" id="x_dataId" name="x_dataId"<?php echo $jobs->dataId->EditAttributes() ?>>
<?php
if (is_array($jobs->dataId->EditValue)) {
	$arwrk = $jobs->dataId->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($jobs->dataId->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
&nbsp;<a id="aol_x_dataId" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_dataId',url:'postsaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $jobs->dataId->FldCaption() ?></a>
<script type="text/javascript">
fjobsedit.Lists["x_dataId"].Options = <?php echo (is_array($jobs->dataId->EditValue)) ? ew_ArrayToJson($jobs->dataId->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $jobs->dataId->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobs->datetime->Visible) { // datetime ?>
	<tr id="r_datetime"<?php echo $jobs->RowAttributes() ?>>
		<td><span id="elh_jobs_datetime"><?php echo $jobs->datetime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $jobs->datetime->CellAttributes() ?>><span id="el_jobs_datetime" class="control-group">
<input type="text" data-field="x_datetime" name="x_datetime" id="x_datetime" placeholder="<?php echo $jobs->datetime->PlaceHolder ?>" value="<?php echo $jobs->datetime->EditValue ?>"<?php echo $jobs->datetime->EditAttributes() ?>>
<?php if (!$jobs->datetime->ReadOnly && !$jobs->datetime->Disabled && @$jobs->datetime->EditAttrs["readonly"] == "" && @$jobs->datetime->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_datetime" name="cal_x_datetime" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_datetime" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fjobsedit", "x_datetime", "%Y/%m/%d %H:%M:%S");
</script>
<?php } ?>
</span><?php echo $jobs->datetime->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($jobs->sessionid->Visible) { // sessionid ?>
	<tr id="r_sessionid"<?php echo $jobs->RowAttributes() ?>>
		<td><span id="elh_jobs_sessionid"><?php echo $jobs->sessionid->FldCaption() ?></span></td>
		<td<?php echo $jobs->sessionid->CellAttributes() ?>><span id="el_jobs_sessionid" class="control-group">
<input type="text" data-field="x_sessionid" name="x_sessionid" id="x_sessionid" size="30" maxlength="45" placeholder="<?php echo $jobs->sessionid->PlaceHolder ?>" value="<?php echo $jobs->sessionid->EditValue ?>"<?php echo $jobs->sessionid->EditAttributes() ?>>
</span><?php echo $jobs->sessionid->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fjobsedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$jobs_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jobs_edit->Page_Terminate();
?>
