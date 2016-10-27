<?php
require_once('../../includes/classes/core.php');
header('Content-Type: text/html; charset=utf-8');

$start_date   = $_REQUEST['start_date'];
$end_date     = $_REQUEST['end_date'];
$operator_id  = $_REQUEST['operator_id'];
$tab_id       = $_REQUEST['tab_id'];
$filter_1     = $_REQUEST['filter_1'];
$filter_2     = $_REQUEST['filter_2'];
$filter_3     = $_REQUEST['filter_3'];
$filter_4     = $_REQUEST['filter_4'];
$filter_5     = $_REQUEST['filter_5'];
$filter_6     = $_REQUEST['filter_6'];
$filter_7     = $_REQUEST['filter_7'];
$filter_8     = $_REQUEST['filter_8'];
$filter_9     = $_REQUEST['filter_9'];

// OPERATOR CHECKER
if($operator_id != 0){
    $op_check = " AND user_id = '$operator_id'";
}else{
    $op_check = '';
}

// STATUS CHECKER
if($tab_id != 0){
    $tab_check = " AND inc_status_id = '$tab_id'";
}else{
    $tab_check = '';
}

// INCOMMING DONE
if($filter_1 == 1){
    $check_1 = 1;
}else{
    $check_1 = 0;
}

// INCOMMING UNDONE
if($filter_2 == 2){
    $check_2 = 2;
}else{
    $check_2 = 0;
}

// INCOMMING UNANSSWER
if($filter_3 == 3){
    $check_3 = 3;
}else{
    $check_3 = 0;
}

// OUT DONE
if($filter_4 == 4){
    $check_4 = 4;
}else{
    $check_4 = 0;
}

// OUT UNDONE
if($filter_5 == 5){
    $check_5 = 5;
}else{
    $check_5 = 0;
}

// OUT UNANSSWER
if($filter_6 == 6){
    $check_6 = 6;
}else{
    $check_6 = 0;
}

// INPUT DONE
if($filter_7 == 7){
    $check_7 = 7;
}else{
    $check_7 = 0;
}
if($filter_1 != '' || $filter_2 != '' || $filter_3 != '' || $filter_4 != '' || $filter_5 != '' || $filter_6 != '' || $filter_7 != ''){
    $main_status = " AND main_status IN(0,$check_1,$check_2,$check_3,$check_4,$check_5,$check_6,$check_7)";
}else{
    $main_status = '';
}

$rResult = mysql_query("SELECT 	original_id,
                 				date,
                 				queue,
                 				cl_ab,
                 				cl_ab_num,
                 				sc,
                     	  	    ic1,
                     	  	    inst
                        FROM 	calls
                        WHERE DATE(date) >= '$start_date' AND DATE(date) <= '$end_date' AND NOT ISNULL(main_status) $op_check $tab_check $main_status");

$total_num = 2;
if ($rResult) {
    while ( $aRow = mysql_fetch_array( $rResult ) ){

        $dat .='
    			<ss:Row>
    	            <ss:Cell>
    					<ss:Data ss:Type="String">'.$aRow[0].'</ss:Data>
    				</ss:Cell>
    				<ss:Cell>
    					<ss:Data ss:Type="String">'.$aRow[1].'</ss:Data>
    				</ss:Cell>
    				<ss:Cell>
    					<ss:Data ss:Type="String">'.$aRow[2].'</ss:Data>
    				</ss:Cell>
    				<ss:Cell>
    					<ss:Data ss:Type="String">'.$aRow[3].'</ss:Data>
    				</ss:Cell>
    				<ss:Cell>
    					<ss:Data ss:Type="String">'.$aRow[4].'</ss:Data>
    				</ss:Cell>
    				<ss:Cell>
    					<ss:Data ss:Type="String">'.$aRow[5].'</ss:Data>
    				</ss:Cell>
    			    <ss:Cell>
    					<ss:Data ss:Type="String">'.$aRow[6].'</ss:Data>
    				</ss:Cell>
                    <ss:Cell>
    					<ss:Data ss:Type="String">'.$aRow[7].'</ss:Data>
    				</ss:Cell>
    			</ss:Row>';
        $total_num++;
    }
}else{
    $status=2;
}
$name = "შემომავალი ზარი";

$data = '
        <?xml version="1.0" encoding="utf-8"?><?mso-application progid="Excel.Sheet"?>
        <ss:Workbook xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:o="urn:schemas-microsoft-com:office:office">
        	<o:DocumentProperties>
        		<o:Title>'.$name.'</o:Title>
        	</o:DocumentProperties>
        	<ss:ExcelWorkbook>
        		<ss:WindowHeight>9000</ss:WindowHeight>
        		<ss:WindowWidth>50000</ss:WindowWidth>
        		<ss:ProtectStructure>false</ss:ProtectStructure>
        		<ss:ProtectWindows>false</ss:ProtectWindows>
        	</ss:ExcelWorkbook>
        	<ss:Styles>
        		<ss:Style ss:ID="Default">
        			<ss:Alignment ss:Vertical="Center" ss:Horizontal="Center" ss:WrapText="1" />
        			<ss:Font ss:FontName="Sylfaen" ss:Size="12" />
        			<ss:Interior />
        			<ss:NumberFormat />
        			<ss:Protection />
        			<ss:Borders>
        				<ss:Border ss:Position="Top" ss:Color="#000000" ss:Weight="1" ss:LineStyle="Continuous" />
        				<ss:Border ss:Position="Bottom" ss:Color="#000000" ss:Weight="1" ss:LineStyle="Continuous" />
        				<ss:Border ss:Position="Left" ss:Color="#000000" ss:Weight="1" ss:LineStyle="Continuous" />
        				<ss:Border ss:Position="Right" ss:Color="#000000" ss:Weight="1" ss:LineStyle="Continuous" />
        			</ss:Borders>
        		</ss:Style>
        		<ss:Style ss:ID="title">
        			<ss:Borders />
        			<ss:NumberFormat ss:Format="@" />
        			<ss:Alignment ss:WrapText="1" ss:Horizontal="Center" ss:Vertical="Center" />
        		</ss:Style>
        		<ss:Style ss:ID="headercell">
        			<ss:Font ss:Bold="1" />
        			<ss:Interior ss:Pattern="Solid" />
        			<ss:Alignment ss:WrapText="1" ss:Horizontal="Center" ss:Vertical="Center" />
        		</ss:Style>
        		<ss:Style ss:ID="headercellRotated">
        			<ss:Font ss:Bold="1" />
        			<ss:Interior ss:Pattern="Solid" />
        			<ss:Alignment ss:WrapText="1" ss:Horizontal="Center" ss:Rotate="90" ss:Vertical="Center" />
        		</ss:Style>
        	</ss:Styles>
        	<ss:Worksheet ss:Name="'.$name.'">
        		<ss:Names>
        			<ss:NamedRange ss:Name="Print_Titles" ss:RefersTo="=\' '.$name.' \'!R1:R2" />
        		</ss:Names>
        		<ss:Table x:FullRows="1" x:FullColumns="1" ss:ExpandedColumnCount="10" ss:ExpandedRowCount="'.$total_num.'">
        			<ss:Column ss:AutoFitWidth="1" ss:Width="150" />
        			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
        			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
        			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
        			<ss:Column ss:AutoFitWidth="1" ss:Width="200" />
        			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
        			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
        			<ss:Column ss:AutoFitWidth="1" ss:Width="100" />
        			<ss:Row ss:Height="40">
        				<ss:Cell ss:StyleID="title" ss:MergeAcross="9">
        					<ss:Data xmlns:html="http://www.w3.org/TR/REC-html40" ss:Type="String">
        						<html:B>
        							<html:Font html:Size="14">'.$name.'</html:Font>
        						</html:B>
        					</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        			</ss:Row>
        			<ss:Row ss:AutoFitHeight="1" ss:Height="50">
        				<ss:Cell ss:StyleID="headercell">
        					<ss:Data ss:Type="String">№</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        			    <ss:Cell ss:StyleID="headercell">
        					<ss:Data ss:Type="String">თარიღი</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        				<ss:Cell ss:StyleID="headercell">
        					<ss:Data ss:Type="String">ტელეფონი</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        				<ss:Cell ss:StyleID="headercell">
        					<ss:Data ss:Type="String">აბონენტი</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        				<ss:Cell ss:StyleID="headercell">
        					<ss:Data ss:Type="String">აბონენტის ნომერი</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        				<ss:Cell ss:StyleID="headercell">
        					<ss:Data ss:Type="String">მ/ცენტრი</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        			    <ss:Cell ss:StyleID="headercell">
        					<ss:Data ss:Type="String">კატეგორია</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        				<ss:Cell ss:StyleID="headercell">
        					<ss:Data ss:Type="String">რეაგირება</ss:Data>
        					<ss:NamedCell ss:Name="Print_Titles" />
        				</ss:Cell>
        			</ss:Row>';
$data .= $dat;


$data .='</ss:Table>
    		<x:WorksheetOptions>
    			<x:PageSetup>
    				<x:Layout x:CenterHorizontal="1" x:Orientation="Portrait" />
    				<x:Header x:Data="&amp;R&#10;&#10;&amp;D" />
    				<x:Footer x:Data="Page &amp;P of &amp;N" x:Margin="0.5" />
    				<x:PageMargins x:Top="0.5" x:Right="0.5" x:Left="0.5" x:Bottom="0.8" />
    			</x:PageSetup>
    			<x:FitToPage />
    			<x:Print>
    				<x:PrintErrors>Blank</x:PrintErrors>
    				<x:FitWidth>1</x:FitWidth>
    				<x:FitHeight>32767</x:FitHeight>
    				<x:ValidPrinterInfo />
    				<x:VerticalResolution>600</x:VerticalResolution>
    			</x:Print>
    			<x:Selected />
    			<x:DoNotDisplayGridlines />
    			<x:ProtectObjects>False</x:ProtectObjects>
    			<x:ProtectScenarios>False</x:ProtectScenarios>
    		</x:WorksheetOptions>
	   </ss:Worksheet>
    </ss:Workbook>
		';


if ($status==2) {
    $null= 1;
    echo json_encode($null);
}else{
    echo json_encode($data);
}

file_put_contents('excel.xls', $data);



