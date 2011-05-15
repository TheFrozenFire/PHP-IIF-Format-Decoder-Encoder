<?php
abstract class IIF_Header {
	private $type;
	private $headings; // Array of "Headingname" => boolean, where boolean indicates whether the heading is required.
	
	public $rows;
	
	// Ensures that each row contains data for the headings which are required.
	public function verify() {
		foreach($this->rows as $index => $row) foreach($row as $heading => $column) if($headings[$heading] === true && empty($column)) throw new Exception("Row {$index} requires {$heading}, but no data is present");
	}
	
	// Generates an array of headings that are utilized by the row data
	public function distill() {
		$headings = array();
		
		foreach($this->rows as $row) foreach($row as $heading => $column) if(!empty($column)) $headings[] = $heading;
		
		return array_unique($headings);
	}
	
	// Generates the Header data in IIF format
	// $block indicates whether data needs to be encapsulated in ENDTYPE
	public function generate($block = false) {
		$headings = $this->distill();
		$heading = "!{$this->type}\t".implode("\t", $headings);
		$rows = array();
		
		foreach($this->rows as $row) {
			$rowdata = array();
			foreach($headings as $usedheading) $rowdata[] = $row; // Ensuring correct order of headings.
			
			$rows[] = "{$this->type}\t".implode("\t", $rowdata);
		}
		
		if($block) return $heading.PHP_EOL."!END{$this->type}".PHP_EOL.implode(PHP_EOL, $rows).PHP_EOL."END{$this->type}";
		else return $heading.PHP_EOL.implode(PHP_EOL, $rows);
	}
}
?>
