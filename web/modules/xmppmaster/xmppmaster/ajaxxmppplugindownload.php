<?php
/*
 * (c) 2016-2018 Siveo, http://www.siveo.net
 *
 * $Id$
 *
 * This file is part of MMC, http://www.siveo.net
 *
 * MMC is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * MMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.<?php
 *
 * You should have received a copy of the GNU General Public License
 * along with MMC; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *  file ajaxxmppplugindownload.php
 */
?>
<?php

require_once("../includes/xmlrpc.php");
require_once("../../../includes/config.inc.php");
require_once("../../../includes/i18n.inc.php");
require_once("../../../includes/acl.inc.php");
require_once("../../../includes/session.inc.php");


extract($_GET);

xmlrpc_createdirectoryuser($directory);


xmlrpc_CallXmppPluginmmc("downloadfile",Array($_GET));
// message a ecrire
// dest, src, jidmachine
echo    "<p style=' margin-left: 10px;'><b>"._T("Machine", 'xmppmaster')." : ".$jidmachine."</b></p>".
        "<p style=' margin-left: 20px;'>"._T("Download File", 'xmppmaster')." :</p>".
        "<p style=' margin-left: 30px;'>"._T("From", 'xmppmaster')."</p>".
        "<p style=' margin-left: 60px;'><b>".$src."</b></p>".
        "<p style=' margin-left: 30px;'>"._T("To", 'xmppmaster') ."</p>".
        "<p style=' margin-left: 60px;'><b>".$dest."</b></p>";
?>
