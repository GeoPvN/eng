<head>
<style type="text/css">
<?php                    		
if($_SESSION['USERID'] == 3 || $_SESSION['USERID'] == 1 ){
   
 
}else{
     echo '.dataTable_buttons{
            display:none;
        }';
}
?>
#phone_base_dialog{
	height: 550px !important;
}

#base tbody tr{
	width: 50% !important;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #408c99), color-stop(1, #599bb3));
	background:-moz-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-webkit-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-o-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-ms-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:linear-gradient(to bottom, #408c99 5%, #599bb3 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#408c99', endColorstr='#599bb3',GradientType=0);
	background-color:#408c99;
}
.myButton:active {
	position:relative;
	top:1px;
}
.hidden{
	display: none;
}

</style>
<script type="text/javascript">
		var aJaxURL		= "server-side/call/tasks/tasks_tab0.action.php";		//server side folder url
		var aJaxURL1	= "server-side/call/tasks/tasks_tab1.action.php";		//server side folder url
		var aJaxURL2	= "server-side/call/tasks/tasks_tab2.action.php";		//server side folder url
		var aJaxURL3	= "server-side/call/tasks/tasks_tab3.action.php";		//server side folder url
		var aJaxURL4	= "server-side/call/tasks/tasks_tab4.action.php";		//server side folder url
		var aJaxURL7	= "server-side/call/tasks/tasks_tab7.action.php";		//server side folder url
		var aJaxURL8	= "server-side/call/tasks/tasks_tab8.action.php";		//server side folder url
		var aJaxURL5	= "server-side/call/tasks/subtasks/tasks_tab1.action.php";		//server side folder url
		var aJaxURL6	= "server-side/call/tasks/subtasks/tasks_tab2.action.php";		//server side folder url
        var seoyURL		= "server-side/seoy/seoy.action.php";					//server side folder url
		var upJaxURL	= "server-side/upload/file.action.php";	
		var tName		= "example0";											//table name
		var tbName		= "tabs";												//tabs name
		var fName		= "add-edit-form";										//form name
		var file_name = '';
		var rand_file = '';

		$(document).ready(function () {     
			GetTabs(tbName);   	
			GetTable0();
			GetTable1();
			GetTable2();
			GetTable3();
			GetTable4();
			GetTable5();
			SetPrivateEvents("add_responsible_person", "check-all", "add-responsible-person");
			GetButtons("add_button","add_responsible_person");
		});

		$(document).on("tabsactivate", "#tabs", function() {
        	var tab = GetSelectedTab(tbName);
        	if (tab == 0) {
        		GetTable0();
        	}else if(tab == 1){
        		GetTable1();
            }else if(tab == 2){
            	GetTable2();
            }else if(tab == 3){
            	GetTable3();
            }else if(tab == 4){
            	GetTable5();
            }else if(tab == 5){
            	GetTable6();
            }else if(tab == 6){
            	GetTable7();
            }else if(tab == 7){
            	GetTable4();
            }
        });

		function GetTable0() {
            LoadTable0();
            $("#delete_but").button({
  	            
		     });
            SetEvents("add_button", "delete_but", "", "example0", fName, aJaxURL);
        }
        
		 function GetTable1() {
             LoadTable1();
             $("#delete_button_t").button({
  	            
  		     });
             SetEvents("", "delete_button_t", "check-all-my", "example1", "task_dialog", aJaxURL1);
         }
         
		 function GetTable2() {
             LoadTable2();
             SetEvents("", "", "", "example2", "task_dialog", aJaxURL1);
         }
         
		 function GetTable3() {
             LoadTable3();
             SetEvents("", "", "", "example3", "task_dialog", aJaxURL1);
         }

		 function GetTable4() {
             LoadTable7();
             SetEvents("", "", "", "all_task", "task_dialog", aJaxURL1);
         }

		 function GetTable5() {
             LoadTable8();
             SetEvents("", "", "", "disable_task", "task_dialog", aJaxURL1);
         }
		 function GetTable6() {
             LoadTable11(5);
             SetEvents("", "", "", "disable_task", "task_dialog", aJaxURL1);
         }
		 function GetTable7() {
             LoadTable11(6);
             SetEvents("", "", "", "disable_task", "task_dialog", aJaxURL1);
         }

		 function LoadTable11(status){			
				/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
				GetDataTableTest("status_task"+status, "server-side/call/tasks/tasks_tab9.action.php", "get_list&status="+status,11, "", 0, "", 9, "asc", "");
			}

		 function LoadTable0(){		
			 SetPrivateEvents("add_responsible_person", "check-all", "add-responsible-person");	
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			 GetDataTableTest("example0", aJaxURL, "get_list", 9, "", 0, "", 1, "asc", "");
		}
			
		function LoadTable1(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example1", aJaxURL1, "get_list", 11, "", 0, "", 9, "asc", "");
		}

		function LoadTable2(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example2", aJaxURL2, "get_list",11, "", 0, "", 9, "asc", "");
		}
		
		function LoadTable3(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example3", aJaxURL3, "get_list", 11, "", 0, "", 9, "asc", "");
		}
		
		function LoadTable4(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("example4", aJaxURL4, "get_list&id="+$("#id").val(), 12, "", 0, "", 1, "asc", "");
		}
		function LoadTable5(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("sub1", aJaxURL5, "get_list", 7, "", 0, "", 1, "asc", "");
		}
		function LoadTable6(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("sub2", aJaxURL6, "get_list", 7, "", 0, "", 1, "asc", "");
		}
		function LoadTable7(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("all_task", aJaxURL7, "get_list", 10, "", 0, "", 8, "asc", "");
		}
		function LoadTable8(){			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableTest("disable_task", aJaxURL8, "get_list", 10, "", 0, "", 8, "asc", "");
		}
		function LoadTable9(){		
			var scenar_name  =	$("#shabloni").val();	
			var task_id      =	$("#id").val();			
			GetButtons("add_button_product","delete_button_product");
			var total=[4];
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTableproduct("sub1", "server-side/call/outgoing/suboutgoing/outgoing_tab1.action.php", "get_list&scenar_name="+scenar_name+"&task_id="+task_id, 7, "", 0, "", 1, "asc", total);
			
			/* Edit Event */
		    $("#sub1 tbody").on("dblclick", "tr", function () {
		        var nTds = $("td", this);
		        var empty = $(nTds[0]).attr("class");

		        if (empty != "dataTables_empty") {
		            var rID = $(nTds[0]).text();
				
    				var buttons = {
    				        "save": {
    				            text: "შენახვა",
    				            id: "save_chosse_product"
    				        }, 
    			        	"cancel": {
    				            text: "დახურვა",
    				            id: "cancel-dialog",
    				            click: function () {
    				            	$(this).dialog("close");
    				            }
    				        }
    				    };
    			    GetDialog("add_product_chosse", 800, "auto", buttons);		
    			    $("#add_product_chosse").html('');    
    	        	$.ajax({
    	  	            url: "server-side/call/outgoing/add_chosse_product.php",
    	  	            type: "POST",
    	  	            data: "act=get_table&sale_detail_id="+rID,
    	  	            dataType: "json", 
    	  	            success: function (data) {    	  	            	
    						$("#add_product_chosse").html(data.page);
    						$('#production_name').chosen({ search_contains: true });
    						$(".add_product_chosse-class").css("position","fixed");
    						$("#production_name_chosen").css("position","fixed");
    						$("#production_name_chosen").css("z-index","99999");
    	  	            }	  	            
    	  	        });
		        }
		    });
		}

		//SeoYyy
		$(document.body).click(function (e) {
        	$("#send_to").autocomplete("close");
        });

		$(document).on("click", "#check-all-in", function () {
			if ($('#check-all-in').is(':checked')) {
				$( ".check" ).prop( "checked", true );
	    	}else{
	    		$( ".check" ).prop( "checked", false );
	    	}
		});
		
		$(document).on("click", "#save-printer", function () {
	       	 var data = $(".check:checked").map(function () {
	  	        return this.value;
	  	    }).get();
	  	    
	  	    var letters = [];
	  	    
	  	    for (var i = 0; i < data.length; i++) {
	  	    	letters.push(data[i]);        
	  	    }
	      	param = new Object();
	      	param.act	= "change_responsible_person";
	      	param.lt	= letters;
	  	    param.rp	= $("#responsible_person").val();
	
	  	    var link	=  GetAjaxData(param);
	  	    
	  	    if(param.rp == "0"){
	  		    alert("აირჩიეთ პასუხისმგებელი პირი!");
	  		}else if(param.ci == "0"){
	  		    alert("აირჩიეთ ავტომობილი");		
	  		}else{	    
	  	        $.ajax({
	  	            url: aJaxURL,
	  	            type: "POST",
	  	            data: link,
	  	            dataType: "json", 
	  	            success: function (data) {
	  	                if (typeof (data.error) != "undefined") {
	  	                    if (data.error != "") {
	  	                        alert(data.error);
	  	                    }else{
	  	                        $("#add-responsible-person").dialog("close");
	  	                        LoadTable0();
	  	                    }
	  	                }
	  	            }
	  	        });
	  		}
      });

        function LoadDialog(fName){
            //alert(form);
			switch(fName){
				case "add-edit-form":
					var buttons = {
						"save": {
				            text: "შენახვა",
				            id: "save-dialog"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        } 
				    };
					GetDialog("add-edit-form", 790, "auto", buttons);
					GetDateTimes("done_start_time");
					GetDateTimes("done_end_time");

					
					
					$(document).on("change", "#task_type_id",function(){
						var cat_id = $("#task_type_id").val();
						if(cat_id == 1 || cat_id == 2 || cat_id == 4){
							$("#additional").removeClass('hidden');
							$("#task_department_id").val(40);
						}else{
							$("#task_department_id").val(0);
						}
						
					});
					
					
					
				break;	
				case "add-edit-form1":
					var buttons = {
						"done": {
				            text: "დასრულება",
				            id: "done-dialog1"
				        }, 
						"save": {
				            text: "შენახვა",
				            id: "save-dialog1"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
					GetDialog("add-edit-form1", 1150, "auto", buttons);
					$(".done").button({
			            
				    });
					$(".next").button({
			            
				    });
					$(".back").button({
			            
				    });
					$("#add_button_product").button({
			            
				    });
					$("#add_button_gift").button({
					    
					});
					$("#complete").button({
					    
					});
					LoadTable5();
					LoadTable6();
					GetDateTimes("send_time");
				break;	
				case "task_dialog":
					var buttons = {
				        "save": {
				            text: "შენახვა",
				            id: "save_my_task",
				            click: function () {
								
				            	$.ajax({
							        url: aJaxURL1,
								    data: "act=save_my_task&id="+$("#id_my_task").val()+"&status_id="+$("#status_id").val()+"&problem_comment="+$("#problem_comment").val(),
							        success: function(data) {       
										if(typeof(data.error) != "undefined"){
											if(data.error != ""){
												alert(data.error);
											}else{
												LoadTable1();
												LoadTable2();
												LoadTable3();
												CloseDialog("task_dialog");
											}
										}
								    }
							    	});		            
				            }
				        },
						"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				                $(this).dialog("close");
				            }
				        }
				};
				GetDialog("task_dialog", 1154, "auto", buttons);
				$( ".download" ).button({
				      
				});
				
				break;
				case "add-edit-form2":
					var buttons = {
						"done": {
				            text: "დასრულება",
				            id: "done-dialog2"
				        }, 
				        "save": {
				            text: "შენახვა",
				            id: "save-dialog2"
				        }, 
			        	"cancel": {
				            text: "დახურვა",
				            id: "cancel-dialog",
				            click: function () {
				            	$(this).dialog("close");
				            }
				        }
				    };
					GetDialog("add-edit-form2", 1060, "auto", buttons);
			    break;
			}
			LoadTable4();
			var id = $("#incomming_id").val();
			var cat_id = $("#category_parent_id").val();
	
			if(id != '' && cat_id == 407){
				$("#additional").removeClass('hidden');
			}
	
			GetDateTimes("planned_end_date");
			
			$( ".calls" ).button({
			      icons: {
			        primary: " ui-icon-contact"
			      }
			});
			$("#choose_button").button({
	            
		    });
			$("#choose_base").button({
	            
		    });
			$("#add_button_pp").button({
	            
		    });
		   
		}

		function LoadDialog1(){
			var buttons = {
			        "save": {
			            text: "შენახვა",
			            id: "save-printer",
			            click: function () {
			            	Change_person();			            
			            }
			        },
					"cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			                $(this).dialog("close");
			            }
			        }
			};
			GetDialog("add-responsible-person", 295, "auto", buttons);
		}

		$(document).on("click", "#incomming_base", function () {
			$("#hidden_base").val('1');
			SetEvents("", "", "check-all-base", "base", "phone_base_dialog", aJaxURL);
			GetDataTableTest("base", aJaxURL, "get_list_base", 11, "", 0, "", 1, "asc", "");
			
            $('#back_1000_phone').addClass('dialog_hidden');
            $('#next_1000_phone').addClass('dialog_hidden');
            $('#mtvleli_phone').addClass('dialog_hidden');
            $('#back_1000_inc').removeClass('dialog_hidden');
            $('#next_1000_inc').removeClass('dialog_hidden');
            $('#mtvleli_inc').removeClass('dialog_hidden');
		});

		$(document).on("click", "#open_out", function () {
			var open_out_id = $("#open_out_id").val();
			$.ajax({
		        url: "server-side/call/outgoing/outgoing_tab0.action.php",
			    data: "act=get_edit_page&id="+open_out_id,
		        success: function(data) {       
					if(typeof(data.error) != "undefined"){
						
							var buttons = {
							        "save": {
							            text: "შენახვა",
							            id: "save_my_out",
							            click: function () {							            	
								     			   
							        			param 				= new Object();
							         			param.act			= "done_outgoing";
							         			param.get_prod 		= '';
							         			param.get_gift 		= '';
							        		    	
							         			param.id					= $("#id").val();
							         			if($("#task_type_id_seller").val() == 1){
							         			    param.hello_quest			= $("input[name='hello_quest1']:checked").val();
							         			}else{
							         				param.hello_quest			= $("input[name='hello_quest']:checked").val();
							         			}
							        	    	param.hello_comment			= $("#hello_comment").val();
							        	    	param.hello_comment1		= $("#hello_comment1").val();
							        	    	param.info_quest			= $("input[name='info_quest']:checked").val();
							        			param.info_comment			= $("#info_comment").val();
							        			param.result_quest			= $("input[name='result_quest']:checked").val();
							        	    	param.result_comment		= $("#result_comment").val();
							        			param.payment_quest			= $("input[name='payment_quest']:checked").val();
							        			param.payment_comment		= $("#payment_comment").val();
							        			param.send_date				= $("#send_date").val();

							        			param.preface_name			= $("#preface_name").val();
							        			param.preface_quest			= $("input[name='preface_quest']:checked").val();
							        			param.d1					= $("input[name='d1']:checked").val();
							        			param.d2					= $("input[name='d2']:checked").val();
							        			param.d3					= $("input[name='d3']:checked").val();
							        			param.d4					= $("input[name='d4']:checked").val();
							        			param.d5					= $("input[name='d5']:checked").val();
							        			param.d6					= $("input[name='d6']:checked").val();
							        			param.d7					= $("input[name='d7']:checked").val();
							        			param.d8					= $("input[name='d8']:checked").val();
							        			param.d9					= $("input[name='d9']:checked").val();
							        			param.d10					= $("input[name='d10']:checked").val();
							        			param.d11					= $("input[name='d11']:checked").val();
							        			param.d12					= $("input[name='d12']:checked").val();
							        			param.q1					= $("input[name='q1']:checked").val();
							        			param.b1					= $("#biblus_quest1").val();
							        			param.b2					= $("#biblus_quest2").val();
							        			param.result_quest1		    = $("input[name='result_quest1']:checked").val();
							        			param.result_comment1		= $("#result_comment1").val();
							        			
							        			

							        			param.call_content			= $("#call_content").val();
							        			param.status				= $("#status").val();
							        			
							        			// person info
							        			param.phone					= $("#phone").val();
							        			param.phone1				= $("#phone1").val();
							        			param.person_n				= $("#person_n").val();
							        			param.first_name			= $("#first_name").val();
							        			param.mail					= $("#mail").val();
							        			param.city_id				= $("#city_id").val();
							        			param.b_day					= $("#b_day").val();
							        			param.addres				= $("#addres").val();
							        			param.age					= $("#age").val();
							        			param.sex					= $("#sex").val();
							        			param.profession			= $("#profession").val();
							        			param.interes				= $("#interes").val();

							        			// Task Formireba
							        			param.set_task_department_id	= $("#set_task_department_id").val();
							        	    	param.set_persons_id			= $("#set_persons_id").val();
							        	    	param.set_priority_id			= $("#set_priority_id").val();
							        			param.set_start_time			= $("#set_start_time").val();
							        			param.set_done_time				= $("#set_done_time").val();
							        			param.set_body					= $("#set_body").val();
							        			param.task_type_id_seller		= $("#task_type_id_seller").val();
							        			param.set_shabloni				= $("#shabloni").val();

							        			if(param.task_type_id_seller == 1){
							        				if ($('input[name=result_quest]').is(':checked')) {
							        					if($('#sub1 tbody').text().length != 21 && $('input[name=result_quest]:checked').val() == 1){
							        						
							        			            my_hint = 1;
							        					}else{
							        						if($('#sub1 tbody').text().length != 21 && $('input[name=result_quest]:checked').val() != 1){
							        							my_hint = 2;
							        						}else{
							        							if($('#sub1 tbody').text().length == 21 && $('input[name=result_quest]:checked').val() == 1){
							        							    my_hint = 3;
							        							}else{
							        								my_hint = 1;
							        							}
							        						}
							        					}
							        				}else{
							        					my_hint = 0;
							        				}
							        			}else{
							        				my_hint = 1;
							        			}
							        			
							        			if(my_hint == 1){
							         	    	$.ajax({
							         		        url: "server-side/call/outgoing/outgoing_tab1.action.php",
							         			    data: param,
							         		        success: function(data) {       
							         					if(typeof(data.error) != "undefined"){
							         						if(data.error != ""){
							         							alert(data.error);
							         						}else{
							         							CloseDialog("add_task");
							         							$('#yesnoclose').dialog("close");
							         						}
							         					}
							         		    	}
							         		   });
							        	    }else if(my_hint == 2){
							        	    	alert('თქვენ მონიშნული გაქვთ პროდუქტები. ან მონიშნეთ შედეგი "დადებითი" ან გააუქმეთ პროდუქტები!');
							        	    }else if(my_hint == 3){
							        	    	alert('თქვენ არ გაქვთ არჩეული პროდუქტი. ან შედეგი შეცვალეთ ან აირჩიეთ პროდუქტი!');
							        	    }else{
							        	    	alert('გთხოვთ, მიუთითოთ შედეგი!');
							        	    }
							        		
							            }
							        },"cancel": {
							            text: "დახურვა",
							            id: "cancel-dialog",
							            click: function () {
							                $(this).dialog("close");
							            }
							        }
							};
							$(".download").button({
					            
						    });
							$("#add_task").html(data.page);
							GetDialog("add_task", 1150, "auto", buttons);
							LoadTable9();
						
					}
			    }
		    });
		});

		$(document).on("click", "#open_inc", function () {
			var open_inc_id = $("#open_inc_id").val();
			$.ajax({
		        url: "server-side/call/incomming.action.php",
			    data: "act=get_edit_page&id="+open_inc_id,
		        success: function(data) {       
					if(typeof(data.error) != "undefined"){
						
							var buttons = {							        
									"cancel": {
							            text: "დახურვა",
							            id: "cancel-dialog",
							            click: function () {
							                $(this).dialog("close");
							            }
							        }
							};
							
							$("#add_task").html(data.page);
							GetDialog("add_task", 1200, "auto", buttons);
							$(".download").button({
					            
						    });
							$("#choose_button").button({
					            
						    });
						    $("#button_calls").button({
					            
						    });
						    $( "#save-dialog" ).remove();
					}
			    }
		    });
		});
		
		$(document).on("click", "#phone_base", function () {
			$("#hidden_base").val('2');
			SetEvents("", "", "check-all-base", "base", "phone_base_dialog", aJaxURL);
			GetDataTableTest("base", aJaxURL, "get_list_base_phone", 11, "", 0, 0, 1, "asc", "");
			$('#back_1000_inc').addClass('dialog_hidden');
            $('#next_1000_inc').addClass('dialog_hidden');
            $('#mtvleli_inc').addClass('dialog_hidden');
			$('#back_1000_phone').removeClass('dialog_hidden');
            $('#next_1000_phone').removeClass('dialog_hidden');
            $('#mtvleli_phone').removeClass('dialog_hidden');
            
		});
		
		function seller(id){
			if(id == '0'){
				$('#seller-0').removeClass('dialog_hidden');
	            $('#0').addClass('seller_select');
	            $('#seller-1').addClass('dialog_hidden');
	            $('#seller-2').addClass('dialog_hidden');
	            $('#1').removeClass('seller_select');
	            $('#2').removeClass('seller_select');
			}else if(id == '1'){
				$('#seller-1').removeClass('dialog_hidden');
	            $('#1').addClass('seller_select');
	            $('#seller-0').addClass('dialog_hidden');
	            $('#seller-2').addClass('dialog_hidden');
	            $('#0').removeClass('seller_select');
	            $('#2').removeClass('seller_select');
			}else if(id == '2'){
				$('#seller-2').removeClass('dialog_hidden');
	            $('#2').addClass('seller_select');
	            $('#seller-1').addClass('dialog_hidden');
	            $('#seller-0').addClass('dialog_hidden');
	            $('#1').removeClass('seller_select');
	            $('#0').removeClass('seller_select');
			}
		}
		function research(id){
			if(id == 'r0'){
				$('#research-0').removeClass('dialog_hidden');
	            $('#r0').addClass('seller_select');
	            $('#research-1').addClass('dialog_hidden');
	            $('#research-2').addClass('dialog_hidden');
	            $('#r1').removeClass('seller_select');
	            $('#r2').removeClass('seller_select');
			}else if(id == 'r1'){
				$('#research-1').removeClass('dialog_hidden');
	            $('#r1').addClass('seller_select');
	            $('#research-0').addClass('dialog_hidden');
	            $('#research-2').addClass('dialog_hidden');
	            $('#r0').removeClass('seller_select');
	            $('#r2').removeClass('seller_select');
			}else if(id == 'r2'){
				$('#research-2').removeClass('dialog_hidden');
	            $('#r2').addClass('seller_select');
	            $('#research-1').addClass('dialog_hidden');
	            $('#research-0').addClass('dialog_hidden');
	            $('#r1').removeClass('seller_select');
	            $('#r0').removeClass('seller_select');
			}
		}

		/* Disable Event */
        $(document).on("click", "#delete_button_product", function () {
        	var buttons = {
					"save": {
			            text: "კი",
			            id: "hint_yes",
		            	click: function () {
		            		var data = $(".check_p:checked").map(function () { //Get Checked checkbox array
		                        return this.value;
		                    }).get();

		                    for (var i = 0; i < data.length; i++) {
		                        $.ajax({
		                            url: "server-side/call/outgoing/suboutgoing/outgoing_tab1.action.php",
		                            type: "POST",
		                            data: "act=disable&id=" + data[i],
		                            dataType: "json",
		                            success: function (data) {
		                                if (data.error != "") {
		                                    alert(data.error);
		                                } else {
		                                    $("#check-all_p").attr("checked", false);
		                                }
		                            }
		                        });
		                    }
		                    LoadTable9();
			            	$(this).dialog("close");
			            }
			        }, 
		        	"cancel": {
			            text: "არა",
			            id: "hint_no",
			            click: function () {
			            	$(this).dialog("close");
			            }
			        } 
			    };
			GetDialog("yesno", 350, "auto", buttons);            
           
        });
        function changemypr(raodenoba){
        	raod = $("#sub3_qu"+raodenoba).val();
        	fasi = $("#sub3_rpr"+raodenoba).text();
        	$("#sub3_pr"+raodenoba).text((raod*fasi).toFixed(2));
        	$("#all_num"+raodenoba).val(raod);
        }

        $(document).on("click", "#add_button_product", function () {
        	
        	var buttons = {
			        "save": {
			            text: "შენახვა",
			            id: "save_chosse_product"
			        }, 
		        	"cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			            	$(this).dialog("close");
			            }
			        }
			    };
		    GetDialog("add_product_chosse", 800, "550", buttons);
		    $("#add_product_chosse").html('');
        	$.ajax({
  	            url: "server-side/call/outgoing/add_chosse_product.php",
  	            type: "POST",
  	            data: "act=get_table",
  	            dataType: "json", 
  	            success: function (data) {  	            	
					$("#add_product_chosse").html(data.page);
					$('#production_name').chosen({ search_contains: true });
					$(".add_product_chosse-class").css("position","fixed");
					$("#production_name_chosen").css("position","fixed");
					$("#production_name_chosen").css("z-index","9999999");
					$("#production_name_chosen").css("width","300px");
					task_id = $("#id").val();
		        	GetDataTableproduct("sub3", "server-side/call/outgoing/suboutgoing/outgoing_tab1.action.php", "all_products&task_id="+task_id, 7, "", 0, "", 1, "asc", "");
  	            }
  	        });
        	
        });

        $(document).on("click", "#save_chosse_product", function () {
        	var product_id 		= $("#production_name").val();
        	var porod_count		= $("#porod_count").val();
        	var task_scenar_id  = $("#id").val();
        	var sale_detail_id  = $("#sale_detail_id").val();
        	if(sale_detail_id == ''){
            	if($("#clickme1").val() == '' && $("#clickme2").val() == '' && $("#clickme3").val() == '' && $("#clickme4").val() == '' && $("#clickme5").val() == '' && $("#clickme6").val() == '' && $("#clickme7").val() == ''){
            	var data = $(".check_all_p:checked").map(function () { //Get Checked checkbox array
    				 par=$(this).parent();
    			     td= $(par[0]);
    			     td1=$(td[0].children[1]).val();
    			     prooood = this.value;
    	        
            	$.ajax({
      	            url: "server-side/call/outgoing/add_chosse_product.php",
      	            type: "POST",
      	            data: "act=add_product&product_id="+prooood+"&porod_count="+td1+"&task_scenar_id="+task_scenar_id+"&sale_detail_id="+sale_detail_id,
      	            dataType: "json", 
      	            success: function (data) {
      	            	$("#add_product_chosse").dialog("close");
      	            	LoadTable9();
      	            	if(data.hint != ''){
      	  	            	alert(data.hint);
      	            	}
      	            }
      	        });
            	}).get();
            	}else{
                	alert('გთხოვთ ფილტრი გაასუფთავოთ!');
            	}
        	}else{
        		$.ajax({
      	            url: "server-side/call/outgoing/add_chosse_product.php",
      	            type: "POST",
      	            data: "act=add_product&product_id="+product_id+"&porod_count="+porod_count+"&task_scenar_id="+task_scenar_id+"&sale_detail_id="+sale_detail_id,
      	            dataType: "json", 
      	            success: function (data) {
      	            	$("#add_product_chosse").dialog("close");
      	            	LoadTable9();
      	            	if(data.hint != ''){
      	  	            	alert(data.hint);
      	            	}
      	            }
        		});
        	}
        });
		
		 $(document).on("click", "#add_button_pp", function () {
			 param 			= new Object();
			 param.act		= "get_task";
			 $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {       
						if(typeof(data.error) != "undefined"){
							if(data.error != ""){
								alert(data.error);
							}else{
								var buttons = {
								        "save": {
								            text: "შენახვა",
								            id: "save-task",
								            click: function () {
								            	add_task();			            
								            }
								        },
										"cancel": {
								            text: "დახურვა",
								            id: "cancel-dialog",
								            click: function () {
								                $(this).dialog("close");
								            }
								        }
								};
								$("#add_task").html(data.page);
								GetDialog("add_task", 400, "auto", buttons);
								
							}
						}
				    }
			    });

		 });
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
	    	$('#save-dialog').attr('disabled','disabled');
			param 			= new Object();
			param.act			= "save_outgoing";
			
			param.cur_date				= $("#cur_date").val();
	    	param.done_start_time		= $("#done_start_time").val();
	    	param.done_end_time			= $("#done_end_time").val();
			param.task_type_id			= $("#task_type_id").val();
			param.template_id			= $("#template_id").val();
			param.persons_id			= $("#persons_id").val();
			param.task_department_id	= $("#task_department_id").val();
			param.task_comment			= $("#task_comment").val();
			param.problem_comment		= $("#problem_comment").val();
			param.priority_id			= $("#priority_id").val();

			if(param.task_type_id < 3){
		 		if(param.template_id == 0){
			 		alert('ამოირჩიეთ სცენარი');
		 		}else{
			 		$.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {       
						if(typeof(data.error) != "undefined"){
							if(data.error != ""){
								alert(data.error);
							}else{
								LoadTable0();
								CloseDialog("add-edit-form");
							}
						}
				    }
			    	});
		 		}
			}else{
				$.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {       
						if(typeof(data.error) != "undefined"){
							if(data.error != ""){
								alert(data.error);
							}else{
								LoadTable0();
								CloseDialog("add-edit-form");
							}
						}
				    }
			    	});
			}
		});

		
	    $(document).on("click", "#choose_base", function () {
	    	param 				= new Object();
 			param.act			= "phone_base_dialog";
 			
	    	$.ajax({
		        url: aJaxURL,
			    data: param,
		        success: function(data) {       
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}else{
							$("#phone_base_dialog").html(data.page);
							var buttons = {
							        
									"cancel": {
							            text: "დახურვა",
							            id: "cancel-dialog",
							            click: function () {
							                $(this).dialog("close");
							                LoadTable4();
							            }
							        }
							};
								$("#incomming_base").button({
								    
								});
								
								$("#add_formireba").button({
								    
								});
								$("#add_formireba_done").button({
								    
								});
								
								$("#phone_base").button({
								    
								});
								
								$("#back_1000_phone").button({
																    
								});
								$("#next_1000_phone").button({
								    
								});
								$("#back_1000_inc").button({
								    
								});
								$("#next_1000_inc").button({
								    
								});
							GetDialog("phone_base_dialog", 1260, "auto", buttons);
							SetEvents("", "", "check-all-base", "base", "phone_base_dialog", aJaxURL);
							GetDataTableTest("base", aJaxURL, "get_list_base_phone", 11, "", 0, "", 1, "asc", "");
						}
					}
			    }
			});
	    	
	    });

	    $(document).on("click", "#next_1000_inc", function () {
			var next = $('#mtvleli_inc').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli_inc').val(next_ch);
			GetDataTableTest("base", aJaxURL, "get_list_base&pager="+next_ch, 11, "", 0, "", 1, "desc");
		});
		$(document).on("click", "#back_1000_inc", function () {
			var back = $('#mtvleli_inc').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli_inc').val(back_ch);
			GetDataTableTest("base", aJaxURL, "get_list_base&pager="+back_ch, 11, "", 0, "", 1, "desc");
		});

		$(document).on("click", "#next_1000_phone", function () {
			var next = $('#mtvleli_phone').val();
			var next_ch = parseInt(next)+1;
			$('#mtvleli_phone').val(next_ch);
			GetDataTableTest("base", aJaxURL, "get_list_base_phone&pager="+next_ch, 11, "", 0, "", 1, "desc");
		});
		$(document).on("click", "#back_1000_phone", function () {
			var back = $('#mtvleli_phone').val();
			if(back != 0){
			var back_ch = parseInt(back)-1;
			}else{
				back_ch = 0;
			}
			$('#mtvleli_phone').val(back_ch);
			GetDataTableTest("base", aJaxURL, "get_list_base_phone&pager="+back_ch, 11, "", 0, "", 1, "desc");
		});

	    $(document).on("click", "#add_formireba", function () {
		
 	    	$.ajax({
 			        url: aJaxURL,
 				    data: "act=dialog_formireba",
 			        success: function(data) {       
 						if(typeof(data.error) != "undefined"){
 							if(data.error != ""){
 								alert(data.error);
 							}else{
 								var buttons = {
 										"save": {
 								            text: "შენახვა",
 								            id: "save_formireba",
 								            click: function () {
 	 								            var f_number 	= $('#f_number').val();
 	 	 								        var f_note 		= $('#f_note').val();
 	 	 	 								    var f_sorce 	= $('#f_sorce').val();
 	 	 	 	 								var f_name      = $('#f_name').val();
 	 	 	 								    var id			= $('#id').val();
 								            	$.ajax({
 								 			        url: aJaxURL,
 								 				    data: "act=save_formireba&f_note="+f_note+"&f_number="+f_number+"&f_sorce="+f_sorce+"&f_name="+f_name+"&id="+id,
 								 			        success: function(data) {       
 								 						if(typeof(data.error) != "undefined"){
 								 							if(data.error != ""){
 								 								alert(data.error);
 								 							}else{
 								 								$("#add_formireba_dialog").dialog("close");
 								 								LoadTable4();
 								 							}
 														}
 								 				    }
 								 				});
 								                
 								            }
 								        },
 										"cancel": {
 								            text: "დახურვა",
 								            id: "cancel-dialog",
 								            click: function () {
 								                $(this).dialog("close");
 								            }
 								        }
 								};
 								GetDialog("add_formireba_dialog", 300, "auto", buttons);
 								$("#add_formireba_dialog").html(data.page)
 							}
						}
 				    }
 			});
 	        
 		});

	    $(document).on("click", "#add_formireba_done", function () {
			
 	    	$.ajax({
 			        url: aJaxURL,
 				    data: "act=dialog_formireba_done",
 			        success: function(data) {       
 						if(typeof(data.error) != "undefined"){
 							if(data.error != ""){
 								alert(data.error);
 							}else{
 								var buttons = {
 										"save": {
 								            text: "შენახვა",
 								            id: "save_formireba_done",
 								            click: function () {
 	 								            var f_number 	= $('#f_m_number').val();
 	 	 								        var f_note 	    = $('#f_m_note').val();
 	 	 	 								    var f_sorce 	= $('#f_m_sorce').val();
 	 	 	 	 								var f_name      = $('#f_m_name').val();
 	 	 	 								    var id			= $('#id').val();
 								            	$.ajax({
 								 			        url: aJaxURL,
 								 				    data: "act=save_formireba_done&f_note="+f_note+"&f_number="+f_number+"&f_sorce="+f_sorce+"&f_name="+f_name+"&id="+id,
 								 			        success: function(data) {       
 								 						if(typeof(data.error) != "undefined"){
 								 							if(data.error != ""){
 								 								alert(data.error);
 								 							}else{
 								 								$("#add_formireba_done_dialog").dialog("close");
 								 								LoadTable4();
 								 							}
 														}
 								 				    }
 								 				});
 								                
 								            }
 								        },
 										"cancel": {
 								            text: "დახურვა",
 								            id: "cancel-dialog",
 								            click: function () {
 								                $(this).dialog("close");
 								            }
 								        }
 								};
 								GetDialog("add_formireba_done_dialog", 300, "auto", buttons);
 								$("#add_formireba_done_dialog").html(data.page)
 							}
						}
 				    }
 			});
 	        
 		});
	    

///

	    $(document).on("change", "#task_type_id",function(){

			if(this.value == 1 || this.value == 2 || this.value == 4){
				$("#additional").removeClass('hidden');
			}else{
				$("#additional").addClass('hidden');
			}
        });
///
	    
	    
	 function SetPrivateEvents(add,check,formName){
		$(document).on("click", "#" + add, function () {    
	        $.ajax({
	            url: aJaxURL,
	            type: "POST",
	            data: "act=get_responsible_person_add_page",
	            dataType: "json",
	            success: function (data) {
	                if (typeof (data.error) != "undefined") {
	                    if (data.error != "") {
	                        alert(data.error);
	                    }else{
	                        $("#" + formName).html(data.page);
	                        if ($.isFunction(window.LoadDialog)){
	                            //execute it
	                        	LoadDialog1();
	                        }
	                    }
	                }
	            }
	        });
	    });
		
	    $(document).on("click", "#" + check, function () {
	    	$("#" + tName + " INPUT[type='checkbox']").prop("checked", $("#" + check).is(":checked"));
	    });	
	}

	function add_task(formName){
    	param = new Object();
    	param.act			= "save_task";
    	param.id			= $("#id").val();
	    param.p_phone			= $("#p_phone").val();
	    param.p_person_n		= $("#p_person_n").val();
	    param.p_first_name		= $("#p_first_name").val();
	    param.p_mail			= $("#p_mail").val();
	    param.p_last_name		= $("#p_last_name").val();
	    param.p_person_status	= $("#p_person_status").val();
	    param.p_addres			= $("#p_addres").val();
	    param.p_b_day			= $("#p_b_day").val();
	    param.p_city_id			= $("#p_city_id").val();
	    param.p_family_id		= $("#p_family_id").val();
	    param.p_profesion		= $("#p_profesion").val();
	        
	        $.ajax({
	            url: aJaxURL,
	            type: "POST",
	            data: param,
	            dataType: "json", 
	            success: function (data) {
	                if (typeof (data.error) != "undefined") {
	                    if (data.error != "") {
	                        alert(data.error);
	                    }else{
	                        $("#add_task").dialog("close");
	                        LoadTable4();
	                    }
	                }
	            }
	        });	    		
	}

	$(document).on("click", ".download", function () {
        var link = $(this).attr("str");
        link = "http://109.234.117.182:8181/records/" + link;

        var newWin = window.open(link, "JSSite", "width=420,height=230,resizable=yes,scrollbars=yes,status=yes");
        newWin.focus();
    });

	$(document).on("click", ".download1", function () {
        var link = $(this).attr("str");
        link = "http://109.234.117.182:8181/records/" + link;

        var newWin = window.open(link, "JSSite", "width=420,height=230,resizable=yes,scrollbars=yes,status=yes");
        newWin.focus();
    });
    </script>
</head>

<body>
<div id="dvLoading" class="dialog_hidden"></div>
<div id="tabs" style="width: 99%; margin: 0 auto; min-height: 768px; margin-top: 25px;">
		<ul>
		    <?php
		    if($_SESSION['USERID'] != 30){
			    echo '<li><a href="#tab-0">დავალების ფორმირება</a></li>';
		    }else{
		        $hide = 'style="display:none;"';
		    }
			if($_SESSION['USERID'] == 1  || $_SESSION['USERID'] == 3){
				echo '<li><a href="#tab-1">გადაცემულია გასარკვევად</a></li>';
			}else{
				echo '<li><a href="#tab-1">ჩემი დავალებები</a></li>';
			}
			?>
			<li><a href="#tab-2">გარკვევის პროცესშია</a></li>
			<li><a href="#tab-3">მოგვარებულია</a></li>
			<li><a href="#tab-4">გაუქმებულია</a></li>
			<li><a href="#tab-5">ქოლცენტრის დაზუსტებულია</a></li>
			<li><a href="#tab-6">ელვა.გე-ს დაზუსტებულია</a></li>
			<?php
			if($_SESSION['USERID'] != 30){
			    echo '<li><a href="#tab-7">ყველა დავალება</a></li>';
			}
			?>
		</ul>
<div id="tab-0" <?php echo $hide;?> >
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">დავალების ფორმირება</h2>
		            	<div id="button_area">
		            		<button id="add_button">დამატება</button>
		            		<?php
                    		include '../../includes/classes/core.php';
                    		if($_SESSION['USERID'] == 3 || $_SESSION['USERID'] == 1 ){
                    		  echo '<button id="delete_but">წაშლა</button>';
                    		}
                    		?>
		            		
	        				<button id="add_responsible_person">პ. პირის აქტივაცია</button>	        				
	        			</div>
		                <table class="display" id="example0">
		                   <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:25px;;">#</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input type="checkbox" name="check-all" id="check-all-in"/>
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>

  <div id="tab-1">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გადაცემულია გასარკვევად</h2>
		            	<div id="button_area" >
		            	    <?php
                    		include '../../includes/classes/core.php';
                    		if($_SESSION['USERID'] == 3 || $_SESSION['USERID'] == 1 ){
                    		  echo '<button id="delete_button_t">წაშლა</button>';
                    		}
                    		?>
                			
                		</div>
		                <table class="display" id="example1">
		                   <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">დამფორმირებელი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
									<th style="width:6%;">#</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input type="checkbox" name="check-all" id="check-all-my"/>
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
    <div id="tab-2">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გარკვევის პროცესშია</h2>
		                <table class="display" id="example2">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">დამფორმირებელი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
  <div id="tab-3">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">მოგვარებულია</h2>
		                <table class="display" id="example3">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">დამფორმირებელი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
		 <div id="tab-4">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">გაუქმებულია</h2>
		                <table class="display" id="disable_task">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
		 <div id="tab-5">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">ქოლცენტრის დაზუსტებულია</h2>
		                <table class="display" id="status_task5">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">დამფორმირებელი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
		 <div id="tab-6">
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">ელვა.გე-ს დაზუსტებულია</h2>
		                <table class="display" id="status_task6">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">დამფორმირებელი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
		 <div id="tab-7" <?php echo $hide;?>>
		    <div id="dt_example" class="ex_highlight_row">
		        <div id="container" style="width: 100%;">        	
		            <div id="dynamic">
		            	<h2 align="center">ყველა დავალება</h2>
		                <table class="display" id="all_task">
		                    <thead>
								<tr id="datatable_header">
		                            <th>ID</th>
									<th style="width:6%;">ID</th>
									<th style="width:19%;">შექმნის თარიღი</th>
									<th style="width:19%;">დასაწისი</th>
									<th style="width:19%;">დასასრული</th>
									<th style="width:19%;">დავალების ტიპი</th>
									<th style="width:19%;">დეპარტამენტი</th>
									<th style="width:19%;">პასუხისმგებელი პირი</th>
									<th style="width:19%;">პრიორიტეტი</th>
									<th style="width:19%;">სტატუსი</th>
								</tr>
							</thead>
							<thead>
								<tr class="search_header">
									<th class="colum_hidden">
                            			<input type="text" name="search_id" value="" class="search_init" style="width: 10px"/>
                            		</th>
									<th>
										<input style="width:30px;" type="text" name="search_overhead" value="" class="search_init" />
									</th>
									<th>
										<input style="width:85px;" type="text" name="search_partner" value="ფილტრი" class="search_init" />
									</th>
									
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_op_date" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									<th>
										<input style="width:100px;" type="text" name="search_sum_cost" value="ფილტრი" class="search_init" />
									</th>
									
								</tr>
							</thead>
		                </table>
		                </table>
		            </div>
		            <div class="spacer">
		            </div>
		        </div>
		    </div>
		 </div>
</div>

	<!-- jQuery Dialog -->
    <div  id="add-edit-form" class="form-dialog" title="დავალება">
	</div>
	
	<!-- jQuery Dialog -->
	<div id="last_calls" title="ბოლო ზარები">
	</div>
	<div id="add_task" class="form-dialog" title="დავალების დამატება">
	<!-- aJax -->
	</div>
	<!-- jQuery Dialog -->
	<div id="add-edit-form2" class="form-dialog" title="გამავალი ზარი">
	<!-- aJax -->
	</div>
	
	<div id="add-responsible-person" class="form-dialog" title="პასუხისმგებელი პირი">
	<!-- aJax -->
	</div>
	<div id="phone_base_dialog" class="form-dialog" title="სატელეფონო ბაზა">
	<!-- aJax -->
	</div>
	<div id="task_dialog" class="form-dialog" title="დავალება">
	<!-- aJax -->
	</div>
	<div id="add_formireba_dialog" class="form-dialog" title="ფორმირება">
	<!-- aJax -->
	</div>
	<div id="add_formireba_done_dialog" class="form-dialog" title="ფორმირება">
	<!-- aJax -->
	</div>
	<div id="add_product" class="form-dialog" title="დავალების დამატება">
    <!-- aJax -->
    </div>
    <div id="add_product_chosse" class="form-dialog" title="პროდუქტის დამატება">
    <!-- aJax -->
    </div>
	<div id="yesno" class="form-dialog" title="მინიშნება">
    <div id="dialog-form">
    <fieldset>
         დარწმუნებული ხართ რომ გსურთ პროდუქტის გაუქმება?
    <input type="hidden" value="" id="p">
    <input type="hidden" value="" id="pp">
    </fieldset>
    </div>
    </div>
    <div id='yesnoclose' class="form-dialog">
        <div id="dialog-form">
            <fieldset>
                        გსურთ თუ არა ცვლილებების შენახვა?
            </fieldset>
        </div>
    </div>
</body>
	