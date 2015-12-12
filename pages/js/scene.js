//$(function(){ alert('welcome')});

function getKwDesc(kwid){
    $.get("ajax/getDesc.php?id="+kwid,
        function(data){
	    $("#desc").html(data);
        });
}

/*function getUserDesc(uid){
    $.get("getDesc.php?id="+kwid,
        function(data){
            $("#desc").html(data);
        });
}*/
