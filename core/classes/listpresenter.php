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
	 */
    public function __construct($columns, $values, $type = "default") {
        $this->columns = $columns;
        $this->values = $values;
        $this->type = $type;
        $this->output = "";
    }

	/**
	 * @return string
	 */
    public function presentList() {
        $this->output = "<div class=\"row rowheader " . $this->type . "\">\n";

        foreach($this->columns as $value) {
            $this->output .= "   <div>".$value."</div>\n";
        }
        $this->output .= "</div>";

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

    public function presentDetails() {
        $this->output = "<div class=\"table-responsive\">\n"
                        . "<table class=\"table-striped table-details\">\n";
        foreach($this->values as $key => $value) {

            if(isset($this->columns[$key]) && $this->columns[$key]) {
                $this->output .= "</tr>\n";
                $this->output .= "   <td><b>" . $this->columns[$key] . ":</b></td>\n";
                $this->output .= "   <td>" . $value .  "   </td>\n";
                $this->output .= "</tr>\n";
            }

        }

        $this->output .= "</table>\n";
        return $this->output;
    }
}