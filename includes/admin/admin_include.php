<?php
if (!defined("ROOT")) {
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
}
require_once(ROOT . "/include.php");

require_once(ROOT."/admin/event_form_model.php");
require_once(ROOT."/admin/event_form_view.php");
require_once(ROOT."/admin/event_form_contr.php");

require_once(ROOT."/admin/categorie_form_contr.php");
require_once(ROOT."/admin/categorie_form_model.php");

require_once(ROOT."/admin/locatie_form_contr.php");
require_once(ROOT."/admin/locatie_form_model.php");

require_once(ROOT."/admin/zaal_form_model.php");
require_once(ROOT."/admin/zaal_form_contr.php");

require_once(ROOT."/admin/event_info_contr.php");
require_once(ROOT."/admin/event_info_model.php");

require_once(ROOT."/admin/spreker_form_contr.php");
require_once(ROOT."/admin/spreker_form_model.php");

require_once(ROOT."/admin/event_reserveren_contr.php");
require_once(ROOT."/admin/event_reserveren_model.php");