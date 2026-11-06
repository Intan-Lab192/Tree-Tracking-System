<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../authentication/login.php");
    exit;
}

include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$treeLocations=[];

$sql="

SELECT

T.TREE_CODE,

T.CURRENT_STATUS,

T.LATITUDE,

T.LONGITUDE,

T.HEIGHT,

T.LAST_INSPECTION_DATE,

S.COMMON_NAME,

L.LOCATION_NAME

FROM TREE T

LEFT JOIN SPECIES_CATALOG S

ON T.SPECIES_ID=S.SPECIES_ID

LEFT JOIN LOCATIONZONE L

ON T.LOCATION_ID=L.LOCATION_ID

WHERE

LATITUDE IS NOT NULL

AND

LONGITUDE IS NOT NULL

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $treeLocations[]=$row;
}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

📍 Tree Location Map

</h2>

<p class="text-muted">

Interactive map of all registered trees.

</p>

<div class="card shadow">

<div class="card-body">

<div class="row mb-3">

<div class="col-md-3">

<select
id="statusFilter"
class="form-select">

<option value="">

All Status

</option>

<option>

Healthy

</option>

<option>

Medium Risk

</option>

<option>

High Risk

</option>

<option>

Needs Inspection

</option>

</select>

</div>

<div class="col-md-3">

<input

type="text"

id="searchTree"

class="form-control"

placeholder="Search Tree Code">

</div>

<div class="col-md-3">

<button

type="button"

id="resetMap"

class="btn btn-secondary w-100">

Reset

</button>

</div>

<div class="col-md-3 text-end">

<span class="badge bg-success">

Healthy

</span>

<span class="badge bg-warning text-dark">

Medium

</span>

<span class="badge bg-danger">

High

</span>

</div>

</div>

<div

id="fullMap"

style="height:700px;border-radius:10px;">

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>

<script>

var map=L.map('fullMap').setView(

[3.0738,101.5183],

12

);

L.tileLayer(

'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

{

maxZoom:19,

attribution:'© OpenStreetMap'

}

).addTo(map);

const treeLocations=<?php echo json_encode($treeLocations); ?>;

function getMarkerColor(status)
{

    if(status==="Healthy")
    {
        return "green";
    }

    if(status==="Medium Risk")
    {
        return "orange";
    }

    if(status==="High Risk")
    {
        return "red";
    }

    return "blue";

}

treeLocations.forEach(function(tree){

var marker=L.circleMarker(

[

parseFloat(tree.LATITUDE),

parseFloat(tree.LONGITUDE)

],

{

radius:8,

fillColor:getMarkerColor(tree.CURRENT_STATUS),

color:"#ffffff",

weight:2,

fillOpacity:0.9

}

);

marker

.addTo(map)

.bindPopup(

"<div style='min-width:220px;'>"+

"<h6><b>🌳 "+tree.TREE_CODE+"</b></h6>"+

"<hr style='margin:6px 0;'>"+

"<b>Species:</b> "+tree.COMMON_NAME+"<br>"+

"<b>Location:</b> "+tree.LOCATION_NAME+"<br>"+

"<b>Status:</b> "+tree.CURRENT_STATUS+"<br>"+

"<b>Height:</b> "+tree.HEIGHT+" m<br>"+

"<b>Last Inspection:</b> "+tree.LAST_INSPECTION_DATE+

"</div>"

);

});

/* ======================================
   SEARCH TREE
====================================== */

document.getElementById("searchTree").addEventListener("keyup",function(){

    let keyword=this.value.trim().toUpperCase();

    map.eachLayer(function(layer){

        if(layer instanceof L.CircleMarker)
        {
            map.removeLayer(layer);
        }

    });

    let found=false;

    treeLocations.forEach(function(tree){

        if(
            keyword=="" ||
            tree.TREE_CODE.toUpperCase().includes(keyword)
        )
        {

            found=true;

            var marker=L.circleMarker(

                [

                    parseFloat(tree.LATITUDE),

                    parseFloat(tree.LONGITUDE)

                ],

                {

                    radius:8,

                    fillColor:getMarkerColor(tree.CURRENT_STATUS),

                    color:"#ffffff",

                    weight:2,

                    fillOpacity:0.9

                }

            );

            marker.addTo(map)

            .bindPopup(

                "<div style='min-width:220px;'>"+

                "<h6><b>🌳 "+tree.TREE_CODE+"</b></h6>"+

                "<hr style='margin:6px 0;'>"+

                "<b>Species:</b> "+tree.COMMON_NAME+"<br>"+

                "<b>Location:</b> "+tree.LOCATION_NAME+"<br>"+

                "<b>Status:</b> "+tree.CURRENT_STATUS+"<br>"+

                "<b>Height:</b> "+tree.HEIGHT+" m<br>"+

                "<b>Last Inspection:</b> "+tree.LAST_INSPECTION_DATE+

                "</div>"

            );

            if(keyword!="")
            {

                map.setView(

                    [

                        parseFloat(tree.LATITUDE),

                        parseFloat(tree.LONGITUDE)

                    ],

                    18

                );

                marker.openPopup();

            }

        }

    });

});

/* ======================================
   SEARCH TREE CODE
====================================== */

document.getElementById("searchTree").addEventListener("keyup", function () {

    var keyword = this.value.trim().toUpperCase();

    map.eachLayer(function(layer){

        if(layer instanceof L.CircleMarker)
        {
            map.removeLayer(layer);
        }

    });

    treeLocations.forEach(function(tree){

        if(
            keyword==="" ||
            tree.TREE_CODE.toUpperCase().includes(keyword)
        )
        {

            var marker=L.circleMarker(

                [

                    parseFloat(tree.LATITUDE),

                    parseFloat(tree.LONGITUDE)

                ],

                {

                    radius:8,

                    fillColor:getMarkerColor(tree.CURRENT_STATUS),

                    color:"#ffffff",

                    weight:2,

                    fillOpacity:0.9

                }

            );

            marker.addTo(map)

            .bindPopup(

                "<div style='min-width:220px;'>"+

                "<h6><b>🌳 "+tree.TREE_CODE+"</b></h6>"+

                "<hr style='margin:6px 0;'>"+

                "<b>Species:</b> "+tree.COMMON_NAME+"<br>"+

                "<b>Location:</b> "+tree.LOCATION_NAME+"<br>"+

                "<b>Status:</b> "+tree.CURRENT_STATUS+"<br>"+

                "<b>Height:</b> "+tree.HEIGHT+" m<br>"+

                "<b>Last Inspection:</b> "+tree.LAST_INSPECTION_DATE+

                "</div>"

            );

            if(keyword!=="")
            {
                map.setView(
                    [
                        parseFloat(tree.LATITUDE),
                        parseFloat(tree.LONGITUDE)
                    ],
                    18
                );

                marker.openPopup();
            }

        }

    });

});

/* ======================================
   RESET MAP
====================================== */

document.getElementById("resetMap").addEventListener("click",function(){

    document.getElementById("searchTree").value="";

    map.eachLayer(function(layer){

        if(layer instanceof L.CircleMarker)
        {
            map.removeLayer(layer);
        }

    });

    treeLocations.forEach(function(tree){

        var marker=L.circleMarker(

            [

                parseFloat(tree.LATITUDE),

                parseFloat(tree.LONGITUDE)

            ],

            {

                radius:8,

                fillColor:getMarkerColor(tree.CURRENT_STATUS),

                color:"#ffffff",

                weight:2,

                fillOpacity:0.9

            }

        );

        marker.addTo(map)

        .bindPopup(

            "<div style='min-width:220px;'>"+

            "<h6><b>🌳 "+tree.TREE_CODE+"</b></h6>"+

            "<hr style='margin:6px 0;'>"+

            "<b>Species:</b> "+tree.COMMON_NAME+"<br>"+

            "<b>Location:</b> "+tree.LOCATION_NAME+"<br>"+

            "<b>Status:</b> "+tree.CURRENT_STATUS+"<br>"+

            "<b>Height:</b> "+tree.HEIGHT+" m<br>"+

            "<b>Last Inspection:</b> "+tree.LAST_INSPECTION_DATE+

            "</div>"

        );

    });

    map.setView([3.0738,101.5183],12);

});

</script>