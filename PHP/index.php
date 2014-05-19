<?php if(!defined('IS_CMS')) die();
/***************************************************************
 *
* Plugin fuer moziloCMS, welches das Ausführen von beliebigen PHP-Codes in Inhaltsseiten erlaubt
* bassierten auf dem moziloCMS 1.12 Plug-in von Arvid Zimmermann (http://software.azett.com)
* by black-night - Daniel Neef
*
***************************************************************/
class PHP extends Plugin {
    
    private $lang_admin;
        
    function getContent($value) {
        global $specialchars;        
        $value = str_replace("&nbsp;", " ", $value);
        $value = str_replace("<br />", "", $value);
        $value = $specialchars->getHtmlEntityDecode($value);
        $value = str_replace("-html_br~","",$value);
        $value = str_replace("-html_nbsp~"," ",$value);
        $value = str_replace(array("-html_lt~","-html_gt~"),array("&lt;","&gt;"),$value);
        $value = str_replace("^^", "-html_94~", $value);
        $value = str_replace("^", "", $value);
        $value = str_replace("-html_94~", "^", $value);
        // Code ausführen und Ausgabe zurückgeben
        ob_start();
        eval($value);
        $result = ob_get_contents();
        ob_end_clean();        
        return $result;
    }
    function getConfig() {
        $config = array();      
        return $config;
    }
    function getInfo() {
        global $ADMIN_CONF;
         $this->lang_admin = new Language($this->PLUGIN_SELF_DIR."sprachen/admin_language_".$ADMIN_CONF->get("language").".txt");
        $info = array(
            // Plugin-Name (wird in der Pluginübersicht im Adminbereich angezeigt)
             $this->lang_admin->getLanguageValue("plugin_name")." Revision: 3",
            // CMS-Version
            "2.0",
            // Kurzbeschreibung
             $this->lang_admin->getLanguageValue("plugin_desc"),
            // Name des Autors
             "black-night<br />Orginal von Arvid Zimmermann",
            // Download-URL
            array("http://www.black-night.org","Software by black-night"),
            // Platzhalter => Kurzbeschreibung, für Inhaltseditor
             array('{PHP|...}' => $this->lang_admin->getLanguageValue("plugin_mit_param"))
            );
        return $info;        
    }
  }
?>
