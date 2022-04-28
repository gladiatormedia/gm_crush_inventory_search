<?php
    if( isset( $modelList ) && is_array( $modelList ) && count( $modelList ) > 0 ):
        
        $html = "<div class='row'>";
        foreach( $modelList as $model ):
            
            $html .= "<div class='col-sm-12 col-md-4 d-grid gap-2'>";
            $html .= "<button type=\"button\" class=\"btn btn-primary btn-model btn-block\" data-model='".$model."'>".$model."</button>";
            $html .= "</div>";
        
        endforeach;
        $html .= "</div>";
        echo $html;
    endif;