<?php
/**
 * Class for limitting query and creating links for paging
 *
 * @version 1.0
 * @author lucky <bogeyman2007@gmail.com>
 * @filesource paging.php
 * @license http://www.gnu.org/licenses/gpl.html
 */

/**
 * Class nicePaging
 */
class nicePaging{
	/**
	 * @var connection Storing the database connection link
	 * @access private
	 */
	private $conn;
	
	/**
	 * @var integer Storing the current page
	 * @access private
	 */
	private static $page;
	
	/**
	 * @var integer Storing the total pages
	 * @access private
	 */
	private $totalPages;
	
	/**
	 * @var string Storing the separator between link and query string
	 * @access private
	 */
	private $separator;
	
	/**
	 * @var integer Storing the maximum number of links displayed per page
	 * @access private
	 */
	private $maxPages;
	
	/**
	 * Constructor
	 *
	 * @access public
	 * @param connection The database connection link (default=NULL)
	 */
	public function __construct($conn=null){
		$this->conn=$conn;
		$this->separator="&";
		$this->maxPages=10;
	}
	
	/**
	 * Method for setting the separator between link and query string
	 *
	 * @access public
	 * @param int $char separator
	 */
	public function setSeparator($char){
		$this->separator=$char;
	}
	
	/**
	 * Method for setting maximum number of links displayed per page
	 *
	 * @access public
	 * @param int $maxPages Maximum number of links
	 */
	public function setMaxPages($maxPages){
		$this->maxPages=$maxPages;
	}
	
	/**
	 * Method for limitting query result based on the requested page and rows per page 
	 *
	 * @access public
	 * @param string $sql The SQL string (without LIMIT)
	 * @param integer $rowsPerPage Displayed rows per page
	 * @return resultset Resultset from query
	 */
	public function pagerQuery($sql, $rowsPerPage){
		$page=isset($_GET['page']) ? $_GET['page'] : 1;
		
		if($this->conn==null)
			$result=mysql_query($sql);
		else
			$result=mysql_query($sql, $this->conn);
		
		$totalRows=mysql_num_rows($result);
		$this->totalPages=intval($totalRows/$rowsPerPage) + ($totalRows%$rowsPerPage==0 ? 0 : 1);
		if($this->totalPages<1){
			$this->totalPages=1;
		}
		
		$this->page=intval($page);
		if($this->page<1){
			$this->page=1;
		}
		if($this->page>$this->totalPages){
			$this->page=$this->totalPages;
		}
		
		$this->page-=1;
		if($this->page<0){
			$this->page=0;
		}
		
		$result=mysql_query($sql." LIMIT ".$this->page*$rowsPerPage.", ".$rowsPerPage);
		$this->page+=1;
		
		return $result;
	}
	
	/**
	 * Method for creating the links for paging
	 *
	 * @access public
	 * @param string $link The page name
	 * @return string Links for paging
	 */
	public function createPaging($link){
		$start=((($this->page%$this->maxPages==0) ? ($this->page/$this->maxPages) : intval($this->page/$this->maxPages)+1)-1)*$this->maxPages+1;
		$end=((($start+$this->maxPages-1)<=$this->totalPages) ? ($start+$this->maxPages-1) : $this->totalPages);
		
		$paging='<ul class="nice_paging">';
		if($this->page>1){
			$paging.='<li><a href="'.$link.$this->separator.'page=1" title="First page">&lt;&lt;</a></li>';
			$paging.='<li><a href="'.$link.$this->separator.'page='.($this->page-1).'" title="Previous page">&lt;</a></li>';
		}
		
		if($start>$this->maxPages){
			$paging.='<li><a href="'.$link.$this->separator.'page='.($start-1).'" title="Page '.($start-1).'">...</a></li>';
		}
		
		for($i=$start;$i<=$end;$i++){
			if($this->page==$i){
				$paging.='<li class="current">'.$i.'</li>';
			}
			else{
				$paging.='<li><a href="'.$link.$this->separator.'page='.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
			}
		}
		
		if($end<$this->totalPages){
			$paging.='<li><a href="'.$link.$this->separator.'page='.($end+1).'" title="Page '.($end+1).'">...</a></li>';
		}
		
		if($this->page<$this->totalPages){
			$paging.='<li><a href="'.$link.$this->separator.'page='.($this->page+1).'" title="Next page">&gt;</a></li>';
			$paging.='<li><a href="'.$link.$this->separator.'page='.$this->totalPages.'" title="Last page">&gt;&gt;</a></li>';
		}
		
		return $paging;
	}
}
?>