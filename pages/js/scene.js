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

function getItemDesc(iid){
    $.get("ajax/getItemDesc.php?id="+iid,
        function(data){
            $("#desc").html(data);
        });
}

function getNpcDesc(nid){
    $.get("ajax/getNpcDesc.php?id="+nid,
        function(data){
            $("#desc").html(data);
        });
}

function startCraft(){
    $("#log").append("starting craft");
}

function attack(nid){
    $.get("ajax/attack.php?nid="+nid,
        function(data){
            $("#log").append(data);
        });
}

function regen(){
    $.get("ajax/regen.php",
        function(data){
            $("#log").append(data);
        });
}

function walk(sid){
    $("#desc").empty();
    $.get("ajax/walk.php?sid="+sid,
        function(data){
            $("#main").html(data);
        });
}

