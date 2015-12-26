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
            parseResponse(data);
        });
}

function resurrect(nid){
    $.get("ajax/resurrect.php?nid="+nid,
        function(data){
            parseResponse(data);
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
    $("#log").empty();
    $.get("ajax/walk.php?sid="+sid,
        function(data){
            $("#main").html(data);
        });
}

function parseResponse(data){
    alert(data);
    data = JSON.parse(data);
    if(data['main'] != ""){
      $("#main").html(data['main']);
    }
    if(data['desc'] != ""){
      $("#desc").html(data['desc']);
    }
    for(l of data['log']){
      $("#log").append(l);
    }
    for(u of data['upd']){
      $("."+u['type']+"#"+u['id']).attr("class", u['classes']);
    }
}
