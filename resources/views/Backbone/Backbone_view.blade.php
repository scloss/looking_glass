@include('Nav.header')

<link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/neo4jd3.min.css?v=0.0.1">
        <style>
            body,
            html,
            .neo4jd3 {
                height: 100%;
                
            }
        </style>

<script src="jquery/jquery-1.8.3.min.js"></script>

<style type="text/css">
	th{
		white-space: nowrap;
		font-size: 12px;
	}
	td{
		white-space: nowrap;
		font-size: 12px;
	}

	tr.selected td {
    background-color: #D0DFDF;
    color: #fff;    
	}
	.search-header{
		 background-color: #DADFDF;
	}

	.date-field{
		min-width: 80px;
	}


    #blue {
  top: 1em;
  left: 1em;
}

#orange {
  top: 1em;
  left: 8em;
}

#green {
  top: 1em;
  left: 16em;
}


</style>




<body>


	<div >
	<table>
		<tr>
			<th><center>Link Name</center></th>
			<th><center><input type="checkbox" checked="checked" name="chkbxAll" onClick="checkAll(this);"></center></th>
			<th><center>Districtwise Link Name</center></th>
			<th><center>Utilization</center></th>
			<th><center>Built Capacity</center></th>
			<th><center>Hop Count</center></th>
			<th><center>Protection Utilization</center></th>
			<th><center>Protection Built Capacity</center></th>
			<th><center>Sellable?</center></th>
			<th><center>Free Capacity</center></th>
		</tr>


		@foreach($array_bb_active as $bb_active)
		<tr class="table-row" @if($bb_active->sellable=='Yes')  style="background-color:#ffd800; font-weight: bold;" @endif>
			<td title="Hopwise Link: {{$bb_active->hopwise_link_name}}"><center>{{$bb_active->link_name}}</center></td>
			<td><center><input type="checkbox" checked="checked" name="chkbx" value="{{$bb_active->districtwise_link_name}}" onChange="checkAlertz();"></center></td>
			<td><center>{{$bb_active->districtwise_link_name}}</center></td>
			<td><center>{{number_format($bb_active->utilization)}}</center></td>
			<td><center>{{number_format($bb_active->built_capacity)}}</center></td>
			<td><center>{{$bb_active->hop_count}}</center></td>
			<td title="Protection Exist? {{$bb_active->protection_exist}}"><center>{!! !empty($bb_active->protection_utilization) ? number_format($bb_active->protection_utilization) : '-' !!}</center></td>
			<td title="Protection Exist? {{$bb_active->protection_exist}}"><center>{!! !empty($bb_active->protection_built_capacity) ? number_format($bb_active->protection_built_capacity) : '-' !!}</center></td>
			<td><center>{{$bb_active->sellable}}</center></td>
			<td><center>{!! !empty($bb_active->free_capacity) ? number_format($bb_active->free_capacity) : '-' !!}</center></td>
		</tr>
				

		@endforeach

		

	</table>
	</div>

	
	<div id="neo4jd3"></div>
        

        <!-- Scripts -->
        <script src="js/d3.min.js"></script>
        <script src="js/neo4jd3.js?v=0.0.1"></script>
        <script type="text/javascript">
            var neo;
            function init() {
                neo = new Neo4jd3('#neo4jd3', {
                    highlight: [
                        {
                            class: 'Project',
                            property: 'name',
                            value: 'neo4jd3'
                        }, {
                            class: 'User',
                            property: 'userId',
                            value: 'eisman'
                        }
                    ],
                    icons: {
//                        
                    },
                    images: {
                        'Address': 'img/twemoji/1f3e0.svg',
//                        'Api': 'img/twemoji/1f527.svg',
                        'BirthDate': 'img/twemoji/1f382.svg',
                        'Cookie': 'img/twemoji/1f36a.svg',
                        'CreditCard': 'img/twemoji/1f4b3.svg',
                        'Device': 'img/twemoji/1f4bb.svg',
                        'Email': 'img/twemoji/1f600.svg',
                        'Git': 'img/twemoji/1f600.svg',
                        'Github': 'img/twemoji/1f600.svg',
                        'icons': 'img/twemoji/1f600.svg',
                        'Ip': 'img/twemoji/1f600.svg',
                        'Issues': 'img/twemoji/1f600.svg',
                        'Language': 'img/twemoji/1f600.svg',
                        'Options': 'img/twemoji/1f600.svg',
                        'Password': 'img/twemoji/1f600.svg',
//                        'Phone': 'img/twemoji/1f4de.svg',
                        'Project': 'img/twemoji/1f600.svg',
                        'Project|name|neo4jd3': 'img/twemoji/2196.svg',
//                        'SecurityChallengeAnswer': 'img/twemoji/1f4ac.svg',
                        'User': 'img/twemoji/1f600.svg'
//                        'zoomFit': 'img/twemoji/2194.svg',
//                        'zoomIn': 'img/twemoji/1f50d.svg',
//                        'zoomOut': 'img/twemoji/1f50e.svg'
                    },
                    minCollision: 100,
                    neo4jDataUrl: 'json/neo4jData.json',
                    nodeRadius: 10,
                    onNodeDoubleClick: function(node) {
                        
                    },
                    onRelationshipDoubleClick: function(relationship) {
                        console.log('double click on relationship: ' + JSON.stringify(relationship));
                    },
                    zoomFit: false
                });
            }

            //console.log(neo4jd3.abc());

            window.onload = function(){
                init();
                //neo.checkAlert();
            }

            
        </script>



        <script>
        ///////////////////////Filter/////////////////////////////////////
var graph, store;
//  filtered types
typeFilterList = [];

//  filter button event handlers

function checkAlertx(a){
    neo.checkAlert(a);
    neo.checkBlert(a);
}
function checkAlerty(a){
    
    //console.log(a.value);
    //console.log(a.checked);
    var val = a.value;
    var chk = 0;
    if (a.checked) {chk = 1};
    neo.checkAlert(val,chk);
    neo.checkBlert(val,chk);
}

/*function checkAlertz(){
    checkboxes = document.getElementsByName('chkbx');
    //console.log(checkboxes);
    var bigArr = [];
    var smallArr = [];
    for(var i=0, n=checkboxes.length;i<n;i++) {
        smallArr = [];
        if(checkboxes[i].checked){
            smallArr = [checkboxes[i].value,1];
        }else{
            smallArr = [checkboxes[i].value,0];
        }
        bigArr[i] = smallArr;
    }
    neo.checkClert(bigArr);
}

function checkAll(a){
    checkboxes = document.getElementsByName('chkbx');
    //console.log(checkboxes);
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = a.checked;
    }

    var bigArr = [];
    var smallArr = [];
    for(var i=0, n=checkboxes.length;i<n;i++) {
        smallArr = [];
        if(checkboxes[i].checked){
            smallArr = [checkboxes[i].value,1];
        }else{
            smallArr = [checkboxes[i].value,0];
        }
        bigArr[i] = smallArr;
    }
    neo.checkClert(bigArr);
}
*/

function checkAlertz(){
    checkboxes = document.getElementsByName('chkbx');
    //console.log(checkboxes);
    var bigArr = [];
    var smallArr = [];
    for(var i=0, n=checkboxes.length;i<n;i++) {
        smallArr = [];
        if(checkboxes[i].checked){
            smallArr = [checkboxes[i].value,1];
            bigArr.push(smallArr);
        }
        
    }
    neo.checkClert(bigArr);
}

function checkAll(a){
    checkboxes = document.getElementsByName('chkbx');
    //console.log(checkboxes);
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = a.checked;
    }

    var bigArr = [];
    var smallArr = [];
    for(var i=0, n=checkboxes.length;i<n;i++) {
        smallArr = [];
        if(checkboxes[i].checked){
            smallArr = [checkboxes[i].value,1];
            bigArr.push(smallArr);
        }
        
    }
    neo.checkClert(bigArr);
}



function filter(nodeId) {
  //  add and remove nodes from data based on type filters
  alert(nodeId);

  //  add and remove links from data based on availability of nodes
       
}




////////////////////////Filter Ends//////////////////////////////////


        </script>


</body>

<script type="text/javascript" src="jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>






@include('Nav.footer')