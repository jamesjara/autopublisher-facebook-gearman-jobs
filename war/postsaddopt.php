<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "postsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$posts_addopt = NULL; // Initialize page object first

class cposts_addopt extends cposts {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{A78C9F64-F701-4951-8B68-9678633E190C}";

	// Table name
	var $TableName = 'posts';

	// Page object name
	var $PageObjName = 'posts_addopt';

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

		// Table object (posts)
		if (!isset($GLOBALS["posts"])) {
			$GLOBALS["posts"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["posts"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'posts', TRUE);

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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_id"] = $this->id->DbValue;
					$row["x_message"] = $this->message->DbValue;
					$row["x_picture"] = $this->picture->DbValue;
					$row["x_link"] = $this->link->DbValue;
					$row["x_name"] = $this->name->DbValue;
					$row["x_description"] = $this->description->DbValue;
					$row["x_action_name"] = $this->action_name->DbValue;
					$row["x_action_link"] = $this->action_link->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->message->CurrentValue = NULL;
		$this->message->OldValue = $this->message->CurrentValue;
		$this->picture->CurrentValue = NULL;
		$this->picture->OldValue = $this->picture->CurrentValue;
		$this->link->CurrentValue = NULL;
		$this->link->OldValue = $this->link->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->action_name->CurrentValue = NULL;
		$this->action_name->OldValue = $this->action_name->CurrentValue;
		$this->action_link->CurrentValue = NULL;
		$this->action_link->OldValue = $this->action_link->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->message->FldIsDetailKey) {
			$this->message->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_message")));
		}
		if (!$this->picture->FldIsDetailKey) {
			$this->picture->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_picture")));
		}
		if (!$this->link->FldIsDetailKey) {
			$this->link->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_link")));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_name")));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_description")));
		}
		if (!$this->action_name->FldIsDetailKey) {
			$this->action_name->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_action_name")));
		}
		if (!$this->action_link->FldIsDetailKey) {
			$this->action_link->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_action_link")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->message->CurrentValue = ew_ConvertToUtf8($this->message->FormValue);
		$this->picture->CurrentValue = ew_ConvertToUtf8($this->picture->FormValue);
		$this->link->CurrentValue = ew_ConvertToUtf8($this->link->FormValue);
		$this->name->CurrentValue = ew_ConvertToUtf8($this->name->FormValue);
		$this->description->CurrentValue = ew_ConvertToUtf8($this->description->FormValue);
		$this->action_name->CurrentValue = ew_ConvertToUtf8($this->action_name->FormValue);
		$this->action_link->CurrentValue = ew_ConvertToUtf8($this->action_link->FormValue);
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
		$this->message->setDbValue($rs->fields('message'));
		$this->picture->setDbValue($rs->fields('picture'));
		$this->link->setDbValue($rs->fields('link'));
		$this->name->setDbValue($rs->fields('name'));
		$this->description->setDbValue($rs->fields('description'));
		$this->action_name->setDbValue($rs->fields('action_name'));
		$this->action_link->setDbValue($rs->fields('action_link'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->message->DbValue = $row['message'];
		$this->picture->DbValue = $row['picture'];
		$this->link->DbValue = $row['link'];
		$this->name->DbValue = $row['name'];
		$this->description->DbValue = $row['description'];
		$this->action_name->DbValue = $row['action_name'];
		$this->action_link->DbValue = $row['action_link'];
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
		// message
		// picture
		// link
		// name
		// description
		// action_name
		// action_link

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// message
			$this->message->ViewValue = $this->message->CurrentValue;
			$this->message->ViewCustomAttributes = "";

			// picture
			$this->picture->ViewValue = $this->picture->CurrentValue;
			$this->picture->ViewCustomAttributes = "";

			// link
			$this->link->ViewValue = $this->link->CurrentValue;
			$this->link->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// action_name
			$this->action_name->ViewValue = $this->action_name->CurrentValue;
			$this->action_name->ViewCustomAttributes = "";

			// action_link
			$this->action_link->ViewValue = $this->action_link->CurrentValue;
			$this->action_link->ViewCustomAttributes = "";

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

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// action_name
			$this->action_name->LinkCustomAttributes = "";
			$this->action_name->HrefValue = "";
			$this->action_name->TooltipValue = "";

			// action_link
			$this->action_link->LinkCustomAttributes = "";
			$this->action_link->HrefValue = "";
			$this->action_link->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// message
			$this->message->EditCustomAttributes = "";
			$this->message->EditValue = $this->message->CurrentValue;
			$this->message->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->message->FldTitle()));

			// picture
			$this->picture->EditCustomAttributes = "";
			$this->picture->EditValue = $this->picture->CurrentValue;
			$this->picture->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->picture->FldTitle()));

			// link
			$this->link->EditCustomAttributes = "";
			$this->link->EditValue = $this->link->CurrentValue;
			$this->link->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->link->FldTitle()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = $this->name->CurrentValue;
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldTitle()));

			// description
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = $this->description->CurrentValue;
			$this->description->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->description->FldTitle()));

			// action_name
			$this->action_name->EditCustomAttributes = "";
			$this->action_name->EditValue = $this->action_name->CurrentValue;
			$this->action_name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->action_name->FldTitle()));

			// action_link
			$this->action_link->EditCustomAttributes = "";
			$this->action_link->EditValue = $this->action_link->CurrentValue;
			$this->action_link->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->action_link->FldTitle()));

			// Edit refer script
			// message

			$this->message->HrefValue = "";

			// picture
			$this->picture->HrefValue = "";

			// link
			$this->link->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// description
			$this->description->HrefValue = "";

			// action_name
			$this->action_name->HrefValue = "";

			// action_link
			$this->action_link->HrefValue = "";
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

		// message
		$this->message->SetDbValueDef($rsnew, $this->message->CurrentValue, NULL, FALSE);

		// picture
		$this->picture->SetDbValueDef($rsnew, $this->picture->CurrentValue, NULL, FALSE);

		// link
		$this->link->SetDbValueDef($rsnew, $this->link->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// action_name
		$this->action_name->SetDbValueDef($rsnew, $this->action_name->CurrentValue, NULL, FALSE);

		// action_link
		$this->action_link->SetDbValueDef($rsnew, $this->action_link->CurrentValue, NULL, FALSE);

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
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "postslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("addopt");
		$Breadcrumb->Add("addopt", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Custom validate event
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
if (!isset($posts_addopt)) $posts_addopt = new cposts_addopt();

// Page init
$posts_addopt->Page_Init();

// Page main
$posts_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$posts_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var posts_addopt = new ew_Page("posts_addopt");
posts_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = posts_addopt.PageID; // For backward compatibility

// Form object
var fpostsaddopt = new ew_Form("fpostsaddopt");

// Validate form
fpostsaddopt.Validate = function() {
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

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpostsaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpostsaddopt.ValidateRequired = true;
<?php } else { ?>
fpostsaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$posts_addopt->ShowMessage();
?>
<form name="fpostsaddopt" id="fpostsaddopt" class="ewForm form-horizontal" action="postsaddopt.php" method="post">
<input type="hidden" name="t" value="posts">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_postsaddopt">
	<div class="control-group">
		<label class="control-label" for="x_message"><?php echo $posts->message->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_message" name="x_message" id="x_message" cols="35" rows="4" placeholder="<?php echo $posts->message->PlaceHolder ?>"<?php echo $posts->message->EditAttributes() ?>><?php echo $posts->message->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_picture"><?php echo $posts->picture->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_picture" name="x_picture" id="x_picture" cols="35" rows="4" placeholder="<?php echo $posts->picture->PlaceHolder ?>"<?php echo $posts->picture->EditAttributes() ?>><?php echo $posts->picture->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_link"><?php echo $posts->link->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_link" name="x_link" id="x_link" cols="35" rows="4" placeholder="<?php echo $posts->link->PlaceHolder ?>"<?php echo $posts->link->EditAttributes() ?>><?php echo $posts->link->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_name"><?php echo $posts->name->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_name" name="x_name" id="x_name" cols="35" rows="4" placeholder="<?php echo $posts->name->PlaceHolder ?>"<?php echo $posts->name->EditAttributes() ?>><?php echo $posts->name->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_description"><?php echo $posts->description->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo $posts->description->PlaceHolder ?>"<?php echo $posts->description->EditAttributes() ?>><?php echo $posts->description->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_action_name"><?php echo $posts->action_name->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_action_name" name="x_action_name" id="x_action_name" cols="35" rows="4" placeholder="<?php echo $posts->action_name->PlaceHolder ?>"<?php echo $posts->action_name->EditAttributes() ?>><?php echo $posts->action_name->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_action_link"><?php echo $posts->action_link->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_action_link" name="x_action_link" id="x_action_link" cols="35" rows="4" placeholder="<?php echo $posts->action_link->PlaceHolder ?>"<?php echo $posts->action_link->EditAttributes() ?>><?php echo $posts->action_link->EditValue ?></textarea>
</div>
	</div>
</div>
</form>
<script type="text/javascript">
fpostsaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$posts_addopt->Page_Terminate();
?>
