<?php
    if( isset( $yearsList ) && is_array( $yearsList ) && count( $yearsList ) > 0 ):
        
        $html = "<div class='row'>";
        foreach( $yearsList as $year ):
    
            $html .= "<div class='col-sm-12 col-md-4 d-grid gap-2'>";
            $html .= "<button type=\"button\" class=\"btn btn-primary btn-block btn-year\" data-year='".$year."'>".$year."</button>";
            $html .= "</div>";
        
        endforeach;
        $html .= "</div>";
        echo $html;
    endif;