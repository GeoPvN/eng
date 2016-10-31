/**
* @summary     DataTables Function, jQtransform Plugin Function
* @version     2.3.6
* @contact
*
* @copyright Copyright 2012-2013 Levani Ramazashvili, all rights reserved.
*/


/**
* @summary     GetDataTable
* @version     1.2.8
* @requested   Table Selector Name,
Server Side aJaxURL,
Action,
Colum Number,
Custom Request,
Hidden Colum & Check Box ID,
Menu Array,
Sort Colum ID,
Sort Method
*/

// start close all audio file
$(document).on("click", ".ui-dialog-titlebar-close", function () {
	$('audio').each(function(){
	    this.pause(); // Stop playing
	    this.currentTime = 0; // Reset time
	}); 
});
// end close all audio file

function GetDataTable(tname, aJaxURL, action, count, data, hidden, length, sorting, sortMeth, total, colum_change) {
    if (empty(data))
        data = "";
 
    if (empty(tname))
        tname = "example";

    var asInitVals = new Array();

    if (empty(sorting)) {
        sorting = hidden;
    }

    //"asc" or "desc"
    if (empty(sortMeth))
        sortMeth = "asc";

    var oTable = "";

    //Defoult Length
    var dLength = [[10, 30, 50, -1], [10, 30, 50, "ALL"]];

    if (!empty(length))
        dLength = length;

    var imex = {
		"sSwfPath": "media/swf/copy_csv_xls.swf",
		"aButtons": [ "copy",
		              {
						"sExtends": "xls",
						"sFileName": GetDateTime(1) + ".csv"
		              },
		              "print" ]
	};

    oTable = $("#" + tname).dataTable({
        "bDestroy": true, 																				//Reinicialization table
        "bJQueryUI": true, 																				//Add jQuery ThemeRoller
        //"bStateSave": true, 																			//state saving
        //"bFilter": true,
        "sDom": colum_change,  
		"oTableTools": imex,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "aaSorting": [[sorting, sortMeth]],
        "iDisplayLength": dLength[0][0],
        "aLengthMenu": dLength,                                                                         //Custom Select Options
        "sAjaxSource": aJaxURL,
        "autoWidth": false,
        "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
        	if(!empty(total)){
	        	var iTotal = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	            for ( var i = 0 ; i < aaData.length ; i++ )
	            {
	            	for ( var j = 0 ; j < total.length ; j++ )
	                {
		                iTotal[j] += aaData[i][total[j]]*1;
	                }
	            }

	            var iPage = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
				for ( var i = iStart ; i < iEnd ; i++ )
				{
					for ( var j = 0 ; j < total.length ; j++ )
	                {
						iPage[j] += aaData[ aiDisplay[i] ][total[j]]*1;
	                }
				}

	            var nCells = nRow.getElementsByTagName('th');
	            for ( var k = 0 ; k < total.length ; k++ )
	            {
	            	nCells[total[k]].innerHTML = (parseInt(iPage[k] * 100) / 100).toFixed(2) + '<br />' + (parseInt(iTotal[k] * 100) / 100).toFixed(2) + ' ';
	            }
        	}
		},
        "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
        	
            oSettings.jqXHR = $.ajax({
                url: sSource,
                data: "act=" + action + "&count=" + count + "&hidden=" + hidden + "&" + data,           //Server Side Requests
                success: function (data) {
                    fnCallback(data);
                    onhovercolor('#A3D0E4');
//                    $('#'+tname+' tbody td').each(function(index){
//                    	 this.setAttribute( 'title', $(this).text());
//                	});
                    if (typeof (data.error) != "undefined") {
                        if (data.error != "") {
                            alert(data.error);
                        } else {
                            if ($.isFunction(window.DatatableEnd)) {
                                //execute it
                                DatatableEnd(tname);                                
                            }
                        }
                    }
                }
            });
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            /* Append the grade to the default row class name */
        	$('td:eq(1)', nRow).attr( 'title', $('td:eq(1)', nRow).text() );
        	$('td:eq(2)', nRow).attr( 'title', $('td:eq(2)', nRow).text() );
        	$('td:eq(3)', nRow).attr( 'title', $('td:eq(3)', nRow).text() );
        	$('td:eq(4)', nRow).attr( 'title', $('td:eq(4)', nRow).text() );
        	$('td:eq(5)', nRow).attr( 'title', $('td:eq(5)', nRow).text() );
        	$('td:eq(6)', nRow).attr( 'title', $('td:eq(6)', nRow).text() );
        	$('td:eq(7)', nRow).attr( 'title', $('td:eq(7)', nRow).text() );
        	$('td:eq(8)', nRow).attr( 'title', $('td:eq(8)', nRow).text() );
        	$('td:eq(9)', nRow).attr( 'title', $('td:eq(9)', nRow).text() );
        	$('td:eq(10)', nRow).attr( 'title', $('td:eq(10)', nRow).text() );
        	$('td:eq(11)', nRow).attr( 'title', $('td:eq(11)', nRow).text() );
        	$('td:eq(12)', nRow).attr( 'title', $('td:eq(12)', nRow).text() );
        },
        "aoColumnDefs": [
              { "sClass": "colum_hidden", "bSortable": false, "bSearchable": false, "aTargets": [hidden]}	//hidden collum
            ],
        "oLanguage": {																						//Localization
            "sProcessing": "Loading...",
            "sLengthMenu": "_MENU_",
            "sZeroRecords": "Records could not be found",
            "sInfo": "_START_-From _END_-Up to all: _TOTAL_",
            "sInfoEmpty": "0-From 0-Up to all: 0",
            "sInfoFiltered": "(Filtrated _MAX_-From _TOTAL_ Record)",
            "sInfoPostFix": "",
            "sSearch": "Search",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "First",
                "sPrevious": "Forward",
                "sNext": "Next",
                "sLast": "last"
            }
        }
    });

    //new $.fn.dataTable.ColReorder(oTable);
    $("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").keyup(function () {
    	
        /* Filter on the column (the index) of this element */
        oTable.fnFilter(this.value, $("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").index(this));
    });

    /*
    * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
    * the footer
    */
    $("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").each(function (i) {
        asInitVals[i] = this.value;
    });

    $("#"+tname+" thead input,  .dataTables_scrollFoot .dataTable tfoot input").focus(function () {
        if (this.className == "search_init") {
            this.className = "";
            this.value = "";
        }
    });

    $("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").blur(function (i) {
        if (this.value == "") {
            this.className = "search_init";
            this.value = asInitVals[$("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").index(this)];
        }
    });

    $(".DTTT_button").hover(
		  function () {
		    $(this).addClass("ui-state-hover");
		  },
		  function () {
		    $(this).removeClass("ui-state-hover");
		  }
    );	
}

function GetDataTableSD(tname, aJaxURL, action, count, data, hidden, length, sorting, sortMeth, total, colum_change) {
    if (empty(data))
        data = "";
 
    if (empty(tname))
        tname = "example";

    var asInitVals = new Array();

    if (empty(sorting)) {
        sorting = hidden;
    }

    //"asc" or "desc"
    if (empty(sortMeth))
        sortMeth = "asc";

    var oTable = "";

    //Defoult Length
    var dLength = [[10, 30, 50, 100, 500, 1000], [10, 30, 50, 100, 500, 1000]];

    if (!empty(length))
        dLength = length;

    var imex = {
		"sSwfPath": "media/swf/copy_csv_xls.swf",
		"aButtons": [ "copy",
		              {
						"sExtends": "xls",
						"sFileName": GetDateTime(1) + ".csv"
		              },
		              "print" ]
	};

    oTable = $("#" + tname).dataTable({
        "bDestroy": true, 																				//Reinicialization table
        "bJQueryUI": true, 																				//Add jQuery ThemeRoller
        //"bStateSave": true, 																			//state saving
        //"bFilter": true,
        "sDom": colum_change,  
		"oTableTools": imex,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "aaSorting": [[sorting, sortMeth]],
        "iDisplayLength": dLength[0][0],
        "aLengthMenu": dLength,                                                                         //Custom Select Options
        "processing": true,
		"serverSide": true,
		"ajax": {
            "url": aJaxURL,
            "data": function ( d ) {
            	d.act = action;
				d.start_date = data.start_date;
				d.end_date = data.end_date;
				d.operator_id = data.operator_id;
				d.tab_id = data.tab_id;
				d.user_info_id = data.user_info_id;
				d.filter_1 = data.filter_1;
				d.filter_2 = data.filter_2;
				d.filter_3 = data.filter_3;
				d.filter_4 = data.filter_4;
				d.filter_5 = data.filter_5;
				d.filter_6 = data.filter_6;
				d.filter_7 = data.filter_7;
				$("#table_index tbody").html("<tr><td colspan=10 style=\"font-size:16px;color: red;font-weight:bold;\">იტვირთება......</td></tr>");
        	}
		},
        //"sAjaxSource": aJaxURL,
        "autoWidth": false,
        "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
        	if(!empty(total)){
	        	var iTotal = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	            for ( var i = 0 ; i < aaData.length ; i++ )
	            {
	            	for ( var j = 0 ; j < total.length ; j++ )
	                {
		                iTotal[j] += aaData[i][total[j]]*1;
	                }
	            }

	            var iPage = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
				for ( var i = iStart ; i < iEnd ; i++ )
				{
					for ( var j = 0 ; j < total.length ; j++ )
	                {
						iPage[j] += aaData[ aiDisplay[i] ][total[j]]*1;
	                }
				}

	            var nCells = nRow.getElementsByTagName('th');
	            for ( var k = 0 ; k < total.length ; k++ )
	            {
	            	nCells[total[k]].innerHTML = (parseInt(iPage[k] * 100) / 100).toFixed(2) + '<br />' + (parseInt(iTotal[k] * 100) / 100).toFixed(2) + ' ';
	            }
        	}
		},
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            /* Append the grade to the default row class name */
        	$('td:eq(1)', nRow).attr( 'title', $('td:eq(1)', nRow).text() );
        	$('td:eq(2)', nRow).attr( 'title', $('td:eq(2)', nRow).text() );
        	$('td:eq(3)', nRow).attr( 'title', $('td:eq(3)', nRow).text() );
        	$('td:eq(4)', nRow).attr( 'title', $('td:eq(4)', nRow).text() );
        	$('td:eq(5)', nRow).attr( 'title', $('td:eq(5)', nRow).text() );
        	$('td:eq(6)', nRow).attr( 'title', $('td:eq(6)', nRow).text() );
        	$('td:eq(7)', nRow).attr( 'title', $('td:eq(7)', nRow).text() );
        	$('td:eq(8)', nRow).attr( 'title', $('td:eq(8)', nRow).text() );
        	$('td:eq(9)', nRow).attr( 'title', $('td:eq(9)', nRow).text() );
        	$('td:eq(10)', nRow).attr( 'title', $('td:eq(10)', nRow).text() );
        	$('td:eq(11)', nRow).attr( 'title', $('td:eq(11)', nRow).text() );
        	$('td:eq(12)', nRow).attr( 'title', $('td:eq(12)', nRow).text() );
        },
        "aoColumnDefs": [
              { "sClass": "colum_hidden", "bSortable": false, "bSearchable": false, "aTargets": [hidden]}	//hidden collum
            ],
        "oLanguage": {																						//Localization
            "sProcessing": "Loading...",
            "sLengthMenu": "_MENU_",
            "sZeroRecords": "Records could not be found",
            "sInfo": "_START_-From _END_-Up to all: _TOTAL_",
            "sInfoEmpty": "0-From 0-Up to all: 0",
            "sInfoFiltered": "(Filtrated _MAX_-From _TOTAL_ Record)",
            "sInfoPostFix": "",
            "sSearch": "Search",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "First",
                "sPrevious": "Forward",
                "sNext": "Next",
                "sLast": "Last"
            }
        }
    });    

    //new $.fn.dataTable.ColReorder(oTable);
    $("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").keyup(function () {
    	
        /* Filter on the column (the index) of this element */
        oTable.fnFilter(this.value, $("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").index(this));
    });

    /*
    * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
    * the footer
    */
    $("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").each(function (i) {
        asInitVals[i] = this.value;
    });

    $("#"+tname+" thead input,  .dataTables_scrollFoot .dataTable tfoot input").focus(function () {
        if (this.className == "search_init") {
            this.className = "";
            this.value = "";
        }
    });

    $("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").blur(function (i) {
        if (this.value == "") {
            this.className = "search_init";
            this.value = asInitVals[$("#"+tname+" thead input, .dataTables_scrollFoot .dataTable tfoot input").index(this)];
        }
    });

    $(".DTTT_button").hover(
		  function () {
		    $(this).addClass("ui-state-hover");
		  },
		  function () {
		    $(this).removeClass("ui-state-hover");
		  }
    );	
}


function GetDataTable3(tname, aJaxURL, action, count, data, hidden, length, sorting, sortMeth, total) {
    if (empty(data))
        data = "";
    
    if (empty(tname))
        tname = "example";
    
    var asInitVals = new Array();
    
    if (empty(sorting)) {
        sorting = hidden;
    }
    
    //"asc" or "desc"
    if (empty(sortMeth))
        sortMeth = "asc";
    
    var oTable = "";
    
    //Defoult Length
    var dLength = [[15, 30, 50, -1], [15, 30, 50, "ყველა"]];
    
    if (!empty(length))
        dLength = length;
    
    var imex = {
		"sSwfPath": "media/swf/copy_csv_xls.swf",
		"aButtons": [ "copy",
		              {
						"sExtends": "xls",
						"sFileName": GetDateTime(1) + ".csv"
		              },
		              "print" ]
	};
    
    oTable = $("#" + tname).dataTable({
        "bDestroy": true, 																				//Reinicialization table
        "bJQueryUI": true, 																				//Add jQuery ThemeRoller
        //"bStateSave": true, 																			//state saving
        "sDom": "<'dataTable_buttons'T><'H'lfrt><'dataTable_content't><'F'ip>",
		"oTableTools": imex,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "aaSorting": [[sorting, sortMeth]],
        "iDisplayLength": dLength[0][0],
        "aLengthMenu": dLength,                                                                         //Custom Select Options
        "sAjaxSource": aJaxURL,
        "bAutoWidth": false,
        "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
        	if(!empty(total)){
        		//total[9,10]
        		//total sum
	        	var iTotal 	= [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	        	var iPage 	= [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	        	var tTime	= 0; 
	        	var pTime	= 0;

	            for ( var i = 0 ; i < total.length ; i++ )
		        {	       
	            	var t1		= 0;
		        	var t2		= 0;
		        	var t3		= 0;
		        	var s		= 0;
		        	var m		= 0;
		        	var h		= 0;

	            	for ( var j = 0 ; j < aaData.length ; j++ )
	                {
		                tTime = aaData[j][total[i]];
						
						if(tTime!=0 && tTime!=null){
							t1 = parseInt(tTime.substring(0,2));
							t2 = parseInt(tTime.substring(3,5));
							t3 = parseInt(tTime.substring(6,8));
							
							s 	+= parseInt(t1*60*60 + t2*60 + t3);
			            	m 	+=	Math.floor(s/60); 
			            	s	=	s%60;
			            	h 	+=	Math.floor(m/60); 
			            	m	=	m%60;
						}
						
						iTotal[i] = (h+':'+m+':'+s);
			            console.log(iTotal);
	                } 
	            }

			for ( var i = 0 ; i < total.length; i++ )
				{
					var t1		= 0;
		        	var t2		= 0;
		        	var t3		= 0;
		        	var s		= 0;
		        	var m		= 0;
		        	var h		= 0;
		        	
					for ( var j = iStart; j < iEnd; j++ )
	                {
						pTime = aaData[ aiDisplay[j]][total[i]];
						
						if(pTime!=0 && pTime!=null){
							t1 = parseInt(pTime.substring(0,2));
							t2 = parseInt(pTime.substring(3,5));
							t3 = parseInt(pTime.substring(6,8));
							
							s 	+= 	parseInt(t1*60*60 + t2*60 + t3);
			            	m 	+=	Math.floor(s/60); 
			            	s	=	s%60;
			            	h 	+=	Math.floor(m/60); 
			            	m	=	m%60;
						}
						iPage[i] = (h+':'+m+':'+s);
	                }     						
				}
			
	            var nCells = nRow.getElementsByTagName('th');
	            for ( var k = 0 ; k < total.length ; k++ )
	            {
	            	nCells[total[k]].innerHTML = iPage[k] + '<br />' + iTotal[k] + '';
	            }
        	}
		},
        "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
            oSettings.jqXHR = $.ajax({
                url: sSource,
                data: "act=" + action + "&count=" + count + "&hidden=" + hidden + "&" + data,           //Server Side Requests
                success: function (data) {
                    fnCallback(data);
                    if (typeof (data.error) != "undefined") {
                        if (data.error != "") {
                            alert(data.error);
                        } else {
                            if ($.isFunction(window.DatatableEnd)) {
                                //execute it
                                DatatableEnd(tname);
                            }
                        }
                    }
                }
            });
        },
        "aoColumnDefs": [
              { "sClass": "colum_hidden", "bSortable": false, "bSearchable": false, "aTargets": [hidden]}	//hidden collum
            ],
        "oLanguage": {																						//Localization
            "sProcessing": "Loading...",
            "sLengthMenu": "_MENU_",
            "sZeroRecords": "Records could not be found",
            "sInfo": "_START_-From _END_-Up to all: _TOTAL_",
            "sInfoEmpty": "0-From 0-Up to all: 0",
            "sInfoFiltered": "(Filtrated _MAX_-From _TOTAL_ Record)",
            "sInfoPostFix": "",
            "sSearch": "Search",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "First",
                "sPrevious": "Forward",
                "sNext": "Next",
                "sLast": "last"
            }
        }
    });
    
    $("#" + tname + " thead input").keyup(function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter(this.value, $("#" + tname + " thead input").index(this));
    });
    
    /*
    * Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
    * the footer
    */
    $("#" + tname + " thead input").each(function (i) {
        asInitVals[i] = this.value;
    });

    $("#" + tname + " thead input").focus(function () {
        if (this.className == "search_init") {
            this.className = "";
            this.value = "";
        }
    });

    $("#" + tname + " thead input").blur(function (i) {
        if (this.value == "") {
            this.className = "search_init";
            this.value = asInitVals[$("#" + tname + " thead input").index(this)];
        }
    });
        
    $(".DTTT_button").hover(
		  function () {
		    $(this).addClass("ui-state-hover");
		  },
		  function () {
		    $(this).removeClass("ui-state-hover");
		  }
    );    
}

function onhovercolor(color){
	var next_color = '';
	$( ".display tbody tr" )
	.mouseenter(function() {	
		if($(this).css('backgroundColor') == 'rgb(230, 242, 248)'){
			next_color = 'rgb(230, 242, 248)';
		}else{
			next_color = '#FFF';
		}		
		$(this).css( 'background', color );
	})
	.mouseleave(function() {
		$(this).css( 'background', next_color );
	});
}

function GetDataTableServer(tname, aJaxURL, action, count, data, hidden, length, sorting, sortMeth, total) {
    if (empty(data))
        data = "";

    if (empty(tname))
        tname = "example";

    var asInitVals = new Array();

    if (empty(sorting)) {
        sorting = hidden;
    }

    //"asc" or "desc"
    if (empty(sortMeth))
        sortMeth = "asc";

    var oTable = "";

    //Defoult Length
    var dLength = [[30, 50, 100, 200, 500], [30, 50, 100, 200, 500]];

    if (!empty(length))
        dLength = length;

    var imex = {
		"sSwfPath": "media/swf/copy_csv_xls.swf",
		"aButtons": [ "copy",
		              {
						"sExtends": "xls",
						"sFileName": GetDateTime(1) + ".csv"
		              },
		              "print" ]
	};

    oTable = $("#" + tname).dataTable({
        "bDestroy": true, 																				//Reinicialization table
        "bJQueryUI": true, 																				//Add jQuery ThemeRoller
        //"bStateSave": true, 																			//state saving
        "sDom": "<'dataTable_buttons'T><'H'lfrt><'dataTable_content't><'F'ip>",
		"oTableTools": imex,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "aaSorting": [[sorting, sortMeth]],
        "iDisplayLength": dLength[0][0],
        "aLengthMenu": dLength,                                                                         //Custom Select Options
        //"sAjaxSource": aJaxURL,
        "bAutoWidth": false,
        "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
        	if(!empty(total)){
	        	var iTotal = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	            for ( var i = 0 ; i < aaData.length ; i++ )
	            {
	            	for ( var j = 0 ; j < total.length ; j++ )
	                {
		                iTotal[j] += aaData[i][total[j]]*1;
	                }
	            }

	            var iPage = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
				for ( var i = iStart ; i < iEnd ; i++ )
				{
					for ( var j = 0 ; j < total.length ; j++ )
	                {
						iPage[j] += aaData[ aiDisplay[i] ][total[j]]*1;
	                }
				}

	            var nCells = nRow.getElementsByTagName('th');
	            for ( var k = 0 ; k < total.length ; k++ )
	            {
	            	nCells[total[k]].innerHTML = parseInt(iPage[k] * 100) / 100 + ' <br />' + parseInt(iTotal[k] * 100) / 100 + '';
	            }
        	}
		},
        
        "aoColumnDefs": [
              { "sClass": "colum_hidden", "bSortable": false, "bSearchable": false, "aTargets": [hidden]}	//hidden collum
            ],
        "processing": true,
		"serverSide": true,
		"ajax": {
            "url": aJaxURL,
            "data": function ( d ) {d.act = action; d.check = data}
		},
		"stateSave": true,
		"oLanguage": {																						//Localization
            "sProcessing": "Loading...",
            "sLengthMenu": "_MENU_",
            "sZeroRecords": "Records could not be found",
            "sInfo": "_START_-From _END_-Up to all: _TOTAL_",
            "sInfoEmpty": "0-From 0-Up to all: 0",
            "sInfoFiltered": "(Filtrated _MAX_-From _TOTAL_ Record)",
            "sInfoPostFix": "",
            "sSearch": "Search",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "First",
                "sPrevious": "Forward",
                "sNext": "Next",
                "sLast": "last"
            }
        }
    });

    $("#" + tname + " thead input").keyup(function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter(this.value, $("#" + tname + " thead input").index(this));
    });

    /*
    * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
    * the footer
    */
    $("#" + tname + " thead input").each(function (i) {
        asInitVals[i] = this.value;
    });

    $("#" + tname + " thead input").focus(function () {
        if (this.className == "search_init") {
            this.className = "";
            this.value = "";
        }
    });

    $("#" + tname + " thead input").blur(function (i) {
        if (this.value == "") {
            this.className = "search_init";
            this.value = asInitVals[$("#" + tname + " thead input").index(this)];
        }
    });

    $(".DTTT_button").hover(
		  function () {
		    $(this).addClass("ui-state-hover");
		  },
		  function () {
		    $(this).removeClass("ui-state-hover");
		  }
    );
}

function GetNotify(message){
	jNotify(message,
			{
			  autoHide : true,
			  clickOverlay : true,
			  MinWidth : 250,
			  TimeShown : 1000,
			  ShowTimeEffect : 200,
			  HideTimeEffect : 200,
			  LongTrip :20,
			  HorizontalPosition : 'right',
			  VerticalPosition : 'bottom',
			  ShowOverlay : true,
	   		  ColorOverlay : '#000',
			  OpacityOverlay : 0.3,
			  onClosed : function(){
			  },
			  onCompleted : function(){
			  }
			});
}

function Check(timeout,aJaxURL){
	var start = new Date().getTime();
	var time = 0;

	function instance() {
		if(time <= timeout + 100 ){
			    if (time == timeout && $("#chechedStatus").val() == "true" ) {
			    	time += 100;
		        	param 		= new Object();
		        	param.act	= 'check';
				    $.ajax({
				        url: aJaxURL,
					    data: param,
				        success: function(data) {
							if(typeof(data.error) != "undefined")
							{
								if(data.error != "")
								{
									alert(data.error);
								}else
								{
									if( data.message != null ){
											GetNotify(data.message);
											document.getElementById("jNotify").onclick = function(){ time = 0; LoadTable(); };
									}else{
										time = 0;
									}
								}
							}
					    }
				    });
			    }else{
			        time += 100;
			    }
		}else{
			time = 0;
		}
	    window.setTimeout(instance, 100);
	}
	window.setTimeout(instance, 100);
}



/**
* @summary     GetButtons
* @version     1.0.4
* @requested   Add Button Selector Name, Disable Button Selector Name, Export Button Selector Name
*
* http://www.petefreitag.com/cheatsheets/jqueryui-icons/
*/
function GetButtons(add, dis, exp, cancel, clear) {
    if (!empty(add)) {
        $("#" + add).button({
            icons: {
                primary: "ui-icon-plus"
            }
        });
    }
    if (!empty(dis)) {
        $("#" + dis).button({
            icons: {
                primary: "ui-icon-trash"
            }
        });
    }
    if (!empty(exp)) {
        $("#" + exp).button({
            icons: {
                primary: "ui-icon-arrowreturnthick-1-n"
            }
        });
    }
    if (!empty(cancel)) {
        $("#" + cancel).button({
            icons: {
                primary: "ui-icon-cancel"
            }
        });
    }
    if (!empty(clear)) {
        $("#" + clear).button({
            icons: {
                primary: "ui-icon-shuffle"
            },
            text: false
        });
    }
}

function que(tname, fname, aJaxURL){
	$(document).on("dblclick", "#" + tname + " tbody tr td:nth-child(2)", function () {
        var nTds = $(this).siblings(0).html();
        var empty = $(this).siblings(0).attr("class");

        if (empty != "dataTables_empty") {
            var rID = nTds;
            if(rID != ''){
	            $.ajax({
	                url: aJaxURL,
	                type: "POST",
	                data: "act=get_edit_page&id=" + rID,
	                dataType: "json",
	                success: function (data) {
	                    if (typeof (data.error) != "undefined") {
	                        if (data.error != "") {
	                            alert(data.error);
	                        } else {
	                        	tinymce.remove("#answer");
	                            $("#" + fname).html(data.page);
	                            if ($.isFunction(window.LoadDialog)) {
	                                //execute it
	                                LoadDialog(fname);
	                                tinymce.init({selector:'#answer'});
	                            }
	                        }
	                    }
	                }
	            });
            }
        }
    });
}

/**
* @summary     SetEvents
* @version     1.2.4
* @requested   Add Button Selector Name,
*              Disable Button Selector Name,
*              Check All Selector Name,
*              Table Selector Name,
*              Form Selector Name,
*              Server Side aJaxURL,
*			   Custom Request
*/
function SetEvents(add, dis, check, tname, fname, aJaxURL, c_data, tbl,col_num,act,change_colum,lenght,other_act) {
    if (empty(c_data))
        c_data = "";
        $("#"+tname+" tbody").off("dblclick");
        $("#" + add).off("click");

    // Add Event
    $("#" + add).on("click", function () {
    	 $.ajax({
            url: aJaxURL,
            type: "POST",
            data: "act=get_add_page&" + c_data,
            dataType: "json",
            success: function (data) {
                if (typeof (data.error) != "undefined") {
                    if (data.error != "") {
                        alert(data.error);
                    } else {
                        $("#" + fname).html(data.page);
                        if ($.isFunction(window.LoadDialog)) {
                            //execute it
                            LoadDialog(fname);
                        }
                    }
                }
            }
        });
    });

    /* Edit Event */
    $("#" + tname + " tbody").on("dblclick", "tr", function () {
        var nTds = $("td", this);
        var empty = $(nTds[0]).attr("class");

        if (empty != "dataTables_empty") {
            var rID = $(nTds[0]).text();
            if(rID != ''){
	            $.ajax({
	                url: aJaxURL,
	                type: "POST",
	                data: "act=get_edit_page&id=" + rID + "&" + c_data,
	                dataType: "json",
	                success: function (data) {
	                    if (typeof (data.error) != "undefined") {
	                        if (data.error != "") {
	                            alert(data.error);
	                        } else {
	                            $("#" + fname).html(data.page);
	                            if ($.isFunction(window.LoadDialog)) {
	                                //execute it
	                                LoadDialog(fname);
	                            }
	                        }
	                    }
	                }
	            });
            }
        }
    });
    
    $(document).keydown(function(event){
        if(event.which=="17"){
        	cntrlIsPressed = true;
        }
        if(event.which=="46"){
        	$("#" + dis).click();
        }
    });

    $(document).keyup(function(){
        cntrlIsPressed = false;
    });

    var cntrlIsPressed = false;

    
    $("#" + tname + " tbody").on("click", "td:not(:last-child)", function () {
        var nTds = $($(this).siblings())[0];        
        var rID  = $(nTds).text();
        
        if(cntrlIsPressed)
        {
        	
        }else{
        	if(!$("#" + tname + "  INPUT[name='check_"+rID+"']").is(":checked")){
        		$("#" + tname + "  .check").prop("checked", false);
        	}
        }
        
        $("#" + tname + "  INPUT[name='check_"+rID+"']").prop("checked", !$("#" + tname + "  INPUT[name='check_"+rID+"']").is(":checked"));
        
    });

    /* Disable Event */
    $("#" + dis).on("click", function () {
    	
        var data = $(".check:checked").map(function () {
            return this.value;
        }).get();
    	

        for (var i = 0; i < data.length; i++) {
            $.ajax({
                url: aJaxURL,
                type: "POST",
                data: "act=disable&id=" + data[i] + "&" + c_data,
                dataType: "json",
                success: function (data) {
	                    if (data.error != "") {
	                        alert(data.error);
	                    } else {
	                    	if(tbl=='example-brack'){
	                    		LoadTable_breack();
	                    	}
	                    	LoadTable(tbl,col_num,act,change_colum,lenght,other_act);
	                    	LoadTable('index',colum_number,main_act,change_colum_main);
	                    	LoadTable('client',6,'get_list_person',"<'F'lip>");
	                    	LoadTable('project',5,'get_list_project',"<'F'lip>");
	                    	LoadTable('number',5,'get_list_number',"<'F'lip>");
	                    	LoadTable('import',6,'get_list_import',"<'F'lip>");
	                    	
	                        $("#" + check).attr("checked", false);
	                    }
                }
            });
        }

    });
    
    /* Check All */
    $("#" + check).on("click", function () {
    	$("#" + tname + " INPUT[type='checkbox']").prop("checked", $("#" + check).is(":checked"));
    });

    $(document).on("dialogbeforeclose", "#" + fname, function( event, ui ) {
    	if($(this).is(":ui-dialog") || $(this).is(":data(dialog)")){
			$(this).dialog("destroy");
		}
	});
}

function MyEvent(aJaxURL, addButton, deleteButton, Check, dialogID, saveButtonID, closeButtonID, DialogHeight, DialogPosition, DialogOpenAct, DeleteAct, EditDialogAct, TableID, ColumNum, TableAct, TableFunction, TablePageNum, TableOtherParam,InDialogTable,CustomAddAct,CustomEditAct){
	GetButtons(addButton,deleteButton);
	
	$("#" + addButton).click(function() {
    	var buttons = {
				"save": {
		            text: "Save",
		            id: saveButtonID
		        },
	        	"cancel": {
		            text: "Close",
		            id: closeButtonID,
		            click: function () {
		            	$(this).dialog("close");
		            }
		        }
		    };
    	GetDialog('add-edit-form' + dialogID, DialogHeight, "auto", buttons, DialogPosition);
        $.ajax({
            url: aJaxURL,
            data: "act=" + DialogOpenAct + "&" + CustomAddAct,
            success: function(data) {
            	$('#add-edit-form' + dialogID).html(data.page);
            	if(InDialogTable == 1){
                	GetTable();
                }
            }
        });
    });
	
	$("#" + deleteButton).click(function() {
    	var data = $(".check:checked").map(function () {
            return this.value;
        }).get();
    	

        for (var i = 0; i < data.length; i++) {
            $.ajax({
                url: aJaxURL,
                type: "POST",
                data: "act=" + DeleteAct + "&id=" + data[i],
                dataType: "json",
                success: function (data) {
	                    if (data.error != "") {
	                        alert(data.error);
	                    } else {
	                    	//alert(TablePageNum)
	                    	if(TablePageNum == '0'){
	                    		LoadTable(TableID, ColumNum, TableAct, TableFunction, '', TableOtherParam);
	                    	}else{
	                    		LoadTable(TableID, ColumNum, TableAct, TableFunction, TableOtherParam);
	                    	}
	                    }
                }
            });
        }
    });
    
    $(document).keydown(function(event){
        if(event.which=="17"){
        	cntrlIsPressed = true;
        }
        if(event.which=="46"){
        	$("#" + deleteButton).click();
        }
    });

    $(document).keyup(function(){
        cntrlIsPressed = false;
    });

    var cntrlIsPressed = false;
    
    $("#table_" + TableID + " tbody").on("click", "td:not(:last-child)", function () {
        var nTds = $($(this).siblings())[0];        
        var rID  = $(nTds).text();
        
        if(cntrlIsPressed)
        {
        	
        }else{
        	if(!$("#table_" + TableID + " INPUT[name='check_"+rID+"']").is(":checked")){
        		$("#table_" + TableID + " .check").prop("checked", false);
        	}
        }
        
        $("#table_" + TableID + " INPUT[name='check_"+rID+"']").prop("checked", !$("#table_" + TableID + " INPUT[name='check_"+rID+"']").is(":checked"));
        
    });
    
	$(document).on("dblclick", "#table_" + TableID + " tbody tr", function () {
        var nTds = $("td", this);
        var empty = $(nTds[0]).attr("class");
        
        if (empty != "dataTables_empty") {
            var rID = $(nTds[0]).text();
            
            $.ajax({
                url: aJaxURL,
                type: "POST",
                data: "act=" + EditDialogAct + "&id=" + rID + "&" + CustomEditAct,
                dataType: "json",
                success: function (data) {
                	var buttons = {
            				"save": {
            		            text: "Save",
            		            id: saveButtonID
            		        },
            	        	"cancel": {
            		            text: "Close",
            		            id: closeButtonID,
            		            click: function () {
            		            	$(this).dialog("close");
            		            }
            		        }
            		};
                	GetDialog('add-edit-form' + dialogID, DialogHeight, "auto", buttons, DialogPosition);
                    $('#add-edit-form' + dialogID).html(data.page);
                    if(InDialogTable == 1){
                    	GetTable();
                    }
                }
            });
        }
    });
	
	/* Check All */
    $("#" + Check).on("click", function () {
    	$("#table_" + TableID + " INPUT[type='checkbox']").prop("checked", $("#" + Check).is(":checked"));
    });
}

/**
* @summary     GetDialog
* @version     1.0.7
* @requested   Dialog Form Selector Name, Buttons Array
*/
function GetDialog(fname, width, height, buttons, position) {
    var defoult = {
        "save": {
            text: "Save",
            id: "save-dialog",
            click: function () {
            }
        },
        "cancel": {
            text: "Close",
            id: "cancel-dialog",
            click: function () {
                $(this).dialog("close");
            }
        }
    };
    var ok_defoult = "save-dialog";

    if (!empty(buttons)) {
        defoult = buttons;
    }

    $("#" + fname).dialog({
    	position: position,
    	left: 100,
        resizable: false,
        width: width,
        height: height,
        modal: true,
        stack: false,
        dialogClass: fname + "-class",
        buttons: defoult
    });
}

/**
* @summary     CloseDialog
* @version     1.0.1
* @requested   Dialog Form Selector Name
*/
function CloseDialog(form){
	$("#" + form).dialog("close");
	$("#" + form).html("");
}

/**
* @summary     GetTabs
* @version     1.0.2
* @requested   Tabs Selector Name
*/
function GetTabs(tbname) {
    var tabs = $("#" + tbname).tabs({
        collapsible: false
    });
}

/**
* @summary     GetSelectedTab
* @version     1.0.2
* @requested   Tabs Selector Name
*/
function GetSelectedTab(tbname) {
    var tabs = $("#" + tbname).tabs();
    var selected = tabs.tabs("option", "active"); // => 0

    return selected;
}

/**
* @summary     GetDate
* @version     1.0.1
* @requested   Input Selector Name
*/
function GetDate(iname) {
    $("#" + iname).datepicker({
    	changeMonth: true,
        changeYear: true
    });

    var date = $("#" + iname).val();

    $("#" + iname).datepicker("option", $.datepicker.regional['']);
    $("#" + iname).datepicker("option", "dateFormat", "yy-mm-dd");
    $("#" + iname).datepicker( "setDate", date );
}

function GetDate1(iname) {
    $("#" + iname).datepicker({
    	dateFormat: "yy-mm-dd",
    	changeMonth: true,
    	changeYear: true
    });

    var date = $("#" + iname).val();
}

function GetDate2(iname) {
    $("." + iname).datepicker({
    	dateFormat: "yy-mm-dd"
    });

    var date = $("." + iname).val();
}


/**
* @summary     GetDateTime
* @version     1.0.1
* @requested   Input Selector Name
*/
function GetDateTimes(iname) {
    $("#" + iname).datetimepicker({
    	dateFormat: "yy-mm-dd",
    		changeMonth: true,
        	changeYear: true
    });
}

function GetDateTimes1(iname) {
    $("." + iname).datetimepicker({
    	dateFormat: "yy-mm-dd"
    });
}
/**
* @summary     SeoY
* @version     1.0.1
* @requested   Input Selector Name,
*              Server Side seoyURL,
*              Action,
*              Custom Request,
*              MinLength
*/
function SeoY(iname, seoyURL, act, cdata, length) {
    var dlength = 1;

    //Register Button
    $(".combobox").button({
        icons: {
            primary: "ui-icon-triangle-1-s"
        }
    });

    if (!empty(length)) {
        length = dlength;
    }

    $.ajax({
        url: seoyURL,
        type: "POST",
        data: "act=" + act + "&" + cdata,
        dataType: "json",
        success: function (data) {
            $("#" + iname).autocomplete({
                source: data,
                minLength: length,
                autoFocus: true
            });
            $("#" + iname).autocomplete("widget").attr("id", iname + "-widget");
        }
    });
}

/*get calls dialog*/
function GetDialogCalls(fname, width, height, buttons) {
    var defoult = {
        "save": {
            text: "Save",
            id: "save-dialog",
            click: function () {
            }
        },
        "cancel": {
            text: "Close",
            id: "cancel-dialog",
            click: function () {
                $(this).dialog("close");
            }
        }
    };
    var ok_defoult = "save-dialog";

    if (!empty(buttons)) {
        defoult = buttons;
    }

    $("#" + fname).dialog({
    	position: "right top",
        resizable: false,
        width: width,
        height: height,
        modal: false,
        stack: false,
        dialogClass: fname + "-class",
        buttons: defoult
    });
}

/**
* @summary     AjaxSetup
* @version     1.0.4
*/
function AjaxSetup() {
    $.ajaxSetup({
        type: "POST",
        dataType: "json",
        beforeSend: function(){
            $("#loading").dialog({
                resizable: false,
                width: 160,
                height: 160,
                modal: true,
                stack: false,
                dialogClass: "loading-dialog",
                open: function(event,ui){
                    $(".ui-widget-overlay").addClass("loading-overlay");
                }
            });
        },
        complete: function(){
        	var $focused = "";
        	$("#loading").dialog({
	      		beforeClose: function( event, ui ) {
	      			$focused = $(":focus");
	      		}
        	});
        	$("#loading").dialog("close");
        	$("#loading").dialog("destroy");
        	$(".ui-widget-overlay").removeClass("loading-overlay");
        	$($focused).focus();
        },
	    error: function (jqXHR, exception) {
	        if (jqXHR.status === 0) {
	        	location.reload(true);
	        } else if (jqXHR.status == 404) {
	            window.location = "index.php";
	        } else if (jqXHR.status == 500) {
	        	location.reload(true);
	        } else if (exception === "parsererror") {
	        	alert('d');
	        	//location.reload(true);
	        } else if (exception === "timeout") {
	        	location.reload(true);
	        } else if (exception === "abort") {
	        	location.reload(true);
	        } else {
	        	location.reload(true);
	        }
	    }
    });
}

/**
* @summary     GetDateTime
* @version     1.0.1
*/
function GetDateTime(format) {
	var currentdate = new Date();
	var datetime;

	var d		= currentdate.getDate();
	var m		= currentdate.getMonth() + 1;
	var yy		= currentdate.getYear();

	var day		= (d < 10) ? '0' + d : d;
	var month	= (m < 10) ? '0' + m : m;
	var year	= (yy < 1000) ? yy + 1900 : yy;

	var h		= currentdate.getHours();
	var mm		= currentdate.getMinutes();
	var s		= currentdate.getSeconds();

	var hours	= (h < 10) ? '0' + h : h;
	var minutes = (mm < 10) ? '0' + mm : mm;
	var seconds = (s < 10) ? '0' + s : s;

	switch (format) {
		case 0:
			datetime = year + "-" + month  + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
			break;
		case 1:
			datetime = year + "-" + month  + "-" + day + "-" + hours + "-" + minutes + "-" + seconds;
			break;
		case 2:
			datetime = year + "-" + month  + "-" + day;
			break;
		default:
			datetime = "Null";
	}

    return datetime;
}

function ToPrice(price) {
	return parseFloat(price).toFixed(2);
}

/**
* @summary     GetAjaxData
* @version     1.0.1
* @requested   Object Array
*/
function GetAjaxData(data) {
    param = "";
    for (var key in data) {
        var value = data[key];
        if (typeof (value) != "undefined") {
            param += key + "=" + value + "&"
        }
    }

    return param;
}

function GetRootDIR(){
	var url = window.location.href;
	var path = url.substring(url.lastIndexOf('/')+1);
	var root = url.substring(0, url.length - path.length);

	return root;
}

function play(str){
	$('audio').each(function(){
	    this.pause(); // Stop playing
	    this.currentTime = 0; // Reset time
	});
	var buttons = {
        	"cancel": {
	            text: "Close",
	            id: "cancel-dialog",
	            click: function () {
	            	$('audio').each(function(){
	            	    this.pause(); // Stop playing
	            	    this.currentTime = 0; // Reset time
	            	}); 
	            	$(this).dialog("close");
	            }
	        }
	    };
	GetDialog("play_audio", 325, "auto", buttons);
	
	linkurl = '212.72.155.176';
	
	$('#play_audio audio source').attr('src','http://' + linkurl + ':8000/'+str);
	$('#play_audio audio').load();
}
