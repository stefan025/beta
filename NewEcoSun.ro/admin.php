<?php

$dir = __DIR__ . "/gallery/uploads";

if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

/* UPLOAD */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["images"])) {

    foreach ($_FILES["images"]["tmp_name"] as $i => $tmp) {

        $ext = strtolower(pathinfo($_FILES["images"]["name"][$i], PATHINFO_EXTENSION));
        $name = uniqid() . "." . $ext;

        move_uploaded_file($tmp, $dir . "/" . $name);
    }

    echo json_encode(["success"=>true]);
    exit;
}

/* DELETE */
if (isset($_POST["delete"])) {

    $file = basename($_POST["delete"]);
    $path = $dir . "/" . $file;

    if (file_exists($path)) {
        unlink($path);
    }

    exit;
}

/* RENAME */
if (isset($_POST["rename"])) {

    $old = basename($_POST["old"]);
    $new = basename($_POST["new"]);

    rename($dir . "/" . $old, $dir . "/" . $new);

    exit;
}

$images = array_values(
    glob($dir . "/*.{jpg,jpeg,png,webp,gif,avif}", GLOB_BRACE) ?: []
);

?>

<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>MEDIA CONTROL PANEL</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Inter,system-ui,sans-serif;
    background:#070b12;
    color:#fff;
}

/* BACKGROUND */

body::before{
    content:"";
    position:fixed;
    inset:-20%;
    background:
    radial-gradient(circle at 20% 20%, rgba(99,102,241,.18), transparent 30%),
    radial-gradient(circle at 80% 40%, rgba(14,165,233,.16), transparent 30%),
    radial-gradient(circle at 50% 80%, rgba(168,85,247,.16), transparent 30%);
    filter:blur(80px);
    z-index:-1;
}

/* TOPBAR */

.topbar{
    position:sticky;
    top:0;
    z-index:50;

    display:flex;
    justify-content:space-between;
    align-items:center;

    padding:18px 24px;

    background:rgba(8,12,20,.72);
    backdrop-filter:blur(20px);

    border-bottom:1px solid rgba(255,255,255,.05);
}

.logo{
    font-size:18px;
    font-weight:700;
    letter-spacing:1px;
}

.stats{
    display:flex;
    gap:12px;
}

.badge{
    padding:10px 14px;
    border-radius:14px;

    background:rgba(255,255,255,.05);
    border:1px solid rgba(255,255,255,.06);

    font-size:13px;
    color:#d1d5db;
}

/* MAIN */

.container{
    max-width:1350px;
    margin:auto;
    padding:30px 20px;
}

/* UPLOAD SECTION */

.upload-wrapper{
    display:flex;
    justify-content:center;
    margin-bottom:35px;
}

.upload-box{
    width:100%;
    max-width:760px;

    padding:28px 26px;

    border-radius:24px;

    background:
    linear-gradient(180deg,
    rgba(255,255,255,.05),
    rgba(255,255,255,.02));

    border:1px solid rgba(255,255,255,.08);

    backdrop-filter:blur(30px);

    text-align:center;

    position:relative;
    overflow:hidden;

    transition:.25s;
}

.upload-box:hover{
    transform:translateY(-3px);
    border-color:rgba(99,102,241,.5);

    box-shadow:
    0 20px 60px rgba(99,102,241,.18),
    0 10px 30px rgba(0,0,0,.4);
}

.upload-glow{
    position:absolute;
    width:240px;
    height:240px;

    background:rgba(99,102,241,.18);

    border-radius:50%;

    filter:blur(70px);

    top:-120px;
    right:-80px;
}

/* ICON */

.icon{
    width:62px;
    height:62px;

    margin:auto;
    margin-bottom:14px;

    border-radius:18px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:
    linear-gradient(135deg,#6366f1,#06b6d4);

    font-size:28px;

    box-shadow:
    0 10px 25px rgba(99,102,241,.45);
}

.upload-title{
    font-size:24px;
    font-weight:700;
    margin-bottom:6px;
}

.upload-sub{
    color:#94a3b8;
    margin-bottom:18px;
    font-size:14px;
}

/* BUTTON */

.upload-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;

    padding:14px 26px;

    border-radius:16px;

    background:
    linear-gradient(135deg,#6366f1,#06b6d4);

    color:#fff;
    font-weight:600;
    font-size:15px;

    cursor:pointer;

    transition:.2s;

    box-shadow:
    0 10px 25px rgba(99,102,241,.35);
}

.upload-btn:hover{
    transform:translateY(-2px) scale(1.02);
}

/* PROGRESS */

.progress-area{
    margin-top:18px;
}

.progress-info{
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;

    font-size:13px;
    color:#cbd5e1;
}

.progress{
    width:100%;
    height:14px;

    background:rgba(255,255,255,.05);

    border-radius:999px;

    overflow:hidden;

    border:1px solid rgba(255,255,255,.05);
}

.progress-bar{
    height:100%;
    width:0%;

    border-radius:999px;

    background:
    linear-gradient(90deg,#6366f1,#06b6d4);

    transition:.15s;
}

/* GRID */

.grid{
    display:grid;

    grid-template-columns:
    repeat(auto-fill,minmax(220px,1fr));

    gap:18px;
}

/* CARD */

.card{
    position:relative;

    border-radius:22px;

    overflow:hidden;

    background:#0f172a;

    border:1px solid rgba(255,255,255,.05);

    transition:.25s;
}

.card:hover{
    transform:translateY(-5px);

    box-shadow:
    0 15px 35px rgba(0,0,0,.35),
    0 0 0 1px rgba(99,102,241,.25);
}

.card img{
    width:100%;
    height:220px;
    object-fit:cover;
}

/* ACTIONS */

.actions{
    position:absolute;
    left:12px;
    right:12px;
    bottom:12px;

    display:flex;
    justify-content:space-between;
    gap:8px;
}

.actions button{
    flex:1;

    border:none;

    padding:10px;

    border-radius:12px;

    cursor:pointer;

    font-size:12px;
    font-weight:600;

    color:#fff;

    backdrop-filter:blur(10px);

    transition:.2s;
}

.rename-btn{
    background:rgba(59,130,246,.75);
}

.delete-btn{
    background:rgba(239,68,68,.75);
}

.view-btn{
    background:rgba(16,185,129,.75);
}

.actions button:hover{
    transform:translateY(-2px);
    filter:brightness(1.1);
}

/* VIEWER */

.viewer{
    position:fixed;
    inset:0;

    background:rgba(0,0,0,.92);

    display:none;
    align-items:center;
    justify-content:center;

    z-index:999;
}

.viewer img{
    max-width:92%;
    max-height:92%;

    border-radius:18px;
}

@media(max-width:768px){

    .upload-box{
        padding:35px 20px;
        border-radius:22px;
    }

    .upload-title{
        font-size:23px;
    }

    .card img{
        height:260px;
    }

    .actions{
        flex-direction:column;
    }

}

</style>
</head>

<body>

<div class="topbar">

    <div class="logo">
        MEDIA CONTROL PANEL
    </div>

    <div class="stats">
        <div class="badge">
            <?= count($images) ?> FILES
        </div>
    </div>

</div>

<div class="container">

    <!-- UPLOAD -->

    <div class="upload-wrapper">

        <div class="upload-box">

            <div class="upload-glow"></div>

            <div class="icon">
                ⬆
            </div>

            <div class="upload-title">
                Upload Media
            </div>

            <div class="upload-sub">
                Drag & drop files or upload directly
            </div>

            <label class="upload-btn">
                Select Files
                <input type="file" multiple hidden onchange="uploadFiles(this)">
            </label>

            <div class="progress-area">

                <div class="progress-info">
                    <span id="progressText">Waiting upload...</span>
                    <span id="progressPercent">0%</span>
                </div>

                <div class="progress">
                    <div class="progress-bar" id="progressBar"></div>
                </div>

            </div>

        </div>

    </div>

    <!-- GALLERY -->

    <div class="grid">

        <?php foreach($images as $img): ?>

        <div class="card">

            <img
                src="gallery/uploads/<?= basename($img) ?>"
                onclick="viewImage(this.src)"
            >

            <div class="actions">

                <button
                    class="view-btn"
                    onclick="viewImage('gallery/uploads/<?= basename($img) ?>')">
                    VIEW
                </button>

                <button
                    class="rename-btn"
                    onclick="renameFile('<?= basename($img) ?>')">
                    RENAME
                </button>

                <button
                    class="delete-btn"
                    onclick="deleteFile('<?= basename($img) ?>')">
                    DELETE
                </button>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

</div>

<!-- VIEWER -->

<div class="viewer" id="viewer" onclick="closeViewer()">
    <img id="viewerImg">
</div>

<script>

/* UPLOAD */

function uploadFiles(input){

    let files = input.files;

    if(!files.length) return;

    let form = new FormData();

    for(let i=0;i<files.length;i++){
        form.append("images[]", files[i]);
    }

    let xhr = new XMLHttpRequest();

    xhr.open("POST","",true);

    xhr.upload.onprogress = function(e){

        if(e.lengthComputable){

            let percent = Math.round((e.loaded/e.total)*100);

            document.getElementById("progressBar").style.width =
            percent + "%";

            document.getElementById("progressPercent").innerText =
            percent + "%";

            document.getElementById("progressText").innerText =
            "Uploading files...";
        }
    };

    xhr.onload = function(){

        document.getElementById("progressText").innerText =
        "Upload complete";

        setTimeout(()=>{
            location.reload();
        },500);
    };

    xhr.send(form);
}

/* DELETE */

function deleteFile(file){

    if(!confirm("Delete this file?")) return;

    fetch("",{
        method:"POST",
        headers:{
            "Content-Type":
            "application/x-www-form-urlencoded"
        },
        body:"delete="+encodeURIComponent(file)
    })
    .then(()=>location.reload());
}

/* RENAME */

function renameFile(file){

    let n = prompt("New file name:", file);

    if(!n) return;

    fetch("",{
        method:"POST",
        headers:{
            "Content-Type":
            "application/x-www-form-urlencoded"
        },
        body:
        "rename="+encodeURIComponent(n)+
        "&old="+encodeURIComponent(file)
    })
    .then(()=>location.reload());
}

/* VIEWER */

function viewImage(src){

    document.getElementById("viewer").style.display =
    "flex";

    document.getElementById("viewerImg").src =
    src;
}

function closeViewer(){

    document.getElementById("viewer").style.display =
    "none";
}

</script>

</body>
</html>