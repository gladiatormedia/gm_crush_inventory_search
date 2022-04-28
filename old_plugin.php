<?php
    
    
    
    /*** enque ajax functions **/
    function ajax_test_enqueue_scripts() {
        wp_enqueue_script( 'vehicleSearch', plugins_url( '/vehicle-search.js', __FILE__ ), array('jquery'), '1.0', true );
    }
    add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts' );
    
    
    function themeslug_enqueue_style() {
        wp_enqueue_style( 'jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css', false );
    }
    
    function themeslug_enqueue_script() {
        wp_enqueue_script( 'jquery-ui-datepicker', 'jquery-ui-datepicker', false );
        
        wp_enqueue_script( 'lazy-load-images', '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js', false );
        wp_enqueue_script( 'lazy-load-plugins', '//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js', false );
    }
    
    add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_style' );
    add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_script' );
    
    /*** Load Jquery UI **********/
#wp_enqueue_script('jquery-ui-datepicker');
#wp_enqueue_style('jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    
    
    function getStores(){
        global $wpdb;
        $stores=[];
        $Query= 'SELECT StoreNumber, StoreName, State FROM Stores';
        $results = $wpdb->get_results($Query);
        foreach($results as $row) {
            $stores[]= $row;
        }
        print_r(json_encode($stores));
        wp_die();
    }
    add_action('wp_ajax_getStores', 'getStores');
    add_action('wp_ajax_nopriv_getStores', 'getStores');
    /***************************** getVehicles function *************************/
    function getVehicles(){
        global $wpdb;
        $output ='';
        $counter = 1;
        // Build Query from Post Data
        if(isset($_POST['InventoryNumber'])){
            $vehiclesQuery="
	SELECT DISTINCT Year,
	ICModel Model,
	COALESCE(Color,'Unknown') Color,
	StockNumber,
	Row,
	DATE_FORMAT(DateSet, '%m-%d-%y') DateSet,
	DATE_FORMAT(DateSet, '%Y-%m-%d') DateSetData,
	S.StoreNumber,
	S.StoreName,
	VIN,
	COALESCE(Odometer,'Unknown') Odometer
  FROM vehicles V
  INNER JOIN Stores S ON S.StoreNumber = V.Store
  INNER JOIN IndexList IL ON V.Year = IL.BeginYear AND V.ICModel = IL.ModelNm
  INNER JOIN IndexListApp ILA ON ILA.IndexListId = IL.IndexListId
  where ILA.InterchangeNumber = '$_REQUEST[InventoryNumber]'";
            if(isset($_POST['store']) && $_POST['store'] != '0'){
                $vehiclesQuery .="AND S.StoreNumber = {$_POST[store]}
		";
            }
            
            $fitment=1;
        }else{
            
            $vehiclesQuery="
		SELECT DISTINCT Year,
		ICModel Model,
		COALESCE(Color,'Unknown') Color,
		StockNumber,
		Row,
		DATE_FORMAT(DateSet, '%m-%d-%y') DateSet,
		DATE_FORMAT(DateSet, '%Y-%m-%d') DateSetData,
		S.StoreNumber,
		S.StoreName,
		VIN,
		COALESCE(Odometer,'Unknown') Odometer
		FROM vehicles V
		INNER JOIN Stores S ON S.StoreNumber = V.Store
		WHERE DateSet IS NOT NULL
		AND Make IS NOT NULL
		AND Make <> ''
		AND location = 'YARD'
	";
            
            //DateCrushed IS NULL AND
            
            if(isset($_POST['makes']) && $_POST['makes'] != '0'){
                $Make = $_POST['makes'];
                $vehiclesQuery .="AND Make = '{$_POST[makes]}'
		";
            }
            if(isset($_POST['models']) && $_POST['models'] != '0'){
                $Model = $_POST['models'];
                $vehiclesQuery .="AND ICModel = '{$_POST[models]}'
		";
            }
            if(isset($_POST['years']) && $_POST['years'] != '0'){
                $Year = $_POST['years'];
                $vehiclesQuery .="AND Year = '{$_POST[years]}'
		";
            }
            if(isset($_POST['beginDate']) && $_POST['beginDate'] != ''){
                $vehiclesQuery .="AND CAST(DateSet AS DATE) >= '{$_POST[beginDate]}'
		";
            }
            if(isset($_POST['beginDate']) && $_POST['endDate'] != ''){
                $vehiclesQuery .="AND CAST(DateSet AS DATE) <= '{$_POST[endDate]}'
		";
            }
            if(isset($_POST['store']) && $_POST['store'] != '0'){
                $vehiclesQuery .="AND S.StoreNumber = {$_POST[store]}
		";
            }
            $fitment = 0;
        }
//$vehiclesQuery .=" ORDER BY Store, DateSet DESC";
        $vehiclesQuery .=" ORDER BY DateSet DESC";

//echo $vehiclesQuery;exit();
        /** Insert search into history table for tracking if not a fitment search*/
        $clientIP = $_SERVER['REMOTE_ADDR'];
        if($fitment==0){
            $wpdb->insert(
                'vehicleSearches',
                array(
                    'Year'  => $Year,
                    'Make'  => $Make,
                    'Model' => $Model,
                    'Store' => $_POST['store'],
                    'ClientIP' => $clientIP,
                    'PartType' => 0
                )
            );
        }
//<th data-hide='phone,tablet'>Odometer</th>
        //<td>{$vehicle->Odometer}</td>
        /** get/show results **/
        $vehicles = $wpdb->get_results($vehiclesQuery);
        
        if (count($vehicles)> 0){
            $output .= "<h2>Vehicle Results</h2>
		<table class='footable table' id='vehicletable1'>
							<thead>
								<tr>
									<th>Year</th>
									<th>Model</th>
									<th>Row</th>
									<th>Store</th>
									<th data-hide='phone,tablet'>Color</th>
									<th data-hide='phone,tablet'>Stock#</th>
                                    <th data-hide='phone,tablet'>VIN</th>
                                    <th data-hide='phone'>Set Date</th>
                                    <th data-hide='phone'>Image</th>
								</tr>
							</thead>
							<tbody>";
            foreach($vehicles as $vehicle){
                $store = $vehicle->Store;
                ////////// loop through results for table
//            if( !validate_url('https://nvpap.com/photos/'.$vehicle->StockNumber.'.jpg' )):
//                $image = "<img class='lazy' data-src=\"".get_site_url()."/wp-content/uploads/inventory-images/placeholder.jpg\" width=\"80px\" height=\"45px\">";
//            else:
//
//            endif;
                $image = "<a href=\"https://nvpap.com/photos/".$vehicle->StockNumber.".jpg\" data-lbox=\"".$vehicle->StockNumber."\"><img src=\"https://nvpap.com/photos/".$vehicle->StockNumber.".jpg\" width=\"80px\" height=\"45px\"></a>";
                //$html .= "</tr>";
                $output.="
				<tr>
					<td>{$vehicle->Year}</td>
					<td><span class='notranslate'>{$vehicle->Model}</span></td>
					<td>{$vehicle->Row}</td>
					<td>{$vehicle->StoreName}</td>
					<td>{$vehicle->Color}</td>
					<td>{$vehicle->StockNumber}</td>
                    <td>{$vehicle->VIN}</td>
                    <td data-value='{$vehicle->DateSetData}'>{$vehicle->DateSet}</td>
                    <td>{$image}</td>
				</tr>
				";
            }
            $output.="</tbody>
		
		</table>";
            //remvoed paging from tbale footer
//        <tfoot>
//			<tr>
//				<td colspan='9'>
//					<div class='pagination'></div>
//				</td>
//			</tr>
//		</tfoot>
        }else{
            $output .="<h2>I'm sorry but there are no matching vehicles at ";
            if($_POST['store'] == '0'){
                $output.="any of our locations at this time</h2>";
            }else{
                $ouput.="our {$_POST['store']} location at this time</h2>";
            }
            
        }
        //$output.=$vehiclesQuery;
        
        print $output;
        //print_r($_POST);
        //echo $vehiclesQuery;
        //echo"hello";
        die();
    }
    add_action('wp_ajax_getVehicles', 'getVehicles');
    add_action('wp_ajax_nopriv_getVehicles', 'getVehicles');
    
    
    /************ getMakes function *********************************/
    
    function getMakes(){
        $Query='';
        global $wpdb;
        $form ='';
        if(isset($_POST['Form'])){
            $form= $_POST['Form'];
        }
        if($form == "searchPartForm"){
            $makeOptions="<option>Select Make</option>";
        }else{
            $makeOptions="<option value='0'>Any</option>";
        }
        /*********** Change to pull all YMM regardless if we have 05-09-2019 */
        $Query="SELECT DISTINCT MfrName make FROM GlennCarline
	";
        if(isset($_POST['year'])){
            $Query.="WHERE CarlineYear = {$_POST[year]}
		";
        }
        $Query.="ORDER BY MfrName";
        /****************** Old code from 3.2.3 and older, this only pulls vehicles we have in stock */
        /*
        if(isset($_POST['year'])){
            $Query="SELECT DISTINCT MfrName make
            FROM GlennCarline
            WHERE CarlineYear = {$_POST[year]}
            ORDER BY MfrName";
            //echo $_POST['year']." ". $Query;
        }else{
            $Query= "SELECT DISTINCT make
            FROM vehicles
            WHERE Make IS NOT NULL
            AND Make <> '' ORDER BY Make;";
        }
        */
        /**** End old code */
        $makes = $wpdb->get_results($Query);
        foreach($makes as $make){
            $makeOptions.= "<option value='".$make->make."'><span class='notranslate'>".$make->make."</span></option>";
        }
        echo $makeOptions;
    }
    add_action('wp_ajax_getMakes', 'getMakes');
    add_action('wp_ajax_nopriv_getMakes', 'getMakes');
    
    
    /************* getModels Function ********************************/
    function getModels(){
        
        global $wpdb;
        $form ='';
        
        if(isset($_POST['Form'])){
            $form= $_POST['Form'];
        }
        $Query ="SELECT DISTINCT ModelNm Model FROM GlennCarline WHERE MfrName = '{$_POST[Make]}'
    ";
        if($form == "searchVehicleForm"){
            $output ="<option value='0'>Any</option>";
            ;
        }else{
            $output ="<option value=''>Select Model</option>";
            $Query.="AND CarlineYear = {$_POST[Year]}
        ORDER BY ModelNm
        ";
        }
        /*********** Old code from 3.2.3 and older, this only pulls vehicles we have in stock */
        /*
        if($form == "searchPartForm"){
            $output ="<option>Select Model</option>";
            $Query = "SELECT DISTINCT ModelNm Model
            FROM GlennCarline
            WHERE CarlineYear = {$_POST[Year]}
            AND MfrName = '{$_POST[Make]}'
            ORDER BY ModelNm";
            //echo $_POST['Make']. " " . $_POST['year'];
        }else{
            $output ="<option value='0'>Any</option>";
            $Query = "SELECT DISTINCT ICModel Model
            FROM vehicles
            WHERE Make = '{$_POST[Make]}'
            ORDER BY Model;";
        }
        */
        /**** End old code */
        
        $models = $wpdb->get_results($Query);
        foreach($models as $model){
            //echo "<option id=".$model->Model.">".$model->Model."</option>";
            $output.="<option value='".$model->Model."'><span class='notranslate'>".$model->Model."</span></option>";
        }
        print $output;
        //echo"hello";
        die();
    }
    add_action('wp_ajax_getModels', 'getModels');
    add_action('wp_ajax_nopriv_getModels', 'getModels');
    
    
    /**************** getYears for YMM search**********************************************/
    function getYears(){
        
        global $wpdb;
        $yearOutput ="<option value='0'>Any</option>";
        $yearsQuery ="SELECT DISTINCT CarlineYear Year FROM GlennCarline WHERE MfrName = '{$_POST[Make]}'
    ";
        if(isset($_POST['Model'])){
            if($_POST['Model'] != 'Any'){
                $yearsQuery .= "AND ModelNm = '{$_POST[Model]}'
            ";
            }
        }
        $yearsQuery.="ORDER BY Year DESC";
        /*********** Old code from 3.2.3 and older, this only pulls vehicles we have in stock */
        /*
        if(isset($_POST['Model'])){
            if($_POST['Model'] != 'Any'){
                $yearsQuery .= " AND ICModel = '{$_POST[Model]}'";
            }else{
                $yearsQuery .= " ";
            }
        }
        $yearsQuery.=" ORDER BY Year DESC";
        */
        /**** End old code */
        $years = $wpdb->get_results($yearsQuery);
        foreach($years as $year){
            //echo "<option id=".$model->Model.">".$model->Model."</option>";
            $yearOutput.="<option value='".$year->Year."'><span class='notranslate'>".$year->Year."</span></option>";
        }
        print $yearOutput;
        // echo $_POST['Make'];
        //echo $yearsQuery;
        die();
    }
    add_action('wp_ajax_getYears', 'getYears');
    add_action('wp_ajax_nopriv_getYears', 'getYears');
    
    /*******************************Get Years for intial Search By Part load***************************/
    function getYearsForPart(){
        global $wpdb;
        $output="<option>Select Year</option>";
        $years = $wpdb->get_results("SELECT DISTINCT CarlineYear FROM GlennCarline ORDER BY CarlineYear DESC");
        foreach($years as $year){
            $output.= "<option>".$year->CarlineYear."</option>";
        }
        print $output;
    }
    add_action('wp_ajax_getYearsForPart', 'getYearsForPart');
    add_action('wp_ajax_nopriv_getYearsForPart', 'getYearsForPart');
    
    /***************** Get Part Types with Interchange List *********************************************/
    function getPartTypesWithIC(){
        global $wpdb;
        $output="<option>Please Choose Part</option>";
        $Query = "SELECT DISTINCT PT.Description PartName, PT.PartType
			  FROM IndexList IDX
			  INNER JOIN IndexListApp ILA ON ILA.IndexListID = IDX.IndexListID
			  INNER JOIN PType PT ON PT.PArtTYpe = IDX.PartType
			WHERE ILA.IntchNbr IS NOT NULL
			AND {$_POST[Year]} BETWEEN IDX.BeginYear AND IDX.EndYear
			AND ModelNm = '{$_POST[Model]}'
			ORDER BY PartName";
        $parts = $wpdb->get_results($Query);
        foreach($parts as $part){
            $output.= "<option value = '{$part->PartType}'>{$part->PartName}</option>";
        }
        print $output;
        
        //echo $_POST['Make']. " " . $_POST['Year']. $Query;
    }
    add_action('wp_ajax_getPartTypesWithIC', 'getPartTypesWithIC');
    add_action('wp_ajax_nopriv_getPartTypesWithIC', 'getPartTypesWithIC');
    
    /****************** Get IC Options For Part *************************************************************/
    function getICOptionsForPart(){
        global $wpdb;
        // set $partType from inventory number to log search, handle if it cannot be converted to int and is a string
        if(is_numeric(substr($_POST['PartType'],0,3))){
            $partType = substr($_POST['PartType'],0,3);
        }else{
            $partType = 0;
        }
        $clientIP = $_SERVER['REMOTE_ADDR'];
        // Insert Search Parameters for logging
        $wpdb->insert(
            'vehicleSearches',
            array(
                'Year'  => $_POST['Year'],
                'Make'  => $Make,//this will insert NULL as it is not set
                'Model' => $_POST['Model'],
                'Store' => $_POST['store'],//this will insert NULL as it is not set
                'ClientIP' => $clientIP,
                'PartType' => $partType
            )
        );
        
        $Query = "
	SELECT
		SeqNbr,
		TreeLevel,
		IDX.PartType,
		PT.Description,
		COALESCE(Application,Description) AS Application,
		InterchangeNumber InventoryNumber
	FROM IndexList IDX
	INNER JOIN IndexListApp ILA ON ILA.IndexListID = IDX.IndexListID
	INNER JOIN PType PT ON PT.PartType = IDX.PartType
	WHERE {$_POST[Year]} BETWEEN IDX.BeginYear AND IDX.EndYear
		AND ModelNm = '{$_POST[Model]}'
		AND IDX.PartType = {$_POST[PartType]}
	ORDER BY SeqNbr";
        
        $rowCount=0;
        $key=0;
        $prevTreeLevel = 1;
        $parent = [];
        $id=0;
        $results=[];
        $parts = $wpdb->get_results($Query,ARRAY_A);
        
        foreach($parts as $part){
            if($part['TreeLevel'] > $prevTreeLevel){
                $parent[$part['TreeLevel']]= $prevID;
            }
            if($part['TreeLevel'] > 1){
                $part['Parent']=$parent[$part['TreeLevel']];
            }else{
                $part['Parent']=0;
            }
            $results[] = $part;
            $prevTreeLevel = $part['TreeLevel'];
            $prevID= $part['SeqNbr'];
        }
        echo json_encode($results);
        //echo $Query;
        wp_die();
    }
    add_action('wp_ajax_getICOptionsForPart', 'getICOptionsForPart');
    add_action('wp_ajax_nopriv_getICOptionsForPart', 'getICOptionsForPart');
    
    /*********** get IC Vehicle Fitment List **********************************************/
    function getFitmentListing(){
        global $wpdb;
        $output = "<h3>Fitment Information:</h3><ul>";
        $items = $wpdb->get_results("SELECT Application FROM InterchangeList WHERE InterchangeNumber = (SELECT CASE  WHEN RIGHT('{$_POST[InventoryNumber]}',1) IN ('L','R') THEN SUBSTRING('{$_POST[InventoryNumber]}',1,length('{$_POST[InventoryNumber]}')-1) ELSE '{$_POST[InventoryNumber]}' END) ORDER BY Application");
        foreach($items as $item){
            $output.= "<li>".$item->Application."</li>";
        }
        $output.="</ul>";
        echo $output;
        die();
    }
    add_action('wp_ajax_getFitmentListing', 'getFitmentListing');
    add_action('wp_ajax_nopriv_getFitmentListing', 'getFitmentListing');
    
    
    /*** get Part Name and Number ***************/
    function getPartType(){
        global $wpdb;
        $query = "SELECT Description FROM PType WHERE PartType = '{$_POST[Part]}'";
        $part = $wpdb->get_var($query);
        echo $part;
        wp_die();
    }
    add_action('wp_ajax_getPartType', 'getPartType');
    add_action('wp_ajax_nopriv_getPartType', 'getPartType');
    /********** Html for Quick Search Sidebar Widget *********
    <div class="form-style-6">
    <form method="get" id="quickVehicleSearch" action="inventory-2">
    <label for="sidebarMakes">Make</label>
    <select name="makes" id="sidebarMakes" class="makes">
    <option>Any</option>
    </select>
    <label for="sidebarModels">Model</label>
    <select  name="models" id="sidebarModels" class="models">
    <option>Any</option>
    </select>
    <input type="submit" value="Quick Search"/>
    </form>
    </div>
     ***********************************************************/
    function logCouponForm(){
        global $wpdb;
        $duplicateCouponCheck = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM Customers WHERE email = '{$_POST['data']['email']}'"));
        if($duplicateCouponCheck > 0) {
            echo "Email already exists in DB";
            wp_die();
        }
// if not exist in the database then insert it
        else{
            $wpdb->insert(
                'Customers',
                array(
                    'firstname'  => $_POST['data']['first-name'],
                    'lastname'  => $_POST['data']['last-name'],
                    'address' => $_POST['data']['address'],
                    'city' => $_POST['data']['city'],
                    'state' => $_POST['data']['states'],
                    'Zip' => $_POST['data']['zip'],
                    'email' => $_POST['data']['email'],
                    'mobile' => $_POST['data']['phone']
                )
            );
            echo "Record inserted in DB";
            wp_die();
        }
    }
    add_action('wp_ajax_logCouponForm', 'logCouponForm');
    add_action('wp_ajax_nopriv_logCouponForm', 'logCouponForm');

//    function validate_url($url) {
//        $path = parse_url($url, PHP_URL_PATH);
//        $encoded_path = array_map('urlencode', explode('/', $path));
//        $url = str_replace($path, implode('/', $encoded_path), $url);
//
//        return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
//    }
    
    function validate_url($file) {
        $size = getimagesize($file);
        return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);
    }

