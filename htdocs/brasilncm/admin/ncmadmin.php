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

$langs->setDefaultLang('pt_BR'); 
$langs->load("admin");
$langs->load("brasilncm");

if (! $user->admin) accessforbidden();

/*
 * View
 */

llxHeader('',$langs->trans("NCMSetup"),$help_url);

$form=new Form($db);

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';

print load_fiche_titre($langs->trans("NCMSetup"),$linkback,'title_setup');

print $langs->trans("NcmSetupDesc")."<br>\n";

$fieldname = array("NCM", "ImpImport", "IPI", "PIS", "COFINS");

// Insert fields form
print '<form action="" method="POST">';
print '<table class="noborder" width="100%"><tr class="liste_titre">';

foreach ($fieldname as $fname)
	print '<th width="20%">'.$langs->trans("$fname").'</th>';

print '<th></th>';
print '</tr>';

print '<tr>';
foreach ($fieldname as $fname)
	print '<td width="20%"><input type="text" name="'.$fname.'"></td>';
print '<td align="right"><input type="submit" class="button" name="actionadd" value="'.$langs->trans("Add").'"></td>';
print '</tr>';
print '</table>';
print '</form>';

// Start of second table.
print '<br><br>';
print '<table class="border" width="100%"><tr class="liste_titre">';

foreach ($fieldname as $fname)
	print '<th width="20%">'.$langs->trans("$fname").'</th>';

llxFooter();
$db->close();
