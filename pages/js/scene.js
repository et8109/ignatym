$(function(){ alert('welcome')});

function getDesc(kwid){
    $.get("getDesc.php?id="+kwid,
        function(data){
	    $("#desc").html(data);
        });
}
