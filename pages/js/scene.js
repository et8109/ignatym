//$(function(){ alert('welcome')});

function getKwDesc(kwid){
    $.get("ajax/getKwDesc.php?id="+kwid,
        function(data){
	    $("#desc").html(data);
        });
}

function getUserDesc(uid){
    $.get("ajax/getUserDesc.php?id="+uid,
        function(data){
            $("#desc").html(data);
        });
}

function getItemDesc(uid){
    alert("yep");
    $.get("ajax/getItemDesc.php?id="+uid,
        function(data){
            $("#desc").html(data);
        });

}
