<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!--
            Using AJAX To Data Bind A Child Drop Down List Based On The Selected Option Of A Parent Select Control
            Copyright (C) 2009 Doug Vanderweide
            
            This program is free software: you can redistribute it and/or modify
            it under the terms of the GNU General Public License as published by
            the Free Software Foundation, either version 3 of the License, or
            (at your option) any later version.
        
            This program is distributed in the hope that it will be useful,
            but WITHOUT ANY WARRANTY; without even the implied warranty of
            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
            GNU General Public License for more details.
        
            You should have received a copy of the GNU General Public License
            along with this program.  If not, see <http://www.gnu.org/licenses/>.
        -->	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Using AJAX To Data Bind A Child Drop Down List Based On The Selected Option Of A Parent Select Control</title>
        <link href="../demo.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="jquery-1.3.2.min.js"></script>
        <script type="text/javascript">
			function bindCity() {
				//disable child select list
				$("#city").attr("disabled", true);
				//clear child select list's options
				$("#city").html('');
				
				//querystring value is selected value of parent drop down list
				var qs = $("#state").val();
				//if user selected a separator, show error
				if(qs == '') {
					alert('You cannot select this option. Please make a different selection.');
				}
				else {
					//show message indicating we're getting new values
					$("#city").append(new Option('Getting city list ...'));
					//declare options array and populate
					var cityOptions = new Array();
					$.get("citylist.php?statecode=" + qs, function(data) {
							eval(data);
							if(cityOptions.length > 0) {
								addOptions(cityOptions);
							}
						}
					);
				}
			}
			
			function addOptions(cl) {
				//enable child select and clear current child options
				$("#city").removeAttr("disabled");
				$("#city").html('');
				//repopulate child list with array from helper page
				var city = document.getElementById('city');
				for(var i = 0; i < cl.length; i++) {
					city.options[i] = new Option(cl[i].text, cl[i].value);
				}
			}
		</script>
    </head>
    <body>
        <h1>Using AJAX To Data Bind A Child Drop Down List Based On The Selected Option Of A Parent Select Control</h1>
        <form id="form1" name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
            <label>
                Select a state:&nbsp;
                <select id="state" name="state" onchange="bindCity()">
					<?php
                        if(!$link = mysql_connect('localhost', 'username', 'password')) {
                            echo "<option>Cannot connect to database server</option>\n";
                        }
                        elseif(!mysql_select_db('database_name')) {
                            echo "<option>Cannot select database</option>\n";
                        }
                        else {
                            if(!$rs = mysql_query("SELECT DISTINCT state, state_code FROM zip_code_dist ORDER BY state")) {
                                echo "<option>Error getting state values from database</option>\n";
                            }
                            elseif(mysql_num_rows($rs) == 0) {
                                echo "<option>No records found</option>\n";
                            }
                            else {
                                $i = 0;
                                while($row = mysql_fetch_array($rs)) {
                                    if($i % 5 == 0) {
                                        echo "<option value=\"\">--------------------</option>\n";
                                    }
                                    echo "<option value=\"$row[state_code]\">$row[state]</option>\n";
                                    $i++;
                                }
                            }
                        }
                        
                    ?>
                </select>
            </label>
            <br />
            <label>
                Select a city:&nbsp;
                <select id="city" name="city" disabled="disabled">
                    <option>Select a state ...</option>
                </select>
            </label>
        </form>
        <p><a href="http://www.dougv.com/blog/2009/04/24/using-ajax-to-data-bind-a-child-drop-down-list-based-on-the-selected-option-of-a-parent-select-control/">Using AJAX To Data Bind A Child Drop Down List Based On The Selected Option Of A Parent Select Control</a></p>
    </body>
</html>