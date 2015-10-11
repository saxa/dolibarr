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

$langs->load("admin");

if (! $user->admin) accessforbidden();

/*
 * View
 */

llxHeader('',$langs->trans("NCM Setup"),$help_url);


$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print load_fiche_titre($langs->trans("NCM Setup"),$linkback,'title_setup');

// $head=user_admin_prepare_head();

// dol_fiche_head($head,'card', $langs->trans("NCM"), 0, 'ncm');

print '<form action="'.$_SERVER['PHP_SELF'].'?id='.$id.'" method="POST">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<table class="noborder" width="100%">';


print '<tr class="liste_titre">';

print '<td></td>';
print '<td colspan="3" align="right">';
if ($tabname[$id] != MAIN_DB_PREFIX.'c_email_templates' || $action != 'edit')
        {
        	print '<input type="submit" class="button" name="actionadd" value="'.$langs->trans("Add").'">';
        }
print '</td>';
print "</tr>";

// Start of second table.
print '<table class="border" width="100%"><tr>';
print '<td width="15%">'.$langs->trans("NCM").'</td><td colspan="">';
print '</td>';
print '</tr>';

print '<tr>';
print '<td width="15%">'.$langs->trans("Imposto de Importacao").'</td><td colspan="">';
print '</td>';
print '</tr>';

print '<tr>';
print '<td width="15%">'.$langs->trans("IPI").'</td><td colspan="">';
print '</td>';
print '</tr>';

print '<tr>';
print '<td width="15%">'.$langs->trans("PIS").'</td><td colspan="">';
print '</td>';
print '</tr>';

print '<tr>';
print '<td width="15%">'.$langs->trans("COFINS").'</td><td colspan="">';
print '</td>';
print '</tr>';
// dol_fiche_end();

llxFooter();
$db->close();
