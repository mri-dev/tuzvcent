<?
namespace PortalManager;

/**
* class Helpdesk
* @package PortalManager
* @version v1.0
*/
class Helpdesk
{
  const DB_CATEGORIES = 'tudastar_kategoriak';
  const DB_LIST = 'tudastar_cikkek';
	private $db = null;


	function __construct( $arg = array() )
	{
		$this->db = $arg[db];
	}

  public function getCategories( $get_articles = false )
  {
    $data = array();
    $count = 0;

    $q = "SELECT
      c.ID,
      c.cim
    FROM ".self::DB_CATEGORIES." as c
    WHERE 1=1
    ORDER BY c.sorrend ASC";

    $get = $this->db->query($q);

    if ($get->rowCount() != 0) {
      $get = $get->fetchAll(\PDO::FETCH_ASSOC);
      foreach ($get as $d) {
        $d['ID'] = (int)$d['ID'];
        if ($get_articles) {
          $d['articles'] = $this->getArticles( $d['ID'] );
          $count += count($d['articles']);
        }
        $data[] = $d;
      }
    }

    return array( 'data' => $data, 'count' => $count );
  }

  public function getArticles( $by_category = false )
  {
    $data = array();
    $q = "SELECT
      c.ID,
      c.cim,
      c.szoveg,
      c.kulcsszavak,
      c.kiemelt,
      c.idopont
    FROM ".self::DB_LIST." as c
    WHERE 1=1";

    if ( $by_category ) {
      $q .= " and katID = ".$by_category;
    }

    $q .= " ORDER BY c.sorrend ASC";

    $get = $this->db->query($q);

    if ($get->rowCount() != 0) {
      $get = $get->fetchAll(\PDO::FETCH_ASSOC);
      foreach ($get as $d) {
        $d['ID'] = (int)$d['ID'];
        $d['kulcsszavak'] = $this->keywordTrimmer( $d['kulcsszavak'] );
        $data[] = $d;
      }
    }

    return $data;
  }

  private function keywordTrimmer( $keywords = '', $separator = ',' )
  {
    $keys = array();
    $xkeys = explode($separator, $keywords);
    foreach ($xkeys as $key) {
      $keys[] = trim($key);
    }

    return $keys;
  }

	/*-----  End of GETTERS  ------*/
	public function __destruct()
	{
		$this->db = null;
	}
}
?>
