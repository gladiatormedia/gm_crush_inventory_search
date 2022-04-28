<?php
    if( isset( $partList ) && is_array( $partList ) && count( $partList ) > 0 ):
        
        $html = "<div class='row'>";
        foreach( $partList as $part ):
            
            $html .= "<div class='col-sm-12 col-md-4 d-grid gap-2'>";
            $html .= "<button type=\"button\" class=\"btn btn-primary btn-block\" data-part='".$part->type."'>".$part->description."</button>";
            $html .= "</div>";
        
        endforeach;
        $html .= "</div>";
        echo $html;
    endif;