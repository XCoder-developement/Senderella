"use strict";
function dragNdrop(event) {
    var fileName = URL.createObjectURL(event.target.files[0]);
    var preview =  document.getElementById("preview");
    var previewImg = document.createElement("img");
    previewImg.setAttribute("src", fileName);
    preview.innerHTML = "";
    preview.appendChild(previewImg);
}
function drag() {
    document.getElementById('uploadFile').parentNode.className = 'draging dragBox';
}
function drop() {
    document.getElementById('uploadFile').parentNode.className = 'dragBox';
}
function dragNdrop1(event) {
    var fileName = URL.createObjectURL(event.target.files[0]);
    var preview =  document.getElementById("preview1");
    var previewImg = document.createElement("img");
    previewImg.setAttribute("src", fileName);
    preview.innerHTML = "";
    preview.appendChild(previewImg);
}

// $('body').on('change', '.image_file', function(e) {
//     console.log($(this).val());
//     var fileName = URL.createObjectURL(e.target.files[0]);
//     var  preview = $(this).parent().closest('#preview');
//     consele.log(preview)
//     var previewImg = document.createElement("img");
//     previewImg.setAttribute("src", fileName);
//  //   preview.innerHTML = "";
//     preview.append(previewImg);
// });