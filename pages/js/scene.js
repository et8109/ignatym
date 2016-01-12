//$(function(){ alert('welcome')});

var currentActive = $(".user");

function getKwDesc(kwid, span){
    $.get("ajax/getKwDesc.php?id="+kwid,
        function(data){
          addDesc(data, span);
        });
}

function getUserDesc(uid, span){
    $.get("ajax/getUserDesc.php?id="+uid,
        function(data){
          addDesc(data, span);
        });
}

function getItemDesc(iid, span){
    $.get("ajax/getItemDesc.php?id="+iid,
        function(data){
          addDesc(data, span);
        });
}

function getNpcDesc(nid, span){
    $.get("ajax/getNpcDesc.php?id="+nid,
        function(data){
          addDesc(data, span);
        });
}

function startCraft(span){
    var form = "<form id='craftform'> Name: <input type='text' name='name'></br> Description: <textarea name='desc' /></br> <input type='submit'></form>";
    addDesc(form, span);
    $("#craftform").submit(function(evt){
      evt.preventDefault();
      $.post("ajax/craft.php", 
            $("#craftform").serialize(), 
            function(data){
              parseResponse(data);
            });
    });
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
            parseResponse(data);
        });
}

function dropItem(iid){
    $.get("ajax/dropItem.php?iid="+iid,
        function(data){
            parseResponse(data);
        });
}

function walk(sid){
    $(".desc:not(#0.desc)").empty();
    $("#log").empty();
    $.get("ajax/walk.php?sid="+sid,
        function(data){
            $("#0.desc").html(data);
        });
}

function setActive(obj){
   currentActive.removeClass("act");
   currentActive = $(obj);
   currentActive.addClass("act");
}

function getDescDivNum(obj){
  var n = parseInt( $(obj).parent().attr('id') );
  return n;
}

function addDesc(txt, src){
  setActive(src);
  var num = getDescDivNum(src);
  $(".desc").slice(num+1).remove();
  $("#dlist").append("<div class='desc' id="+(num+1)+">"+txt+"</div>");
}

function parseResponse(data){
    alert(data);
    data = JSON.parse(data);
    if(data['main'] != ""){
      $("#0.desc").html(data['main']);
    }
    if(data['desc'] != ""){
      $("#1.desc").html(data['desc']);
    }
    for(l of data['log']){
      $("#log").append(l);
    }
    for(u of data['upd']){
      $("."+u['type']+"#"+u['id']).attr("class", u['classes']);
    }
}
