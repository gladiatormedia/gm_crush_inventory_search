<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                
                </div>
                <div class="card-body">
                    
                    <!--Loading Overlay-->
                    <form id="car-data">
                        <input type="hidden" name="manufacturer_code" id="manufacturer_code" value="">
                        <input type="hidden" name="year" id="year" value="">
                        <input type="hidden" name="model" id="model" value="">
                        <input type="hidden" name="action" id="action" value="">
                    </form>
                    <div id="overlay">
                        <div class="w-100 d-flex justify-content-center align-items-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <!--Loading Overlay-->
                    
                    <!--Wizard Steps-->
                    <div class="steps">
                        
                        <!--Step 1 Manufacturers-->
                        <div id="step-1">
                            <h3 class="title">Choose Manufacturer</h3>
                            <p class="text-muted">
                                <small>
                                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                                    he point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
                                </small>
                            </p>
                            <div id="manufacturers-list">
                                <?php
                                    if( isset( $manufacturers ) && is_array( $manufacturers ) && count( $manufacturers ) > 0 ):
                                        
                                        echo "<div class='row'>";
                                        foreach( $manufacturers as $manufacturer ):
                                        
                                            echo "<div class='col-sm-12 col-md-4 d-grid gap-2'>";
                                            echo "<button type=\"button\" class=\"btn btn-primary btn-block btn-manufacturer\" data-manufacturer_code='".$manufacturer->manufacturer_code."'>".$manufacturer->manufacturer_name."</button>";
                                            echo "</div>";
                                            
                                        endforeach;
                                        echo "</div>";
                                    endif;
                                ?>
                            </div>
                        </div>
                        <!--Step 1 Manufacturers-->
                        
                        <!--Step-2 Year-->
                        <div id="step-2">
                            <h3 class="title">Choose Year</h3>
                            <p class="text-muted">
                                <small>
                                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                                    he point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
                                </small>
                            </p>
                            <div id="years-list">
                            </div>
                        </div>
                        <!--Step-2 Year-->

                        <!--Step-3 Models-->
                        <div id="step-3">
                            <h3 class="title">Choose Model</h3>
                            <p class="text-muted">
                                <small>
                                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                                    he point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
                                </small>
                            </p>
                            <div id="models-list">
                            </div>
                        </div>
                        <!--Step-3 Models-->

                        <!--Step-3 Parts-->
                        <div id="step-4">
                            <h3 class="title">Choose Part</h3>
                            <p class="text-muted">
                                <small>
                                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                                    he point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
                                </small>
                            </p>
                            <div id="parts-list">
                            </div>
                        </div>
                        <!--Step-4 Parts-->
                        
                    </div>
                    <!--Wizard Steps-->
                    
                </div>
                <div class="card-footer">
                
                </div>
            </div>
        </div>
    </div>
</div>