<?php
/**
 * Class listPresenter
 */
class listPresenter {
    public $columns;
    public $values;
    public $type;
    public $output;

	/**
	 * listPresenter constructor.
	 *
	 * @param $columns
	 * @param $values
	 * @param $type
	 */
    public function __construct($columns, $values, $type) {
        $this->columns = $columns;
        $this->values = $values;
        $this->type = $type;
        $this->output = "";
    }

	/**
	 * Udskriver lister ud fra arrays af kolonner og værdier
	 * @return string
	 */
    public function presentList() {
    	//Starter output divs
        $this->output = "<div class=\"row rowheader " . $this->type . "\">\n";
        //Looper kolonner til list headers
        foreach($this->columns as $value) {
            $this->output .= "   <div>".$value."</div>\n";
        }
        $this->output .= "</div>";

        //Looper rækker med værdier som passer til list headers
        foreach($this->values as $rows) {
            $this->output .= "  <div class=\"row " . $this->type . "\">\n";

            foreach($this->columns as $key => $value) {
            	if(isset($rows[$key])) {
		            $this->output .= "   <div>" . $rows[$key] . "</div>\n";
	            }
            }

            $this->output .= "  </div>\n";
        }
        $this->output .= "  </div>\n";
        return $this->output;
    }

	/**
	 * @return string
	 */
    public function presentDetails() {
        $this->output = "<div class=\"row rowheader " . $this->type . "\">\n";
        foreach($this->values as $key => $value) {
            if(isset($this->columns[$key]) && $this->columns[$key]) {
                $this->output .= "  <div class=\"row " . $this->type . "\">\n";
                $this->output .= "     <div>" . $this->columns[$key] . ":</div>\n";
                $this->output .= "     <div>" . $value .  "</div>\n";
                $this->output .= "  </div>\n";
            }
        }
        $this->output .= "</div>\n";
        return $this->output;
    }
}