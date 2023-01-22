<html>
    
<style>
    .text {
        text-align: center;
        font-size: 1.2em;
    }

    .loader {
      border: 2vh solid #f3f3f3; /* Light grey */
      border-top: 2vh solid #206ba4;
      border-radius: 50%;
      width: 25vh;
      height: 25vh;
      margin: auto;
      margin-top:10%;
     animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
</style>

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

    $command = "convert -units PixelsPerInch -density 150 -colorspace sRGB -flatten -quality 50 '{$file}[{$page}]' '$name'.jpg";

$output = shell_exec("$command 2>&1");


//check if requested page num is more than available pages
// by grabbing reported pages in ImageMagick error when we load page which doesn't exist
preg_match('/file: \d+/', $output, $int_var);
$in_var = preg_replace('/[^0-9]/', '', $int_var[0]);  
$pagae = $in_var-1;
    if ($in_var !== '') {
    
    $newurl = "pdf.php".'?file='.$file.'&name='.$name.'&page='.$pagae.'';
    
    echo '
    <div class="text">Converting page '.$in_var.' to image... </div><br>
    <div class="loader"></div>
    <script>window.location.href="'.$newurl.'"</script>';
    
    }
} else {
    die();
}

?>

    
    <img id="imga" src=<?php //show images with base64 data so that we can delete the temp files quickly
    $FilePath = $name.".jpg"; 
    $data = base64_encode(file_get_contents($FilePath));
    echo '"data:image/jpg;base64,' . $data . '"'; 
    unlink($FilePath);
    ?> style="overflow-x: hidden; display: block; margin-left: auto; margin-right: auto;"> </img> 
    
    <div id="loading" style="display:none">
        <div class="text">Converting page <span id="pagg"></span> to image... </div><br>
        <div class="loader"></div>
    </div>
    
    <div style="margin: 25px">
	    <button id="go_previous" style="position: fixed; bottom: 0px; left: 32%; transform: translate(-50%, 0%); background-color: #206ba4; border-radius: 50px; color: white;">&#x25c0; prev</button>
	    
	    <input style="-webkit-appearance: none; margin: 0; -moz-appearance:textfield; position: fixed; bottom: 0px; left: 50%; transform: translate(-50%, 0%); background-color: #206ba4; border-radius: 10px; color: white; width: 3em; text-align: center;" onkeydown="goPage(this)" value="<?php echo $_GET['page']+1 ?>" >

	    <button id="go_next" style="position: fixed; bottom: 0px; left: 68%; transform: translate(-50%, 0%); background-color: #206ba4; border-radius: 50px; color: white;">next &#x25ba;</button>
        </div> 
    
        
        
<script>
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
            bringLoad(page);
            window.location.href = "pdf.php?file="+file+"&name="+name+"&page="+page;
    });
 
    document.getElementById('go_next').addEventListener('click', (e) => {
            
            page++;
            bringLoad(page);
            window.location.href = "pdf.php?file="+file+"&name="+name+"&page="+page;
            
    });
    
    function goPage(ele) {
    if(event.key === 'Enter') {
        var inputPage = ele.value-1
	if (inputPage < 1) {inputPage = 0}
	    bringLoad(inputPage);
        window.location.href = "pdf.php?file="+file+"&name="+name+"&page="+inputPage;        
    }
}
    let width = screen.width/1.25;
    document.getElementById('imga').style.width = width+"px";
    
function bringLoad(pagg) {
document.getElementById('pagg').innerHTML=pagg+1;
document.getElementById('imga').style="display:none";
document.getElementById('loading').style="";
}
</script>
</html>
