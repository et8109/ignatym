///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
//Globals
var version = 4;

window.onerror = function(msg, url, line) {
    alert("Error: "+msg+" url: "+url+" line: "+line);
};

disableInput();
var frontLoadSceneText;
var frontLoadKeywords;
/**
 *Set up, needed
 */
(function(){
   sendRequest(
        "mod=setup&function=setUp&version="+version,
        function(response){
            var info = response[0];
            alert("setup response recieved: "+info.pid);
            playerID = parseInt(info.pid);
            currentScene = parseInt(info.sceneID);
            frontLoadSceneText = parseInt(info.fls);
            frontLoadKeywords = parseInt(info.flkw);
            enableInput();
            addText("Welcome!");
            addText("'/look' to look around.");
            addText("'/help' for a link to the guide.");
        }
    ); 
}());

//[id][0:name, 1:description]
var sceneText={};
if (frontLoadSceneText) {
    sendRequest(
        "mod=setup&function=frontLoadScenes",
        function(scenes){
            for(key in scenes){
                sceneText[scenes[key].sid] = [scenes[key].spanText,scenes[key].desc];
            }
        }
    );
}

//[word][0: span text 1: desc] //keyword type not needed
var keywordText={};
if (frontLoadKeywords) {
    sendRequest(
        "mod=setup&function=frontLoadKeywords",
        function(keywords) {
            for(key in keywords){
                sceneText[keywords[key].word] = [keywords[key].spanText,keywords[key].desc];
            }
        }
    );
}

/**
*sets the timer to update chat
*/
var updater = setInterval("updateChat()", 3000);
/**
 *the class for all waits
 */
function listener(message, onInput, onCancel){
    /**
     *checks to make sure no other listeners are active
     *sets the wait message
     */
    this.start = function(){
        endListening();
        /*if (textLineListener != null || textAreaListener != null) {
            setErrorMessage("You're busy with something else.");
            return false;
        }*/
        setWaitMessage(message);
        return true;
    }
    /**
     *calls the function of this listener
     */
    this.onInput = function(input){
        onInput(input);
    }
    /**
     *called when cancelled
     */
    this.onCancel = function(){
        onCancel();
    }
}
var textLineListener = null;
var textAreaListener = null;
/**
 *sets a listener for the text line input
 */
function setTextLineListener(listener_){
    if (listener_.start()) {
        textLineListener = listener_;
    }
}
/**
 *sets a listener for the text area input
 *opens the text area
 */
function setTextAreaListener(listener_){
    if (listener_.start()) {
        openTextArea();
        textAreaListener = listener_;
    }
}
/**
 *closes the text area
 *clears the wait message
 *removes listeners
 */
function endListening() {
    closeTextArea();
    clearWaitMessage();
    if(textLineListener != null){
        textLineListener.onCancel();
        textLineListener = null;
    }
    if(textAreaListener != null){
        closeTextArea();
        textAreaListener.onCancel();
        textAreaListener = null;
    }
}
//text line listeners
var listener_item_name = new listener("Enter the name of the item you are crafting.",
                                            function(input){addCraftName(input);}, function(){}
                                          );
var listener_item_name_to_add_to_scene = new listener("Enter an item name to add.",
                                            function(input){addItemNoteToScenePrompt(input);}, function(){}
                                          );
var listener_item_name_to_remove_from_scene = new listener("Enter an item name to remove.",
                                            function(input){removeItemFromScene(input);}, function(){}
                                          );
var listener_item_name_to_change_note_of = new listener("Enter an item name to change the note of.",
                                            function(input){newNoteTextPromt(input);}, function(){}
                                          );
var listener_quit_job = new listener("Enter 'quit' to leave your job.",
                                            function(input){quitJob(input);}, function(){}
                                          );
var listener_learn_spell = new listener("Enter 'learn' to learn this spell.",
                                            function(input){learnSpell(input);}, function(){}
                                          );
var listener_attack_again = new listener("Enter to attack again.",
                                            function(input){attack(input);}, function(){}
                                          );
//text area listeners
var listener_personal_desc = new listener("Enter your description below.",
                                            function(input){setNewDescription(input);}, function(){}
                                          );
var listener_item_desc = new listener("Enter the item description below.",
                                          function(input){addCraftDescription(input);}, function(){}
                                          );
var listener_new_items_note = new listener("Enter the item's note below.",
                                          function(input){addItemToScene(input);}, function(){}
                                          );
var listener_revised_item_note = new listener("Enter the item note below.",
                                          function(input){changeItemNote(input);},function(){}
                                          );
var listener_new_scene_desc = new listener("Enter the location description below.",
                                          function(input){editSceneDesc(input);},function(){}
                                          );

/**
 A bunch of types of random stuff.
 Each should have:
 id
 description
 **repeated in sql
 */
var spanTypes = {
    ITEM: 0,
    PLAYER: 1,
    SCENE: 2,
    ACTION: 3,
    KEYWORD: 4,
    NPC: 5,
    PATH: 6
}

var textBox="textBox1";
var OfftextBox="textBox2";

/**
 *if the sound is muted or not
 */
var muted = false;
/**
 *the types of public actions added to the chat text.
 *duplicated in helper function
 */
var actionTypes ={
    WALKING : 0,
    ATTACK : 1,
    MESSAGE : 2
}

/**
 *holds the name of the item to be:
 *crafted
 *added to scene
 *have a changed note in scene
 */
var targetName;
/**
 *saves the current scene id. used for addDesc of currentScene
 */
var currentScene;
/**
 *the id of the player
 */
var playerID;
/**
 *if enabled, every query will show how long it took
 */
var requestSpeedChecker = false;
/**
 *if eneabled, text will be displayed with a paper background
 */
var onPaper = false;
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
//big methods
/**
*Returns if key was not enter.
*Checks for waiting, commands with /, and talking
*/
function textTyped(e){
    //if not enter button
    if(event.keyCode != 13){
        if (textLineListener == listener_attack_again) {
            endListening();
        }
        return;
    }
    //reset prev input index
    clearErrorMessage();
    var inputText = getInputText();
    //make sure input is valid
    if (inputText == null) {
        //nothing, skips to clear input
    }
    //listener check
    if (textLineListener != null) {
        alert("using listener yolo");
        textLineListener.onInput(inputText);
    }
    //command check
    else if(inputText.indexOf("/") == 0){
        closeTextArea();
        //find command
        inputText = inputText.split(" ");
        switch (inputText[0]) {
            case("/look"):
                deactivateActiveLinks();
                addDesc(spanTypes.SCENE, currentScene);
                break;
            case('/closelook'):
                closeLook();
                break;
            case("/attack"):
                inputText[0] = "";
                inputText = inputText.join("");
                attack(inputText);
                break;
            case("/help"):
                addText("<a href='guide.php' target='_blank'>Guide</a>");
                break;
            case("/put"):
                inputText[0] = "";
                inputText = inputText.join(" ");
                inputText = inputText.split(" in ");
                putItemIn(inputText[0].trim(), inputText[1].trim());
                break;
            case("/take"):
                inputText[0] = "";
                inputText = inputText.join(" ");
                inputText = inputText.split(" from ");
                takeItemFrom(inputText[0].trim(), inputText[1].trim());
                break;
            case("/give"):
                inputText[0] = "";
                inputText = inputText.join(" ");
                inputText = inputText.split(" to ");
                giveItemTo(inputText[0].trim(), inputText[1].trim());
                break;
            case("/manage"):
                getManageSceneText();
                break;
            case("/quitjob"):
                quitJobPrompt();
                break;
            case("/hire"):
                inputText[0] = "";
                inputText = inputText.join("");
                hireEmployee(inputText);
                break;
            case("/fire"):
                inputText[0] = "";
                inputText = inputText.join("");
                fireEmployee(inputText);
                break;
            case("/self"):
                addPlayerInfo();
                break;
            case("/destroy"):
                inputText[0] = "";
                inputText = inputText.join(" ");
                inputText = inputText.trim();
                destroyItem(inputText);
                break;
            case('/regen'):
                regenerate();
                break;
            case("/read"):
                inputText[0] = "";
                inputText = inputText.join(" ");
                inputText = inputText.trim();
                readBook(inputText);
                break;
            case("/forget"):
                forgetSpell(inputText);
            break;
            case("/cast"):
                inputText[0] = "";
                inputText = inputText.join(" ");
                inputText = inputText.trim();
                castSpell(inputText);
                break;
            case("/time"):
                getTime();
            break;
            default:
                addText(inputText+"..unknown command");
                break;
        }
    }
    //not waiting, and not command
    else{
        if (inputText == "") {
            return;
        }
        speak(inputText);
    }
    
    //always: clear input
    document.getElementById("input").value="";
}

/**
*gets the lines of chat not yet seen.
*adds the lines to the text box
*/
function updateChat(){
    sendRequest("mod=chat&function=updateChat",
        function(info){
            //alerts.alerts should always come first
            if (info[0].numAlerts == true) {
                var numAlerts = info[0].num;
                setAlertButton(parseInt(numAlerts));
                info.shift(); //removes the first item from the array
            }
            //chat. info array should just be chat info now   
            for(l in info){
                var chatLine = info[l].text;
                //if an action, not a chat
                if (chatLine.indexOf("<") == 1) {
                    var type = parseInt(chatLine.charAt(2));
                    chatLine = chatLine.split("<>");
                    chatLine = chatLine[1];
                    switch(type){
                        case(actionTypes.ATTACK):
                            //let the player know somehow that there is combat, sound maybe
                            addText(chatLine);
                            //ask player to attack again
                            if (info[l].pid == playerID) {
                                setTextLineListener(listener_attack_again);
                            }
                            break;
                        case(actionTypes.WALKING):
                            //footsteps sound
                            addText(chatLine);
                            break;
                        case(actionTypes.MESSAGE):
                            //currently only roars from bosses
                            addText(chatLine);
                            break;
                    }
                }
                //if a chat
                else{
                    addText(info[l].pname+":"+chatLine);
                }
            }
        }
    );
}

/**
 *prints the description into the text box.
 *id is actually word for descriptions
 */
function addDesc(spanType, id) {
    switch (spanType) {
        case(spanTypes.SCENE):
            if (frontLoadSceneText) {
                addText( sceneText[id][0] );
                addText( sceneText[id][1] );
                return;
            }
            break;
        case(spanTypes.KEYWORD):
            if (frontLoadKeywords) {
                addText( keywordText[id][0] );
                addText( keywordText[id][1] );
                return;
            }
            break;
    }
    sendRequest("mod=general&function=getDesc&type="+spanType+"&ID="+id,
        function(lines) {
            for(l in lines){
                addText(lines[l].text);
            }
        }
    );
}
  
/**
*Sets the player's new description
*/
function setNewDescription(desc) {
    sendRequest("mod=general&function=updateDescription&Description="+desc,
        function(response) {
            closeTextArea();
            endListening();
        }
    );
}


/**
* Move scene, then print new scene description.
* End waits
* Also updates currentChatTime and adds a walking message ot chat
*/
function walk(newSceneId) {
    currentScene = newSceneId;
    deactivateActiveLinks();
    if (frontLoadSceneText) {
        addDesc(spanTypes.SCENE, newSceneId);
    }
    sendRequest("mod=general&function=moveScenes&newScene="+newSceneId,
        function(response){
            if (!frontLoadSceneText) {
                addDesc(spanTypes.SCENE, newSceneId);
            }
        }
    );
    endListening();
    closeTextArea();
}
/**
*open text area and display player description.
*wait for a new description input
*/
function displayMyDesc() {
    openTextArea();
    sendRequest("mod=general&function=getDesc&type="+spanTypes.PLAYER,
        function(response){
            //first is name, second id desc
            //remove styling, not visible in text area
            setTextAreaText(removeHtmlStyling(response[1].text));
        }
    );
    endListening();
    setTextAreaListener(listener_personal_desc);
}

/**
 *When the anvil is clicked. checks if the player can start crafting.
 *asks for item name.
 */
function startCraft(){
//if waiting for something.
if (isWaiting()) {
    addText("You're already focused on something else. Finish with that, then you can craft something");
    return;
}
addText("You clear some space on the iron anvil. What do you want to make?");
setTextLineListener(listener_item_name);
}
/**
 *When an item name is given, tells the player to give a description
 */
function addCraftName(name){
    targetName = name;
    //has a name, need a description
    sendRequest("mod=crafting&function=getCraftInfo",
        function(response){
            endListening();
            addText("Your craftSkill is "+response[0].info+ ". enter the "+targetName+"'s description below. Your tags are: tags not done yet");
            setTextAreaListener(listener_item_desc);
        }
    );
}
/**
 *When an items description is given, and a name was already chosen
 */
function addCraftDescription(desc){
    if (targetName == "") {
        addText("[Something wierd happened. Woops! Please let me know what you did. Thanks.]");
        endListening();
        return;
    }
    //input into database
    sendRequest("mod=crafting&function=craftItem&Name="+targetName+"&Description="+desc,
        function(response){
            addText("You make a "+targetName);
            closeTextArea();
            endListening();
            //sound
            playSound("anvil");
            targetName = "";
        }
    );
}

/**
 *Begins the service at a pub.
 *prints items available
 */
function startWaiter(){
    //check current waits
    if (isWaiting()){
        addText("You're already focused on something else. Finish with that, then you can order something");
        return;
    }
    addText("A waiter approaches your table. 'Hello there. What would you like?' they ask.");
    //check menu at this pub
    getItemsInScene("Oops, sorry. There is nothing available right now.");
}

/**
 *gets the items and materials in the scene(item with store note).
 *prints empty text if nothing was found
 */
function getItemsInScene(onEmptyText){
    sendRequest("mod=general&function=getItemsInScene",
        function(items) {
            if (response.length == 0) {
                onEmptyText ? addText(onEmptyText) : addText('Nothing here.');
                return;
            }
            //success
            startPaper();
            for(i in items){
                if (items[i].item) {
                    addText(items[i].spanText);
                    addText(items[i].note);
                } else if (items[i].material) {
                    addText(items[i].spanText);
                }
            }
            endPaper();
        }
    );
}
/**
 *prompts for what item to add to the curent scene
 */
function addItemToScenePrompt() {
    addText("what item of yours would you like to add to this location?");
    setTextLineListener(listener_item_name_to_add_to_scene);
}

/**
 *adds an item to the current scene
 */
function addItemNoteToScenePrompt(name){
    targetName = name;
    endListening();
    setTextAreaListener(listener_new_items_note);
}
/**
 *prompts for what item to remove from the scene
 */
function removeItemFromScenePrompt() {
    setTextLineListener(listener_item_name_to_remove_from_scene);
}
/**
 *adds the item and its note to the scene
 */
function addItemToScene(note){
    endListening();
    sendRequest("mod=manage&function=addItemToScene&Name="+targetName+"&Note="+note,
        function(response){
            addText("added "+targetName);
            return;
        }
    );
}
/**
 *removes the given item from the scene
 */
function removeItemFromScene(name){
    endListening();
    sendRequest("mod=manage&function=removeItemFromScene&Name="+name,
        function(response){
            addText("you take the "+name);
            return;
        }
    );
}
/**
 *prompts the player for what note they want to change in this scene
 */
function changeItemNotePrompt() {
    addText("what item note would you like to change in this location?");
    endListening();
    setTextLineListener(listener_item_name_to_change_note_of);
}
/**
 *prompts for the new note text
 */
function newNoteTextPromt(name){
    targetName = name;
    endListening();
    setTextAreaListener(listener_revised_item_note);
}
/**
 *prompts for the new scene description
 */
function newSceneDescPrompt(){
    addText("Edit the description below.");
    endListening();
    //get scene desc
    sendRequest("mod=general&function=getDesc&type="+spanTypes.SCENE,
        function(response){
            setTextAreaListener(listener_new_scene_desc);
            //first is name, second is desc
            setTextAreaText(removeHtmlStyling(response[1].text));
        }
    );
}
/**
 *gets the note text and changes the item note
 */
function changeItemNote(note){
    endListening();
    sendRequest("mod=manage&function=changeItemNote&Name="+targetName+"&Note="+note,
        function(response){
            addText("changed note for "+targetName);
            return;
        }
    );
}  
/**
 *reuests to change the description of this scene
 */
function editSceneDesc(desc){
    endListening();
    sendRequest("mod=manage&function=changeSceneDesc&desc="+desc,
        function(response){
           addText("changed scene description"); 
        }
    );
}


/**
*find who the player wants to attack, after /attack
*/
function attack(name) {
    //if already attacked once
    if (isWaiting()) {
        endListening();
        if (name != "") {
            targetName="";
            return;
        }
        name=targetName;
    } else{
        targetName = name;
    }
    sendRequest("mod=combat&function=attack&Name="+name,
        function(response){}
    );
}
/**
 *clears the alerts which are not required
 */
function clearAlerts(){
    closeMenu();
    sendRequest("mod=general&function=clearAlerts",
        function(){}
    );
}
/**
 *opens the menu and the first page
 */
function openMenu(){
    document.getElementById("menuMain").style.visibility="visible";
    setMenuText("~Menu~");
}
/**
 *sets the text inside the menu
 */
function setMenuText(text){
    document.getElementById("menuMainInside").innerHTML=text;
}
/**
 *shows alerts in menu box
 */
function openAlerts(){
    var inside = document.getElementById("menuMainInside");
    inside.innerHTML = "Loading..";
    sendRequest(
        "mod=general&function=getAlertMessages",
        function(alerts){
            inside.innerHTML = "Alerts:";
            for(a in alerts){
                inside.innerHTML += alerts[a].text;
            }
            addAlertsEnding(alerts.length!=0);
        }
    );
}
/**
 *adds the ending to the alerts menu
 */
function addAlertsEnding(alertsBool) {
    var inside = document.getElementById("menuMainInside");
    var clearButton = "</br><span id='clearAlertsButton' onclick='clearAlerts()'>[Clear]</span>";
    var noAlerts = "</br>None";
    if (alertsBool) {
        inside.innerHTML+=clearButton;
    } else{
        inside.innerHTML+=noAlerts;
    }
}
/**
 *closes the menu
 */
function closeMenu(){
document.getElementById("menuMain").style.visibility="hidden";
}
/**
 *displays the options in the alert box
 */
function openOptions(){
    var menuInside = document.getElementById("menuMainInside");
    menuInside.innerHTML = "Options:</br>";
    //front load scene text
    if (frontLoadSceneText) {
        menuInside.innerHTML +="<input type='checkbox' onclick='toggleFrontLoadSceneText()' checked='checked'>";
    }
    else{
        menuInside.innerHTML +="<input type='checkbox' onclick='toggleFrontLoadSceneText()'>";
    }
    menuInside.innerHTML +="Front load scene text. About 3 lines.</input></br>";
    //front load keywords
    if (frontLoadKeywords) {
        menuInside.innerHTML +="<input type='checkbox' onclick='toggleFrontLoadKeywords()' checked='checked'>";
    }
    else{
        menuInside.innerHTML +="<input type='checkbox' onclick='toggleFrontLoadKeywords()'>";
    }
    menuInside.innerHTML +="Front load keyword text. About 10 lines.</input>";
    //query speed checker
    menuInside.innerHTML += "</br></br><span onclick='togglersc()'>Enable/disable speed checker.</span>"
}
/**
 *puts an item into a container item
 */
function putItemIn(itemName, containerName) {
    sendRequest("mod=general&function=putItemIn&itemName="+itemName+"&containerName="+containerName,
        function(response){
        }
    );
}
/**
 *removes an item from a container
 */
function takeItemFrom(itemName, containerName){
    sendRequest("mod=general&function=takeItemFrom&itemName="+itemName+"&containerName="+containerName,
        function(response){
        }
    );
}
/**
 *pulls up the options to manage a scene if player has the rights
 */
function getManageSceneText() {
    sendRequest("mod=manage&function=getManageSceneText",
        function(lines){
            for(l in lines){
                addText(lines[l].text);
            }
        }
    );
}

/**
 *makes sure the player really wants to quit thier job
 */
function quitJobPrompt(){
    endListening();
    setTextLineListener(listener_quit_job);
}

/**
 *removes the player's current job
 */
function quitJob(input) {
    endListening();
    if (input != "quit") {
        return;
    }
    sendRequest("mod=manage&function=quitJob",
        function(response){
            addText("You have quit your job");
        }
    );
}
/**
 *hires someone to the rank below you with the given name
 */
function hireEmployee(name){
    sendRequest("mod=manage&function=hireEmployee&name="+name,
        function(response){
            addText(name+" has been hired");
        }
    );
}
/**
 *fires someone who works for you so they loose thier job
 */
function fireEmployee(name){
    sendRequest("mod=manage&function=fireEmployee&name="+name,
        function(response){
            addText(name+" has been fired");
        }
    );
}
/**
 *displays some info about the player
 */
function addPlayerInfo(){
    sendRequest("mod=general&function=getPlayerInfo",
        function(lines){
            for(l in lines){
                addText(lines[l].text);
            }
        }
    );
}

/**
 *removes an item from the player's inventory
 */
function destroyItem(itemName){
    sendRequest("mod=general&function=destroyItem&name="+itemName,
                function(response){
                    addText(itemName+" has been destroyed");
                }
    );
}

/**
 *gives additional info about the current scene
 */
function closeLook() {
    sendRequest(
        "mod=general&function=closeLook",
        function(lines){
            for(l in lines){
                addText(lines[l].text);
            }
        }
    );
}

/**
 *lets the player become the manager of the scene
 *available from closelook
 */
function beManager(){
    sendRequest(
        "mod=manage&function=becomeManager",
        function(response){
            addText("Success, you are now the manager here!");
        }
    );
}

/**
 *gives an item to another player in the same location
 */
function giveItemTo(item, playerName){
    sendRequest("mod=general&function=giveItemTo&itemName="+item+"&playerName="+playerName,
                function(response) {
                    addText(item+" was given to "+playerName);
                }
    );
}

/**
 *restores the players health if they are in a sanctuary.
 */
function regenerate() {
    sendRequest("mod=combat&function=regen",
                function(response){
                    addText("Your health has been restored.");
                }
    );
}

/**
 *the player reads a book in the scene.
 *prompts player to learn the spell
 */
function readBook(bookName) {
    addText("You open the "+bookName+"...");
    sendRequest("mod=magic&function=readBook&bookName="+bookName,
                function(response){
                    targetName = bookName; //remember for learning the spell
                    startPaper();
                    addText(response[0].text);
                    endPaper();
                    setTextLineListener(listener_learn_spell);
                }
                );
}
/**
 *gives the spell of the book to the player
 */
function learnSpell(input) {
    if (input != "learn") {
        targetName = "";
        endListening();
        return;
    }
    sendRequest("mod=magic&function=learnSpell&bookName="+targetName,
                function(){
                    addText("A warm glow emits from the "+targetName+" as its spell power magically seeps into your hands.");
                    targetName = "";
                }
                );
    endListening();
}

/**
 *removes a spell from the player
 */
function forgetSpell(kwname) {
    sendRequest("mod=magic&function=forgetSpell",
                function(response){
                    addText("You clear your mind of previous spells.");
                    });
}

/**
 *casts a spell
 */
function castSpell(spellname) {
    sendRequest("mod=magic&function=castSpell&name="+spellname,
                function(response){addText(response[0].text);}
                );
}
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
//small methods
/**
*sets the alert button
*/
function setAlertButton(numAlerts) {
    var button = document.getElementById("alert");
    button.innerHTML="alerts["+numAlerts+"]";
    if (numAlerts>0) {
        button.style.color = "gold";
    } else{
        button.style.color = "black";
    }
}
/**
 *sets the text line to the input text
 */
function setTextLine(text){
    document.getElementById("input").value = text;
}

/**
*Adds a line of text to the screen. Also controls the opacity and left/right columns
*/
function addText(text) {
    var currentHeight = document.getElementById(this.textBox).offsetHeight;
    var maxHeight = (document.height || document.body.offsetHeight)-document.getElementById("hub").offsetHeight;
    if (onPaper) {
        document.getElementById(this.textBox).innerHTML += "</br><span class='paper'>"+text+"</span>";
    } else{
        document.getElementById(this.textBox).innerHTML += "</br>"+text;
    }
    document.getElementById(this.OfftextBox).style.opacity =(maxHeight-currentHeight)/maxHeight;
    if (currentHeight+80>maxHeight) {
        //switch text boxes
        var textBox_ = this.textBox;
        this.textBox = this.OfftextBox;
        this.OfftextBox = textBox_;
        //reset opacity, text, line number
        document.getElementById(this.textBox).style.opacity=1;
        document.getElementById(this.textBox).innerHTML = "";
    }
}

/**
*Deactivates all spans with class active
*/
function deactivateActiveLinks(){
    var previous = document.getElementsByClassName('active');
    var numDeactivated = 0;
    var numTotal = previous.length;
    while(numDeactivated < numTotal){
        previous = document.getElementsByClassName('active');
        for(var i=0; i<previous.length; i++){
            previous[i].setAttribute("onclick", null);
            previous[i].setAttribute("class", "inactive");
            numDeactivated++;
        }
    }
}

/**
 *adds to the chat file
 */
function speak(inputText){
    sendRequest("mod=chat&function=speak&inputText="+inputText,
        function() {}
    );
}

/**
 *returns the text in the input field/text line
 *returns null if invalid input and prints error
 */
function getInputText(){
    var text =  document.getElementById("input").value;
    if (!validateInput(text)) {
        return null;
    }
    return text;
}

/**
* Opens the bottom text area, sets the value to blank
* disables main text line
*/
function openTextArea() {
    document.getElementById("input").disabled = true;
    document.getElementById("textArea").value="";
    document.getElementById("extra").style.display="block";
}
/**
 *sets the text area value
 *use removeHtmlStyling beforehand
 */
function setTextAreaText(text){
    document.getElementById("textArea").value=text;
}

/**
 *Returns the text in the text area.
 *returns null and gives an error message if the text area contained < or >
 */
function getTextAreaText(){
    var text = document.getElementById("textArea").value;
    if (!validateInput(text)){
        return null;
    }
    return text;
}
/**
    *Called when the text area done button is clicked
    *looks at waiting stuff
    *enables main text line
    */
function textAreaSubmit() {
    var input = getTextAreaText();
    if (input == null){
        return;
    }
    document.getElementById("input").disabled = false;
    clearErrorMessage();
    if (textAreaListener != null) {
        textAreaListener.onInput(input);
    }
}
/**
*Closes the text area.
*enables input line
*/
function closeTextArea() {
    document.getElementById("input").disabled = false;
    document.getElementById("extra").style.display="none";
}

/**
 *toggles if the sound is muted
 */
function toggleMute(){
    if (muted) {
        muted = false;
        document.getElementById("menuMainMute").innerHTML = "Mute";
    }
    else{
        muted = true;
        document.getElementById("menuMainMute").innerHTML = "Unmute";
    }
}
/**
*plays the specified sound.
*does not repeat
*/
function playSound(soundId){
    if (!muted) {
        document.getElementById(soundId).play();
    }
}

/**
 *returns true if the player is waiting for something,
 *  in the line or area
 */
function isWaiting() {
    return(textAreaListener != null || textLineListener != null);
}
/**
 *removes the html from the text
 *returns the new text
 */
function removeHtmlStyling(text){
    return text.replace(/(<([^>]+)>)/ig,"");
}
/**
 *switches whether the scene text is front loaded or not
 *also tells db
 */
function toggleFrontLoadSceneText(){
    frontLoadSceneText=!frontLoadSceneText;
    var frontLoad;
    if (frontLoadSceneText) {
        frontLoad = 1;
    }
    else{
        frontLoad = 0;
    }
    sendRequest("mod=general&function=setFrontLoadScenes&load="+frontLoad,
        function(){}
    );
}
/**
 *switches whether the keyword text is front loaded or not
 *also tells db
 */
function toggleFrontLoadKeywords(){ 
    frontLoadKeywords=!frontLoadKeywords;
    var frontLoad;
    if (frontLoadKeywords) {
        frontLoad = 1;
    }
    else{
        frontLoad = 0;
    }
    sendRequest("mod=general&function=setFrontLoadKeywords&load="+frontLoad,
        function(){}
    );
}
/**
 *toggles whether the query speed checker is on or off
 */
function togglersc(){
    requestSpeedChecker = !requestSpeedChecker;
}
/**
 *starts putting the text on paper background
 */
function startPaper(){
    onPaper = true;
}
/**
 *ends writing text on paper background
 */
function endPaper(){
    onPaper = false;
}

/**
 *prints the 24 hour time and time of day
 */
function getTime() {
    sendRequest("mod=general&function=getTime",
        function(response){addText(response);}
    );
}

/**
 *checks for unwanted input
 *returns false on fail
 *pints the error message on its own
 */
function validateInput(text){
    //check for < or >
    if (text.indexOf("<") != -1 || text.indexOf(">") != -1) {
        return "please don't use < or >";
    }
    //check for empty string
    if (text.trim == "") {
        return "that was an empty input";
    }
    return true;
}
/**
 *removes the player from the sceneplayers list
 */
function logout() {
    clearInterval(updater);
    disableInput();
    sendRequest("mod=general&function=logout",
                function(){}
    );
}
/**
 *sends a request to the server
 */
function sendRequest(params,returnFunction){
    alert("sending: "+params);
    var request = new XMLHttpRequest();
    request.open("POST","../backend/server.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Content-length", params.length);
    request.setRequestHeader("Connection", "close");
    request.onreadystatechange = function(){
        if (this.readyState==4 && this.status==200) {
            //build info
            var response = this.responseText;
            alert("response: "+response);
            var info = JSON.parse(response);
            //error should always be the first response
            if (info.length > 0 && info[0].error == true) {
                setErrorMessage(info[0].msg);
            } else{
                //success, call function
                returnFunction(info);
            }
            if (requestSpeedChecker) {
                var date = new Date();
                addText("b "+date.getSeconds());
            }
        }
    }
    if (requestSpeedChecker) {
        var date = new Date();
        addText("a "+date.getSeconds());
    }
    request.send(params);
}

/**
 *prevents the player from entering anything
 */
function disableInput() {
    document.getElementById("input").disabled = true;
    document.getElementById("textArea").disabled = true;
}
/**
 *allows input again
 */
function enableInput() {
    document.getElementById("input").disabled = false;
    document.getElementById("textArea").disabled = false;
}
/**
 *sets the wait message and pops up the image
 */
function setWaitMessage(message){
    var waitBox = document.getElementById("wait");
    waitBox.innerHTML = message;
    waitBox.style.visibility = "visible";
}
/**
 *removes the wait message and image
 */
function clearWaitMessage() {
    var waitBox = document.getElementById("wait");
    waitBox.innerHTML = "";
    waitBox.style.visibility = "hidden";
}
/**
 *sets the error message.
 */
function setErrorMessage(message){
    document.getElementById("error").innerHTML = message;
    document.getElementById("errorPoint").style.visibility = "visible";
}
/**
 *clears the error message
 */
function clearErrorMessage(args) {
    document.getElementById("error").innerHTML = "";
    document.getElementById("errorPoint").style.visibility = "hidden";
}