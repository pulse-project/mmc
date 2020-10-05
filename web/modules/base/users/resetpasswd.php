<?php
/*
 * (c) 2004-2007 Linbox / Free&ALter Soft, http://linbox.com
 * (c) 2007-2012 Mandriva, http://www.mandriva.com
 *
 * This file is part of Mandriva Management Console (MMC).
 *
 * MMC is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * MMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MMC; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ($_SESSION["AUTH_METHOD"] == "login" || isset($_POST["bback"]))
    redirectTo(urlStrRedirect("base/users/index"));

require("modules/base/includes/users.inc.php");
require("graph/header.inc.php");
require("localSidebar.php");
require("graph/navbar.inc.php");

$user = $_SESSION["login"];

$p = new PageGenerator(_("Reset your password"));
$p->setSideMenu($sidemenu);
$p->display();

if (isset($_POST["bchpasswd"]) && $_POST["newpass"] != "" && $_POST["newpass"] == $_POST["confpass"]) {
    callPluginFunction("changeUserPasswd", array(array($user, prepare_string($_POST["newpass"]), "", False)));
    if (!isXMLRPCError())
        $n = new NotifyWidgetSuccess(_("Your password has been changed."));

    header("Location: " . urlStrRedirect("base/users/index"));
    exit;
}
else {
?>
<form action="<?php echo "main.php?module=base&submod=users&action=resetpasswd"; ?>" method="post">
<p><?php echo  _("You are going to change your password") ?></p>

<table cellspacing="0">
<tr><td><?php echo  _("New password") ?></td>
    <td><input name="newpass" type="password" size="23" /></td></tr>
<tr><td><?php echo  _("Confirm your password") ?></td>
    <td><input name="confpass" type="password" size="23" /></td></tr>
</table>

<input name="user" type="hidden" value="<?php echo $user; ?>" />
<input name="bchpasswd" type="submit" class="btnPrimary" value="<?php echo  _("Change your password") ?>" />
<input name="bback" type="submit" class="btnSecondary" value="<?php echo  _("Return") ?>" />
<?php
if (isset($_POST["bchpasswd"]) && $_POST["newpass"] != $_POST["confpass"])
    echo _("Passwords are mismatching. Please retry.");
?>
</form>

<?php
}
?>
