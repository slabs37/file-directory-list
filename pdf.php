<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 0;    
}

if (isset($_GET['name'])) {
    $name = $_GET['name'];
}

if (isset($_GET['file'])) {
    $file = $_GET['file'];

    $command = "convert -units PixelsPerInch -density 150 -colorspace sRGB -flatten -quality 50 '{$file}[{$page}]' pdftmp/'$name'.jpg";

$output = shell_exec("$command 2>&1");
echo "<pre>$output</pre>";
} else {
    die();
}
?>
<html>
    <img id="imga" src="" style="overflow-x: hidden; display: block; margin-left: auto; margin-right: auto;"> </img> 
    <div>
	    <button id="go_previous">prev &#x25c0;</button>
	    <button id="go_next">next &#x25ba;</button>
        </div> 
        
        <?php //this calls a script which deletes the temporary files 10 minutes after creation ?>
        <iframe id="iframe" src="./pdftmp/delfile.php" hidden> </iframe>
        
<script>
var tmpName = getUrlVars()["name"];
document.getElementById("imga").src = "pdftmp/"+tmpName+".jpg";

    function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
    }
    
    
var file = getUrlVars()["file"];
var name = getUrlVars()["name"];
var page = getUrlVars()["page"];



    document.getElementById('go_previous').addEventListener('click', (e) => {
        if (page==0) return;
            page -= 1;
            window.location.href = "pdf.php?file="+file+"&name="+name+"&page="+page;
    });
 
    document.getElementById('go_next').addEventListener('click', (e) => {
            page++;
            window.location.href = "pdf.php?file="+file+"&name="+name+"&page="+page;
    });
    

 
// Calling function
// set the path to check
    var result = checkFileExist("./pdftmp/"+name+".jpg");
 
if (result == true) {
} else {
    window.location.reload(true);
}
    
    setTimeout(function(){
    document.getElementById('iframe').remove();
    },500);

    function checkFileExist(urlToFile) {
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', urlToFile, false);
    xhr.send();
     
    if (xhr.status == "404") {
        return false;
    } else {
        return true;
    }
}
</script>
</html>