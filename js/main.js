// Global Variables

let     width = 500,
    height = 0,
    filter = 'none',
    streaming = false;


// DOM Elements

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const sticker = document.getElementById('stickercnvs');
const photos = document.getElementById('photos');
const photoButton = document.getElementById('photo-button');
const clearButton = document.getElementById('clear-button');
const photoFilter = document.getElementById('photo-filter');

//Get Media Stream

navigator.mediaDevices.getUserMedia({
    video: true,
    audio: false
}).then(function(stream){
    video.srcObject = stream;
    video.play();
}).catch(function(err){
    console.log(`Error: ${err}`);
});

// play when ready
video.addEventListener("canplay", function(e){
    if (!streaming){
        // set video/canvas height
        height = video.videoHeight / (video.videoWidth / width);

        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);

        streaming = true;
    }
}, false);

// FIlter event
photoFilter.addEventListener('change', function(e){
    filter = e.target.value;
    video.style.filter = filter;
    canvas.style.filter = filter;
    e.preventDefault();
});


// clear event
clearButton.addEventListener('click', function(e){
    //clear photos
    photos.innerHTML = '';
    // cahnge filter back to none
    filter = 'none';
    video.style.filter = filter;
    photoFilter.selectedIndex = 0;
    cnv.style.visibility = 'hidden';
});

// Take picture from canvas
photoButton.addEventListener('click', function(e){
    takepic();
    e.preventDefault();
}, false);

function takepic(){
    const context = canvas.getContext('2d');
    if (height && width)
    {
        context.globalAlpha = 1.0;
        context.drawImage(video, 0, 0, width, height);
        context.globalAlpha = 1.0;
        context.drawImage(sticker, 0, 0, width, height);
        canvas.style.filter = filter;
    }
}

function prepareImg() {
    var canvas = document.getElementById('canvas');
    const imgUrl = canvas.toDataURL('image/png');

    // create image element
    img = document.createElement('img');
    img.setAttribute('src', imgUrl);
    
    img.style.filter = filter;
    photos.appendChild(img);
    document.getElementById('inp_img').value = canvas.toDataURL();
    console.log('image prepared');
}
