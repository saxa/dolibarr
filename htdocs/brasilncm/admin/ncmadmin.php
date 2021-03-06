<?php
/* Copyright (C) 2015		Sasa Ostrouska			<casaxa@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
include_once(DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php');
dol_include_once('/brasilncm/class/brasilncm.class.php');

$langs->setDefaultLang('pt_BR'); 
$langs->load("admin");
$langs->load("brasilncm");

if (! $user->admin) accessforbidden();

$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$rowid = GETPOST('rowid', 'int');

/*
 * View
 */

llxHeader('',$langs->trans("NCMSetup"),$help_url);

$form=new Form($db);

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';

print load_fiche_titre($langs->trans("NCMSetup"),$linkback,'title_setup');

print $langs->trans("NcmSetupDesc")."<br>\n";

$fname = array("NCM", "Desc", "ImpImport", "IPI", "PIS", "COFINS");

dol_fiche_head();
// Insert fields form
print '<form action="" method="POST">';
print '<table class="noborder" width="100%"><tr class="liste_titre">';
print '<th width="10%">'.$langs->trans("$fname[0]").'</th>';
print '<th width="50%">'.$langs->trans("$fname[1]").'</th>';
print '<th width="10%">'.$langs->trans("$fname[2]").'</th>';
print '<th width="10%">'.$langs->trans("$fname[3]").'</th>';
print '<th width="10%">'.$langs->trans("$fname[4]").'</th>';
print '<th width="10%">'.$langs->trans("$fname[5]").'</th>';
print '<th></th>'; // Permit to button to align correctly.
print '</tr>';

print '<tr>';
print '<td width="10%"><input type="text" size="10" name="'.$langs->trans("$fname[0]").'"></td>';
print '<td width="50%"><input type="text" size="80" name="'.$langs->trans("$fname[1]").'"></td>';
print '<td width="10%"><input type="text" size="10" name="'.$langs->trans("$fname[2]").'"></td>';
print '<td width="10%"><input type="text" size="10" name="'.$langs->trans("$fname[3]").'"></td>';
print '<td width="10%"><input type="text" size="10" name="'.$langs->trans("$fname[4]").'"></td>';
print '<td width="10%"><input type="text" size="10" name="'.$langs->trans("$fname[5]").'"></td>';
print '<td align="right"><input type="submit" class="button" name="actionadd" value="'.$langs->trans("Add").'"></td>';
print '</tr>';
print '</table>';
print '</form>';

// If action is actionadd add the values to the db.
if (GETPOST('actionadd'))
{
	$customcode = $_POST['NCM'];
	$text = $_POST['Desc'];
	$impimport = price2num($_POST['ImpImport']);
	$ipi = price2num($_POST['IPI']);
	$pis = price2num($_POST['PIS']);
	$cofins = price2num($_POST['COFINS']);

	$sql = "INSERT INTO " .MAIN_DB_PREFIX. "brasil_ncm";
	$sql .= " (";
	$sql .= " fk_customcode,";
	$sql .= " ncm_nr,";
	$sql .= " ncm_descr,";
	$sql .= " imp_import,";
	$sql .= " ipi,";
	$sql .= " pis,";
	$sql .= " cofins";
	$sql .= ") VALUES (";
	$sql .= $fkcustcode = $customcode .",";
	$sql .= $customcode .",";
	$sql .= "'".$text."',";
	$sql .= $impimport.",";
	$sql .= $ipi.",";
	$sql .= $pis.",";
	$sql .= $cofins.");";

	dol_syslog("actionadd", LOG_DEBUG);
	$result = $db->query($sql);

	if ($result)	// Add is ok
	{
		setEventMessages($langs->transnoentities("RecordSaved"), null, 'mesgs');
		$_POST=array('id'=>$id);	// Clean $_POST array, we keep only
	}
	else
	{
		if ($db->errno() == 'DB_ERROR_RECORD_ALREADY_EXISTS') {
			setEventMessages($langs->transnoentities("ErrorRecordAlreadyExists"), null, 'errors');
		}
		else 
		{
			dol_print_error($db);
		}
	}

}

// If action is empty then we show what we have.
if ( $action == '' )
{
	// Start searching and getting the values.
	// Create the sql search query
	$sql = "SELECT";
	$sql .= " t.rowid,";
	$sql .= " t.datec,";
	$sql .= " t.fk_customcode,";
	$sql .= " t.ncm_nr,";
	$sql .= " t.ncm_descr,";
	$sql .= " t.imp_import,";
	$sql .= " t.ipi,";
	$sql .= " t.pis,";
	$sql .= " t.cofins";
	$sql.= " FROM ".MAIN_DB_PREFIX."brasil_ncm as t";
	// $sql.= " WHERE 1 = 1"; This is not needed at all, at least right now.

	// Count total nb of records
	$nbtotalofrecords = 0;
	if (empty($conf->global->MAIN_DISABLE_FULL_SCANLIST))
	{
		$result = $db->query($sql);
		$nbtotalofrecords = $db->num_rows($result);
	}	

	$resql=$db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);
    
		//    	print '<table class="liste">'."\n";
    
	        // Fields title
		//        print '<tr class="liste_titre">';
        
		//print_liste_field_titre($langs->trans('ncm_nr'),$_SERVER['PHP_SELF'],'t.ncm_nr','',$param,'width="10%"',$sortfield,$sortorder);
		//print_liste_field_titre($langs->trans('ncm_descr'),$_SERVER['PHP_SELF'],'t.ncm_descr','',$param,'width="50%"',$sortfield,$sortorder);
		//print_liste_field_titre($langs->trans('imp_import'),$_SERVER['PHP_SELF'],'t.imp_import','',$param,'width="10%"',$sortfield,$sortorder);
		//print_liste_field_titre($langs->trans('ipi'),$_SERVER['PHP_SELF'],'t.ipi','',$param,'width="10%"',$sortfield,$sortorder);
		//print_liste_field_titre($langs->trans('pis'),$_SERVER['PHP_SELF'],'t.pis','',$param,'width="10%"',$sortfield,$sortorder);
		//print_liste_field_titre($langs->trans('cofins'),$_SERVER['PHP_SELF'],'t.cofins','',$param,'width="10%"',$sortfield,$sortorder);
	}
                
	// Start of second table.
	print '<br><br>';
	print '<table class="noborder" width="100%"><tr class="liste_titre">';
	print '<th width="10%">'.$langs->trans("$fname[0]").'</th>';
	print '<th width="50%">'.$langs->trans("$fname[1]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[2]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[3]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[4]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[5]").'</th>';
	print '<th></th>';
	print '<th></th>';
	print '</tr>';
	print '<tr><td width="10%">';
	// Insert loop for listing added values in the database.
	$i = 0;
	//$url = $_SERVER["PHP_SELF"].'?'.($page?'page='.$page.'&':'').'sortfield='.$sortfield.'&sortorder='.$sortorder.'&rowid='.(! empty($obj->rowid)?$obj->rowid:(! empty($obj->code)?$obj->code:'')).'&amp;code='.(! empty($obj->code)?urlencode($obj->code):'').'&amp;id='.$id.'&amp;';
	$url = $_SERVER["PHP_SELF"].'?'.($action);

	while ($i < $num)
	{
		$obj = $db->fetch_object($resql);
		if ($obj)
		{
			// You can use here results
			print '<tr>';
			print '<td width="10%">'.$obj->ncm_nr.'</td>';
			print '<td width="50%">'.$obj->ncm_descr.'</td>';
			print '<td width="10%">'.$obj->imp_import.'</td>';
			print '<td width="10%">'.$obj->ipi.'</td>';
			print '<td width="10%">'.$obj->pis.'</td>';
			print '<td width="10%">'.$obj->cofins.'</td>';
			// Modify link
			$iserasable = 1;
			if ($iserasable)
				print '<td align="center"><a href="'.$url.'action=modify&rowid='.(! empty($obj->rowid)?$obj->rowid:$obj->rowid).'">'.img_edit().'</a></td>';
			else 
				print '<td>&nbsp;</td>';
			// Delete link
			if ($iserasable) 
				print '<td align="center"><a href="'.$url.'action=delete&rowid='.(! empty($obj->rowid)?$obj->rowid:$obj->rowid).'">'.img_delete().'</a></td>';
			else 
				print '<td>&nbsp;</td>';
                           
			print '</tr>';
		}
	$i++;
	}
}

// Edit the line.
if ($action == 'modify')
{
	$sql = "UPDATE " .MAIN_DB_PREFIX. "brasil_ncm SET ";
	$sql .=	"WHERE rowid="."'".$rowid."'";
	print $sql;
	// Start of second table.
	print '<br><br>';
	print '<table class="noborder" width="100%"><tr class="liste_titre">';
	print '<th width="10%">'.$langs->trans("$fname[0]").'</th>';
	print '<th width="50%">'.$langs->trans("$fname[1]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[2]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[3]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[4]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[5]").'</th>';
	print '<th></th>';
	print '<th></th>';
	print '</tr>';
	print '<tr><td width="10%">';

}


// Show confirmation dialog.
if ($action == 'delete')
{
	    print $form->formconfirm($_SERVER["PHP_SELF"].'?rowid='.$rowid, $langs->trans('DeleteLine'), $langs->trans('ConfirmDeleteLine'), 'confirm_delete','',0,1);
}

if ( $action == 'confirm_delete' && $confirm == 'yes' )
{
	$sql = "DELETE from " .MAIN_DB_PREFIX. "brasil_ncm WHERE rowid="."'".$rowid."'";
	print $sql;
	// Start of second table.
	print '<br><br>';
	print '<table class="noborder" width="100%"><tr class="liste_titre">';
	print '<th width="10%">'.$langs->trans("$fname[0]").'</th>';
	print '<th width="50%">'.$langs->trans("$fname[1]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[2]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[3]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[4]").'</th>';
	print '<th width="10%">'.$langs->trans("$fname[5]").'</th>';
	print '<th></th>';
	print '<th></th>';
	print '</tr>';
	print '<tr><td width="10%">';
	// Insert loop for listing added values in the database.
	$i = 0;
	while ($i < $num)
	{
		$obj = $db->fetch_object($resql);
		if ($obj)
		{
			// You can use here results
			print '<tr>';
			print '<td width="10%">'.$obj->ncm_nr.'</td>';
			print '<td width="50%">'.$obj->ncm_descr.'</td>';
			print '<td width="10%">'.$obj->imp_import.'</td>';
			print '<td width="10%">'.$obj->ipi.'</td>';
			print '<td width="10%">'.$obj->pis.'</td>';
			print '<td width="10%">'.$obj->cofins.'</td>';
			print '</tr>';
		}
	$i++;
	}
	dol_syslog("delete", LOG_DEBUG);
	$result = $db->query($sql);
	if (! $result)
	{
		if ($db->errno() == 'DB_ERROR_CHILD_EXISTS')
		{
			setEventMessages($langs->transnoentities("ErrorRecordIsUsedByChild"), null, 'errors');
		}
		else
		{
			dol_print_error($db);
		}
	}

}

print '</td></tr>';
print '</table>';


$db->free($resql);
dol_fiche_end();

llxFooter();
$db->close();
