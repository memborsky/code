<?php
class CNavBar
{
    protected $m_aPrivLevel;
    protected $m_strRootPath;
    protected $m_strDelimiter;
    protected $aNav = array();

    public function __construct($a=0)
    {
        if ( is_array($a) )
        {
            $this->aNav = $a;
        }
        $this->addRootPath();
    }

    public function addRootPath($str = "/")
    {
        if ( substr($str, -1) != "/")
        {
            $str .= "/";
        }
        $this->m_strRootPath = $str;
    }

    public function addNavItem($strRoot, $strName, $strUrl, $nLevel = 0)
    {
        $this->aNav[$strRoot][] = array("name"=>$strName, "link"=>$strUrl, "level" => $nLevel);
    }

    public function addDelimiter($str)
    {
        $this->m_strDelimiter = $str;
    }

    public function printNav($nLevel = 0)
    {
        // Build the root for this level, based on PHP_SELF
        $strRoot = $_SERVER["PHP_SELF"];
        if ( eregi("^(" . $this->m_strRootPath . ")(.*)?", $strRoot, $a) )
        {
            $strRoot = "/" . $a[2];
        }
        for ($n = 0; $nLevel < substr_count($strRoot, "/"); $n++ )
        {
            $strRoot = substr($strRoot, 0, strrpos($strRoot, "/"));
        }
        $strRoot .= "/";

        echo "<div class=\"nav" . ($nLevel + 1) . "\">\n";
        if ( isset($this->aNav[$strRoot]) )
        {
            $n = 0;
            foreach ( $this->aNav[$strRoot] AS $aLink )
            {
                if ( $this->checkPrivLevel($aLink["level"]) || ($aLink["level"] == 0) )
                {
                    if ( $n && !empty($this->m_strDelimiter) )
                    {
                        echo $this->m_strDelimiter;
                    }
                    $n++;
                    $this->printLink($aLink["link"], $aLink["name"]);
                }
            }
        }
        else
        {
            echo "&nbsp;\n";
        }
        echo "</div>\n";
    }

    public function checkPrivLevel($n)
    {
        $fRet = false;
        if ( !empty($this->m_aPrivLevel) )
        {
            foreach ( $this->m_aPrivLevel as $nLvl )
            {
                if ( $n == $nLvl || $nLvl == 0 )
                    $fRet = true;
            }
        }
        return $fRet;
    }

    public function setPrivLevel($aLevel)
    {
        $this->m_aPrivLevel = $aLevel;
    }

    protected function printLink($strLink, $strName)
    {
        $strClass = $this->sameRoot($strLink, $_SERVER["PHP_SELF"]) ? " class=\"selected\"" : "";
        echo "<a href=\"" . $strLink . "\"" . $strClass . ">" . str_replace(" ", "&nbsp;" , $strName) . "</a>";
    }

    public static function sameRoot($str1, $str2)
    {
        $fShared = true;

        $a1 = explode("/", $str1);
        $a2 = explode("/", $str2);

        for ( $n = 1; ($n <= count($a1) - 1) && ($n <= count($a2) - 1); $n++ )
        {
            if ( !empty($a1[$n]) && !empty($a2[$n]) )
            {
                if ( $a1[$n] != $a2[$n] )
                {
                    $fShared = false;
                    break;
                }
            }
        }

        return $fShared;
    }

    public function getSection()
    {
        $nSection = 1;
        foreach ( $this->aNav["/"] AS $nKey => $aNav )
        {
            if ( $this->sameRoot($aNav["link"], $_SERVER["PHP_SELF"]) )
            {
                $nSection = $nKey + 1;
                break;
            }
        }
        return $nSection;
    }
}
?>
