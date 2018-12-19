<?php
/**
 * Class listPresenter
 */
class listPresenter {
    public $columns;
    public $values;
    public $output;

    public function __construct($columns, $values) {
        $this->columns = $columns;
        $this->values = $values;
        $this->output = "";
    }
    
    public function presentlist() {
        $this->output = "<div class='table-responsive'>\n";
        $this->output .= "<table class='table-striped table-hover table-list'>\n\t";
        $this->output .= "  <tr>\n";

        foreach($this->columns as $value) {
            $this->output .= "   <th>".$value."</th>\n";
        }

        foreach($this->values as $rows) {
            $this->output .= "  <tr>\n";
            foreach($this->columns as $key => $value) {
                $this->output .= "   <td>" . $rows[$key] . "</td>\n";
            }
            $this->output .= "  </tr>\n";
        }

        $this->output .= "  </tr>\n";
        $this->output .= "</table>\n";

        return $this->output;
    }

    public function presentdetails() {
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