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

  public function getCategories( $get_articles = false, $arg = array() )
  {
    $data = array();
    $count = 0;

    $q = "SELECT
      c.ID,
      c.cim,
      (SELECT count(l.ID) FROM ".self::DB_LIST." as l WHERE l.katID = c.ID) as itemcount
    FROM ".self::DB_CATEGORIES." as c
    WHERE 1=1";

    $q .= " ORDER BY c.sorrend ASC";

    $get = $this->db->query($q);

    if ($get->rowCount() != 0) {
      $get = $get->fetchAll(\PDO::FETCH_ASSOC);
      foreach ($get as $d) {
        $d['ID'] = (int)$d['ID'];
        if ($get_articles) {
          if ( isset($arg['in_cat']) && !empty($arg['in_cat']) ) {
            if( in_array($d['ID'], $arg['in_cat'])){
              $d['articles'] = $this->getArticles( $d['ID'], $arg );
            } else {
              $d['articles'] = array();
            }
          } else {
            $d['articles'] = $this->getArticles( $d['ID'], $arg );
          }

          $count += count($d['articles']);
        }
        $data[] = $d;
      }
    }

    return array( 'data' => $data, 'count' => $count );
  }

  public function getArticles( $by_category = false, $arg = array() )
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

    if ( isset($arg['kiemelt']) ) {
      if ($arg['kiemelt'] === true) {
        $q .= " and c.kiemelt = 1";
      } elseif( $arg['kiemelt'] === false ){
        $q .= " and c.kiemelt = 1";
      }
    }

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

        // Kulcsszó keresések
        if ( isset($arg['search']) ) {
          if ( !$this->searchByKeywords( $d, $arg['search'] ) ) {
            continue;
          }
        }

        $data[] = $d;
      }
    }

    return $data;
  }

  public function searchByKeywords( $data, $search )
  {
    $valid = false;

    foreach ( (array)$search as $src ) {
      $src = strtolower($src);
      if (
        in_array($src,$data['kulcsszavak']) ||
        strpos($data['cim'], $src) !== false ||
        strpos($data['szoveg'], $src) !== false
      ) {
        $valid[] = $src;
      }
    }

    return $valid;
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
