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

$jobshistorical_list = NULL; // Initialize page object first

class cjobshistorical_list extends cjobshistorical {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{A78C9F64-F701-4951-8B68-9678633E190C}";

	// Table name
	var $TableName = 'jobshistorical';

	// Page object name
	var $PageObjName = 'jobshistorical_list';

	// Grid form hidden field names
	var $FormName = 'fjobshistoricallist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "jobshistoricaladd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "jobshistoricaldelete.php";
		$this->MultiUpdateUrl = "jobshistoricalupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jobshistorical', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "span";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->idjobs->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->idjobs->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idjobs->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idjobs); // idjobs
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->dataId); // dataId
			$this->UpdateSort($this->datetime); // datetime
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->data_id); // data_id
			$this->UpdateSort($this->finished); // finished
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idjobs->setSort("");
				$this->status->setSort("");
				$this->type->setSort("");
				$this->dataId->setSort("");
				$this->datetime->setSort("");
				$this->id->setSort("");
				$this->data_id->setSort("");
				$this->finished->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		if (count($this->CustomActions) > 0) $item->Visible = TRUE;
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fjobshistoricallist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($jobshistorical_list)) $jobshistorical_list = new cjobshistorical_list();

// Page init
$jobshistorical_list->Page_Init();

// Page main
$jobshistorical_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jobshistorical_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var jobshistorical_list = new ew_Page("jobshistorical_list");
jobshistorical_list.PageID = "list"; // Page ID
var EW_PAGE_ID = jobshistorical_list.PageID; // For backward compatibility

// Form object
var fjobshistoricallist = new ew_Form("fjobshistoricallist");
fjobshistoricallist.FormKeyCountName = '<?php echo $jobshistorical_list->FormKeyCountName ?>';

// Form_CustomValidate event
fjobshistoricallist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjobshistoricallist.ValidateRequired = true;
<?php } else { ?>
fjobshistoricallist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($jobshistorical_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $jobshistorical_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$jobshistorical_list->TotalRecs = $jobshistorical->SelectRecordCount();
	} else {
		if ($jobshistorical_list->Recordset = $jobshistorical_list->LoadRecordset())
			$jobshistorical_list->TotalRecs = $jobshistorical_list->Recordset->RecordCount();
	}
	$jobshistorical_list->StartRec = 1;
	if ($jobshistorical_list->DisplayRecs <= 0 || ($jobshistorical->Export <> "" && $jobshistorical->ExportAll)) // Display all records
		$jobshistorical_list->DisplayRecs = $jobshistorical_list->TotalRecs;
	if (!($jobshistorical->Export <> "" && $jobshistorical->ExportAll))
		$jobshistorical_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$jobshistorical_list->Recordset = $jobshistorical_list->LoadRecordset($jobshistorical_list->StartRec-1, $jobshistorical_list->DisplayRecs);
$jobshistorical_list->RenderOtherOptions();
?>
<?php $jobshistorical_list->ShowPageHeader(); ?>
<?php
$jobshistorical_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fjobshistoricallist" id="fjobshistoricallist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="jobshistorical">
<div id="gmp_jobshistorical" class="ewGridMiddlePanel">
<?php if ($jobshistorical_list->TotalRecs > 0) { ?>
<table id="tbl_jobshistoricallist" class="ewTable ewTableSeparate">
<?php echo $jobshistorical->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$jobshistorical_list->RenderListOptions();

// Render list options (header, left)
$jobshistorical_list->ListOptions->Render("header", "left");
?>
<?php if ($jobshistorical->idjobs->Visible) { // idjobs ?>
	<?php if ($jobshistorical->SortUrl($jobshistorical->idjobs) == "") { ?>
		<td><div id="elh_jobshistorical_idjobs" class="jobshistorical_idjobs"><div class="ewTableHeaderCaption"><?php echo $jobshistorical->idjobs->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jobshistorical->SortUrl($jobshistorical->idjobs) ?>',1);"><div id="elh_jobshistorical_idjobs" class="jobshistorical_idjobs">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jobshistorical->idjobs->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jobshistorical->idjobs->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jobshistorical->idjobs->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($jobshistorical->status->Visible) { // status ?>
	<?php if ($jobshistorical->SortUrl($jobshistorical->status) == "") { ?>
		<td><div id="elh_jobshistorical_status" class="jobshistorical_status"><div class="ewTableHeaderCaption"><?php echo $jobshistorical->status->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jobshistorical->SortUrl($jobshistorical->status) ?>',1);"><div id="elh_jobshistorical_status" class="jobshistorical_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jobshistorical->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jobshistorical->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jobshistorical->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($jobshistorical->type->Visible) { // type ?>
	<?php if ($jobshistorical->SortUrl($jobshistorical->type) == "") { ?>
		<td><div id="elh_jobshistorical_type" class="jobshistorical_type"><div class="ewTableHeaderCaption"><?php echo $jobshistorical->type->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jobshistorical->SortUrl($jobshistorical->type) ?>',1);"><div id="elh_jobshistorical_type" class="jobshistorical_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jobshistorical->type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jobshistorical->type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jobshistorical->type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($jobshistorical->dataId->Visible) { // dataId ?>
	<?php if ($jobshistorical->SortUrl($jobshistorical->dataId) == "") { ?>
		<td><div id="elh_jobshistorical_dataId" class="jobshistorical_dataId"><div class="ewTableHeaderCaption"><?php echo $jobshistorical->dataId->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jobshistorical->SortUrl($jobshistorical->dataId) ?>',1);"><div id="elh_jobshistorical_dataId" class="jobshistorical_dataId">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jobshistorical->dataId->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jobshistorical->dataId->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jobshistorical->dataId->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($jobshistorical->datetime->Visible) { // datetime ?>
	<?php if ($jobshistorical->SortUrl($jobshistorical->datetime) == "") { ?>
		<td><div id="elh_jobshistorical_datetime" class="jobshistorical_datetime"><div class="ewTableHeaderCaption"><?php echo $jobshistorical->datetime->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jobshistorical->SortUrl($jobshistorical->datetime) ?>',1);"><div id="elh_jobshistorical_datetime" class="jobshistorical_datetime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jobshistorical->datetime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jobshistorical->datetime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jobshistorical->datetime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($jobshistorical->id->Visible) { // id ?>
	<?php if ($jobshistorical->SortUrl($jobshistorical->id) == "") { ?>
		<td><div id="elh_jobshistorical_id" class="jobshistorical_id"><div class="ewTableHeaderCaption"><?php echo $jobshistorical->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jobshistorical->SortUrl($jobshistorical->id) ?>',1);"><div id="elh_jobshistorical_id" class="jobshistorical_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jobshistorical->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jobshistorical->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jobshistorical->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($jobshistorical->data_id->Visible) { // data_id ?>
	<?php if ($jobshistorical->SortUrl($jobshistorical->data_id) == "") { ?>
		<td><div id="elh_jobshistorical_data_id" class="jobshistorical_data_id"><div class="ewTableHeaderCaption"><?php echo $jobshistorical->data_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jobshistorical->SortUrl($jobshistorical->data_id) ?>',1);"><div id="elh_jobshistorical_data_id" class="jobshistorical_data_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jobshistorical->data_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jobshistorical->data_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jobshistorical->data_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($jobshistorical->finished->Visible) { // finished ?>
	<?php if ($jobshistorical->SortUrl($jobshistorical->finished) == "") { ?>
		<td><div id="elh_jobshistorical_finished" class="jobshistorical_finished"><div class="ewTableHeaderCaption"><?php echo $jobshistorical->finished->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jobshistorical->SortUrl($jobshistorical->finished) ?>',1);"><div id="elh_jobshistorical_finished" class="jobshistorical_finished">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jobshistorical->finished->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jobshistorical->finished->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jobshistorical->finished->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$jobshistorical_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($jobshistorical->ExportAll && $jobshistorical->Export <> "") {
	$jobshistorical_list->StopRec = $jobshistorical_list->TotalRecs;
} else {

	// Set the last record to display
	if ($jobshistorical_list->TotalRecs > $jobshistorical_list->StartRec + $jobshistorical_list->DisplayRecs - 1)
		$jobshistorical_list->StopRec = $jobshistorical_list->StartRec + $jobshistorical_list->DisplayRecs - 1;
	else
		$jobshistorical_list->StopRec = $jobshistorical_list->TotalRecs;
}
$jobshistorical_list->RecCnt = $jobshistorical_list->StartRec - 1;
if ($jobshistorical_list->Recordset && !$jobshistorical_list->Recordset->EOF) {
	$jobshistorical_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $jobshistorical_list->StartRec > 1)
		$jobshistorical_list->Recordset->Move($jobshistorical_list->StartRec - 1);
} elseif (!$jobshistorical->AllowAddDeleteRow && $jobshistorical_list->StopRec == 0) {
	$jobshistorical_list->StopRec = $jobshistorical->GridAddRowCount;
}

// Initialize aggregate
$jobshistorical->RowType = EW_ROWTYPE_AGGREGATEINIT;
$jobshistorical->ResetAttrs();
$jobshistorical_list->RenderRow();
while ($jobshistorical_list->RecCnt < $jobshistorical_list->StopRec) {
	$jobshistorical_list->RecCnt++;
	if (intval($jobshistorical_list->RecCnt) >= intval($jobshistorical_list->StartRec)) {
		$jobshistorical_list->RowCnt++;

		// Set up key count
		$jobshistorical_list->KeyCount = $jobshistorical_list->RowIndex;

		// Init row class and style
		$jobshistorical->ResetAttrs();
		$jobshistorical->CssClass = "";
		if ($jobshistorical->CurrentAction == "gridadd") {
		} else {
			$jobshistorical_list->LoadRowValues($jobshistorical_list->Recordset); // Load row values
		}
		$jobshistorical->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$jobshistorical->RowAttrs = array_merge($jobshistorical->RowAttrs, array('data-rowindex'=>$jobshistorical_list->RowCnt, 'id'=>'r' . $jobshistorical_list->RowCnt . '_jobshistorical', 'data-rowtype'=>$jobshistorical->RowType));

		// Render row
		$jobshistorical_list->RenderRow();

		// Render list options
		$jobshistorical_list->RenderListOptions();
?>
	<tr<?php echo $jobshistorical->RowAttributes() ?>>
<?php

// Render list options (body, left)
$jobshistorical_list->ListOptions->Render("body", "left", $jobshistorical_list->RowCnt);
?>
	<?php if ($jobshistorical->idjobs->Visible) { // idjobs ?>
		<td<?php echo $jobshistorical->idjobs->CellAttributes() ?>><span id="el<?php echo $jobshistorical_list->RowCnt ?>_jobshistorical_idjobs" class="control-group jobshistorical_idjobs">
<span<?php echo $jobshistorical->idjobs->ViewAttributes() ?>>
<?php echo $jobshistorical->idjobs->ListViewValue() ?></span>
</span><a id="<?php echo $jobshistorical_list->PageObjName . "_row_" . $jobshistorical_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jobshistorical->status->Visible) { // status ?>
		<td<?php echo $jobshistorical->status->CellAttributes() ?>><span id="el<?php echo $jobshistorical_list->RowCnt ?>_jobshistorical_status" class="control-group jobshistorical_status">
<span<?php echo $jobshistorical->status->ViewAttributes() ?>>
<?php echo $jobshistorical->status->ListViewValue() ?></span>
</span><a id="<?php echo $jobshistorical_list->PageObjName . "_row_" . $jobshistorical_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jobshistorical->type->Visible) { // type ?>
		<td<?php echo $jobshistorical->type->CellAttributes() ?>><span id="el<?php echo $jobshistorical_list->RowCnt ?>_jobshistorical_type" class="control-group jobshistorical_type">
<span<?php echo $jobshistorical->type->ViewAttributes() ?>>
<?php echo $jobshistorical->type->ListViewValue() ?></span>
</span><a id="<?php echo $jobshistorical_list->PageObjName . "_row_" . $jobshistorical_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jobshistorical->dataId->Visible) { // dataId ?>
		<td<?php echo $jobshistorical->dataId->CellAttributes() ?>><span id="el<?php echo $jobshistorical_list->RowCnt ?>_jobshistorical_dataId" class="control-group jobshistorical_dataId">
<span<?php echo $jobshistorical->dataId->ViewAttributes() ?>>
<?php echo $jobshistorical->dataId->ListViewValue() ?></span>
</span><a id="<?php echo $jobshistorical_list->PageObjName . "_row_" . $jobshistorical_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jobshistorical->datetime->Visible) { // datetime ?>
		<td<?php echo $jobshistorical->datetime->CellAttributes() ?>><span id="el<?php echo $jobshistorical_list->RowCnt ?>_jobshistorical_datetime" class="control-group jobshistorical_datetime">
<span<?php echo $jobshistorical->datetime->ViewAttributes() ?>>
<?php echo $jobshistorical->datetime->ListViewValue() ?></span>
</span><a id="<?php echo $jobshistorical_list->PageObjName . "_row_" . $jobshistorical_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jobshistorical->id->Visible) { // id ?>
		<td<?php echo $jobshistorical->id->CellAttributes() ?>><span id="el<?php echo $jobshistorical_list->RowCnt ?>_jobshistorical_id" class="control-group jobshistorical_id">
<span<?php echo $jobshistorical->id->ViewAttributes() ?>>
<?php echo $jobshistorical->id->ListViewValue() ?></span>
</span><a id="<?php echo $jobshistorical_list->PageObjName . "_row_" . $jobshistorical_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jobshistorical->data_id->Visible) { // data_id ?>
		<td<?php echo $jobshistorical->data_id->CellAttributes() ?>><span id="el<?php echo $jobshistorical_list->RowCnt ?>_jobshistorical_data_id" class="control-group jobshistorical_data_id">
<span<?php echo $jobshistorical->data_id->ViewAttributes() ?>>
<?php echo $jobshistorical->data_id->ListViewValue() ?></span>
</span><a id="<?php echo $jobshistorical_list->PageObjName . "_row_" . $jobshistorical_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jobshistorical->finished->Visible) { // finished ?>
		<td<?php echo $jobshistorical->finished->CellAttributes() ?>><span id="el<?php echo $jobshistorical_list->RowCnt ?>_jobshistorical_finished" class="control-group jobshistorical_finished">
<span<?php echo $jobshistorical->finished->ViewAttributes() ?>>
<?php echo $jobshistorical->finished->ListViewValue() ?></span>
</span><a id="<?php echo $jobshistorical_list->PageObjName . "_row_" . $jobshistorical_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$jobshistorical_list->ListOptions->Render("body", "right", $jobshistorical_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($jobshistorical->CurrentAction <> "gridadd")
		$jobshistorical_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($jobshistorical->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($jobshistorical_list->Recordset)
	$jobshistorical_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($jobshistorical->CurrentAction <> "gridadd" && $jobshistorical->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($jobshistorical_list->Pager)) $jobshistorical_list->Pager = new cPrevNextPager($jobshistorical_list->StartRec, $jobshistorical_list->DisplayRecs, $jobshistorical_list->TotalRecs) ?>
<?php if ($jobshistorical_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($jobshistorical_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $jobshistorical_list->PageUrl() ?>start=<?php echo $jobshistorical_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($jobshistorical_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $jobshistorical_list->PageUrl() ?>start=<?php echo $jobshistorical_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $jobshistorical_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($jobshistorical_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $jobshistorical_list->PageUrl() ?>start=<?php echo $jobshistorical_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($jobshistorical_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $jobshistorical_list->PageUrl() ?>start=<?php echo $jobshistorical_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $jobshistorical_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $jobshistorical_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $jobshistorical_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $jobshistorical_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($jobshistorical_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($jobshistorical_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fjobshistoricallist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$jobshistorical_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jobshistorical_list->Page_Terminate();
?>
