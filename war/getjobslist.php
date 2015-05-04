<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "getjobsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$getjobs_list = NULL; // Initialize page object first

class cgetjobs_list extends cgetjobs {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{A78C9F64-F701-4951-8B68-9678633E190C}";

	// Table name
	var $TableName = 'getjobs';

	// Page object name
	var $PageObjName = 'getjobs_list';

	// Grid form hidden field names
	var $FormName = 'fgetjobslist';
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

		// Table object (getjobs)
		if (!isset($GLOBALS["getjobs"])) {
			$GLOBALS["getjobs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["getjobs"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "getjobsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "getjobsdelete.php";
		$this->MultiUpdateUrl = "getjobsupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'getjobs', TRUE);

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
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

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
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->sessionid, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->app, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->secret, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->target, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->message, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->picture, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->link, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->action_name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->action_link, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->targetid); // targetid
			$this->UpdateSort($this->sessionid); // sessionid
			$this->UpdateSort($this->datetime); // datetime
			$this->UpdateSort($this->dataId); // dataId
			$this->UpdateSort($this->credencial); // credencial
			$this->UpdateSort($this->app); // app
			$this->UpdateSort($this->secret); // secret
			$this->UpdateSort($this->target); // target
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

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->status->setSort("");
				$this->type->setSort("");
				$this->targetid->setSort("");
				$this->sessionid->setSort("");
				$this->datetime->setSort("");
				$this->dataId->setSort("");
				$this->credencial->setSort("");
				$this->app->setSort("");
				$this->secret->setSort("");
				$this->target->setSort("");
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
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgetjobslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->status->DbValue = $row['status'];
		$this->type->DbValue = $row['type'];
		$this->targetid->DbValue = $row['targetid'];
		$this->sessionid->DbValue = $row['sessionid'];
		$this->datetime->DbValue = $row['datetime'];
		$this->dataId->DbValue = $row['dataId'];
		$this->credencial->DbValue = $row['credencial'];
		$this->app->DbValue = $row['app'];
		$this->secret->DbValue = $row['secret'];
		$this->target->DbValue = $row['target'];
		$this->message->DbValue = $row['message'];
		$this->picture->DbValue = $row['picture'];
		$this->link->DbValue = $row['link'];
		$this->description->DbValue = $row['description'];
		$this->action_name->DbValue = $row['action_name'];
		$this->name->DbValue = $row['name'];
		$this->action_link->DbValue = $row['action_link'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
if (!isset($getjobs_list)) $getjobs_list = new cgetjobs_list();

// Page init
$getjobs_list->Page_Init();

// Page main
$getjobs_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$getjobs_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var getjobs_list = new ew_Page("getjobs_list");
getjobs_list.PageID = "list"; // Page ID
var EW_PAGE_ID = getjobs_list.PageID; // For backward compatibility

// Form object
var fgetjobslist = new ew_Form("fgetjobslist");
fgetjobslist.FormKeyCountName = '<?php echo $getjobs_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgetjobslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgetjobslist.ValidateRequired = true;
<?php } else { ?>
fgetjobslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgetjobslist.Lists["x_type"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgetjobslist.Lists["x_targetid"] = {"LinkField":"x_domainid","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgetjobslist.Lists["x_dataId"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgetjobslist.Lists["x_credencial"] = {"LinkField":"x_domainid","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fgetjobslistsrch = new ew_Form("fgetjobslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($getjobs_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $getjobs_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$getjobs_list->TotalRecs = $getjobs->SelectRecordCount();
	} else {
		if ($getjobs_list->Recordset = $getjobs_list->LoadRecordset())
			$getjobs_list->TotalRecs = $getjobs_list->Recordset->RecordCount();
	}
	$getjobs_list->StartRec = 1;
	if ($getjobs_list->DisplayRecs <= 0 || ($getjobs->Export <> "" && $getjobs->ExportAll)) // Display all records
		$getjobs_list->DisplayRecs = $getjobs_list->TotalRecs;
	if (!($getjobs->Export <> "" && $getjobs->ExportAll))
		$getjobs_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$getjobs_list->Recordset = $getjobs_list->LoadRecordset($getjobs_list->StartRec-1, $getjobs_list->DisplayRecs);
$getjobs_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($getjobs->Export == "" && $getjobs->CurrentAction == "") { ?>
<form name="fgetjobslistsrch" id="fgetjobslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fgetjobslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fgetjobslistsrch_SearchGroup" href="#fgetjobslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fgetjobslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fgetjobslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="getjobs">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($getjobs_list->BasicSearch->getKeyword()) ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $getjobs_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($getjobs_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($getjobs_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($getjobs_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</td></tr></table>
</form>
<?php } ?>
<?php } ?>
<?php $getjobs_list->ShowPageHeader(); ?>
<?php
$getjobs_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fgetjobslist" id="fgetjobslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="getjobs">
<div id="gmp_getjobs" class="ewGridMiddlePanel">
<?php if ($getjobs_list->TotalRecs > 0) { ?>
<table id="tbl_getjobslist" class="ewTable ewTableSeparate">
<?php echo $getjobs->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$getjobs_list->RenderListOptions();

// Render list options (header, left)
$getjobs_list->ListOptions->Render("header", "left");
?>
<?php if ($getjobs->id->Visible) { // id ?>
	<?php if ($getjobs->SortUrl($getjobs->id) == "") { ?>
		<td><div id="elh_getjobs_id" class="getjobs_id"><div class="ewTableHeaderCaption"><?php echo $getjobs->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->id) ?>',1);"><div id="elh_getjobs_id" class="getjobs_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->status->Visible) { // status ?>
	<?php if ($getjobs->SortUrl($getjobs->status) == "") { ?>
		<td><div id="elh_getjobs_status" class="getjobs_status"><div class="ewTableHeaderCaption"><?php echo $getjobs->status->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->status) ?>',1);"><div id="elh_getjobs_status" class="getjobs_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->type->Visible) { // type ?>
	<?php if ($getjobs->SortUrl($getjobs->type) == "") { ?>
		<td><div id="elh_getjobs_type" class="getjobs_type"><div class="ewTableHeaderCaption"><?php echo $getjobs->type->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->type) ?>',1);"><div id="elh_getjobs_type" class="getjobs_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->targetid->Visible) { // targetid ?>
	<?php if ($getjobs->SortUrl($getjobs->targetid) == "") { ?>
		<td><div id="elh_getjobs_targetid" class="getjobs_targetid"><div class="ewTableHeaderCaption"><?php echo $getjobs->targetid->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->targetid) ?>',1);"><div id="elh_getjobs_targetid" class="getjobs_targetid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->targetid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->targetid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->targetid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->sessionid->Visible) { // sessionid ?>
	<?php if ($getjobs->SortUrl($getjobs->sessionid) == "") { ?>
		<td><div id="elh_getjobs_sessionid" class="getjobs_sessionid"><div class="ewTableHeaderCaption"><?php echo $getjobs->sessionid->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->sessionid) ?>',1);"><div id="elh_getjobs_sessionid" class="getjobs_sessionid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->sessionid->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->sessionid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->sessionid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->datetime->Visible) { // datetime ?>
	<?php if ($getjobs->SortUrl($getjobs->datetime) == "") { ?>
		<td><div id="elh_getjobs_datetime" class="getjobs_datetime"><div class="ewTableHeaderCaption"><?php echo $getjobs->datetime->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->datetime) ?>',1);"><div id="elh_getjobs_datetime" class="getjobs_datetime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->datetime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->datetime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->datetime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->dataId->Visible) { // dataId ?>
	<?php if ($getjobs->SortUrl($getjobs->dataId) == "") { ?>
		<td><div id="elh_getjobs_dataId" class="getjobs_dataId"><div class="ewTableHeaderCaption"><?php echo $getjobs->dataId->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->dataId) ?>',1);"><div id="elh_getjobs_dataId" class="getjobs_dataId">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->dataId->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->dataId->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->dataId->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->credencial->Visible) { // credencial ?>
	<?php if ($getjobs->SortUrl($getjobs->credencial) == "") { ?>
		<td><div id="elh_getjobs_credencial" class="getjobs_credencial"><div class="ewTableHeaderCaption"><?php echo $getjobs->credencial->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->credencial) ?>',1);"><div id="elh_getjobs_credencial" class="getjobs_credencial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->credencial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->credencial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->credencial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->app->Visible) { // app ?>
	<?php if ($getjobs->SortUrl($getjobs->app) == "") { ?>
		<td><div id="elh_getjobs_app" class="getjobs_app"><div class="ewTableHeaderCaption"><?php echo $getjobs->app->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->app) ?>',1);"><div id="elh_getjobs_app" class="getjobs_app">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->app->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->app->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->app->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->secret->Visible) { // secret ?>
	<?php if ($getjobs->SortUrl($getjobs->secret) == "") { ?>
		<td><div id="elh_getjobs_secret" class="getjobs_secret"><div class="ewTableHeaderCaption"><?php echo $getjobs->secret->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->secret) ?>',1);"><div id="elh_getjobs_secret" class="getjobs_secret">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->secret->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->secret->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->secret->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($getjobs->target->Visible) { // target ?>
	<?php if ($getjobs->SortUrl($getjobs->target) == "") { ?>
		<td><div id="elh_getjobs_target" class="getjobs_target"><div class="ewTableHeaderCaption"><?php echo $getjobs->target->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $getjobs->SortUrl($getjobs->target) ?>',1);"><div id="elh_getjobs_target" class="getjobs_target">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $getjobs->target->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($getjobs->target->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($getjobs->target->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$getjobs_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($getjobs->ExportAll && $getjobs->Export <> "") {
	$getjobs_list->StopRec = $getjobs_list->TotalRecs;
} else {

	// Set the last record to display
	if ($getjobs_list->TotalRecs > $getjobs_list->StartRec + $getjobs_list->DisplayRecs - 1)
		$getjobs_list->StopRec = $getjobs_list->StartRec + $getjobs_list->DisplayRecs - 1;
	else
		$getjobs_list->StopRec = $getjobs_list->TotalRecs;
}
$getjobs_list->RecCnt = $getjobs_list->StartRec - 1;
if ($getjobs_list->Recordset && !$getjobs_list->Recordset->EOF) {
	$getjobs_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $getjobs_list->StartRec > 1)
		$getjobs_list->Recordset->Move($getjobs_list->StartRec - 1);
} elseif (!$getjobs->AllowAddDeleteRow && $getjobs_list->StopRec == 0) {
	$getjobs_list->StopRec = $getjobs->GridAddRowCount;
}

// Initialize aggregate
$getjobs->RowType = EW_ROWTYPE_AGGREGATEINIT;
$getjobs->ResetAttrs();
$getjobs_list->RenderRow();
while ($getjobs_list->RecCnt < $getjobs_list->StopRec) {
	$getjobs_list->RecCnt++;
	if (intval($getjobs_list->RecCnt) >= intval($getjobs_list->StartRec)) {
		$getjobs_list->RowCnt++;

		// Set up key count
		$getjobs_list->KeyCount = $getjobs_list->RowIndex;

		// Init row class and style
		$getjobs->ResetAttrs();
		$getjobs->CssClass = "";
		if ($getjobs->CurrentAction == "gridadd") {
		} else {
			$getjobs_list->LoadRowValues($getjobs_list->Recordset); // Load row values
		}
		$getjobs->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$getjobs->RowAttrs = array_merge($getjobs->RowAttrs, array('data-rowindex'=>$getjobs_list->RowCnt, 'id'=>'r' . $getjobs_list->RowCnt . '_getjobs', 'data-rowtype'=>$getjobs->RowType));

		// Render row
		$getjobs_list->RenderRow();

		// Render list options
		$getjobs_list->RenderListOptions();
?>
	<tr<?php echo $getjobs->RowAttributes() ?>>
<?php

// Render list options (body, left)
$getjobs_list->ListOptions->Render("body", "left", $getjobs_list->RowCnt);
?>
	<?php if ($getjobs->id->Visible) { // id ?>
		<td<?php echo $getjobs->id->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_id" class="control-group getjobs_id">
<span<?php echo $getjobs->id->ViewAttributes() ?>>
<?php echo $getjobs->id->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->status->Visible) { // status ?>
		<td<?php echo $getjobs->status->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_status" class="control-group getjobs_status">
<span<?php echo $getjobs->status->ViewAttributes() ?>>
<?php echo $getjobs->status->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->type->Visible) { // type ?>
		<td<?php echo $getjobs->type->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_type" class="control-group getjobs_type">
<span<?php echo $getjobs->type->ViewAttributes() ?>>
<?php echo $getjobs->type->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->targetid->Visible) { // targetid ?>
		<td<?php echo $getjobs->targetid->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_targetid" class="control-group getjobs_targetid">
<span<?php echo $getjobs->targetid->ViewAttributes() ?>>
<?php echo $getjobs->targetid->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->sessionid->Visible) { // sessionid ?>
		<td<?php echo $getjobs->sessionid->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_sessionid" class="control-group getjobs_sessionid">
<span<?php echo $getjobs->sessionid->ViewAttributes() ?>>
<?php echo $getjobs->sessionid->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->datetime->Visible) { // datetime ?>
		<td<?php echo $getjobs->datetime->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_datetime" class="control-group getjobs_datetime">
<span<?php echo $getjobs->datetime->ViewAttributes() ?>>
<?php echo $getjobs->datetime->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->dataId->Visible) { // dataId ?>
		<td<?php echo $getjobs->dataId->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_dataId" class="control-group getjobs_dataId">
<span<?php echo $getjobs->dataId->ViewAttributes() ?>>
<?php echo $getjobs->dataId->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->credencial->Visible) { // credencial ?>
		<td<?php echo $getjobs->credencial->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_credencial" class="control-group getjobs_credencial">
<span<?php echo $getjobs->credencial->ViewAttributes() ?>>
<?php echo $getjobs->credencial->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->app->Visible) { // app ?>
		<td<?php echo $getjobs->app->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_app" class="control-group getjobs_app">
<span<?php echo $getjobs->app->ViewAttributes() ?>>
<?php echo $getjobs->app->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->secret->Visible) { // secret ?>
		<td<?php echo $getjobs->secret->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_secret" class="control-group getjobs_secret">
<span<?php echo $getjobs->secret->ViewAttributes() ?>>
<?php echo $getjobs->secret->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($getjobs->target->Visible) { // target ?>
		<td<?php echo $getjobs->target->CellAttributes() ?>><span id="el<?php echo $getjobs_list->RowCnt ?>_getjobs_target" class="control-group getjobs_target">
<span<?php echo $getjobs->target->ViewAttributes() ?>>
<?php echo $getjobs->target->ListViewValue() ?></span>
</span><a id="<?php echo $getjobs_list->PageObjName . "_row_" . $getjobs_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$getjobs_list->ListOptions->Render("body", "right", $getjobs_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($getjobs->CurrentAction <> "gridadd")
		$getjobs_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($getjobs->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($getjobs_list->Recordset)
	$getjobs_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($getjobs->CurrentAction <> "gridadd" && $getjobs->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($getjobs_list->Pager)) $getjobs_list->Pager = new cPrevNextPager($getjobs_list->StartRec, $getjobs_list->DisplayRecs, $getjobs_list->TotalRecs) ?>
<?php if ($getjobs_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($getjobs_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $getjobs_list->PageUrl() ?>start=<?php echo $getjobs_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($getjobs_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $getjobs_list->PageUrl() ?>start=<?php echo $getjobs_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $getjobs_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($getjobs_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $getjobs_list->PageUrl() ?>start=<?php echo $getjobs_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($getjobs_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $getjobs_list->PageUrl() ?>start=<?php echo $getjobs_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $getjobs_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $getjobs_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $getjobs_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $getjobs_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($getjobs_list->SearchWhere == "0=101") { ?>
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
	foreach ($getjobs_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fgetjobslistsrch.Init();
fgetjobslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$getjobs_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$getjobs_list->Page_Terminate();
?>
