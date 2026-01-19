// script.js
let new_color_add_to_current_Product;

const imageUpload = document.getElementById('imageUpload');
const uploadedImage = document.getElementById('uploadedImage');
const originImage = document.getElementById('originImage');
const cropButton = document.getElementById('cropButton');
const canvas = document.getElementById('canvas');
const moveLeft = document.getElementById('moveLeft');
const moveRight = document.getElementById('moveRight');
const moveUp = document.getElementById('moveUp');
const moveDown = document.getElementById('moveDown');
const increaseWidth = document.getElementById('increaseWidth');
const decreaseWidth = document.getElementById('decreaseWidth');
const increaseHeight = document.getElementById('increaseHeight');
const decreaseHeight = document.getElementById('decreaseHeight');

let offsetX, offsetY;

// Handle image upload
imageUpload.addEventListener('change', function (event) {
    console.log(colorPicker.value);
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            uploadedImage.src = e.target.result;
            uploadedImage.style.display = 'block';
            uploadedImage.style.left = '0px';
            uploadedImage.style.top = '0px';
            uploadedImage.style.width = '100%';
            uploadedImage.style.height = 'auto';
        };
        reader.readAsDataURL(file);
    }
});

// Enable dragging
uploadedImage.addEventListener('mousedown', function (e) {
    offsetX = e.offsetX;
    offsetY = e.offsetY;
    document.addEventListener('mousemove', moveImage);
});
document.addEventListener('mouseup', function () {
    document.removeEventListener('mousemove', moveImage);
});

function moveImage(e) {
    uploadedImage.style.left = (e.pageX - offsetX - originImage.offsetLeft) + 'px';
    uploadedImage.style.top = (e.pageY - offsetY - originImage.offsetTop) + 'px';
}

// Crop and apply mask
cropButton.addEventListener('click', function (event) {
    // Prevent default if within a form or default action might trigger
    event.preventDefault();

    canvas.width = originImage.width;
    canvas.height = originImage.height;
    const context = canvas.getContext('2d');

    // Draw the origin image to create a mask
    context.drawImage(originImage, 0, 0, originImage.width, originImage.height);
    context.globalCompositeOperation = 'source-in';

    // Calculate the position of the uploaded image relative to the origin
    const rect = uploadedImage.getBoundingClientRect();
    const originRect = originImage.getBoundingClientRect();
    const offsetX = rect.left - originRect.left;
    const offsetY = rect.top - originRect.top;

    // Draw the uploaded image using the mask
    context.drawImage(uploadedImage, offsetX, offsetY, rect.width, rect.height);

    // Generate the masked image and allow download
    canvas.toBlob(blob => {
        const randomId = Math.floor(1000 + Math.random() * 9000);
        const colorValue = colorPicker.value;
        const imageName = `cropped${randomId}.png`;
        const formData = new FormData();
        new_color_add_to_current_Product = colorValue+"<>"+imageName; // lấy ra new_color_add_to_current_Product
        formData.append('image', blob, imageName);
    
        fetch('uploadimg.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Image uploaded successfully');
                
                // Get the color value from the color picker
                const colorValue = colorPicker.value;
                
                // Create new radio button and label
                const newColorCheckDiv = document.createElement('div');
                newColorCheckDiv.classList.add('color-check');
        
                const newColorRadio = document.createElement('input');
                newColorRadio.classList.add('color-radio');
                newColorRadio.type = 'radio';
                newColorRadio.name = 'color';
                newColorRadio.id = colorValue;
                newColorRadio.value = colorValue;
                newColorRadio.checked = true;
                newColorRadio.addEventListener("change", function() {
                    document.getElementById("productImage").src = `img/${imageName}`;
                });

                const newColorLabel = document.createElement('label');
                newColorLabel.classList.add('color-label');
                newColorLabel.htmlFor = colorValue;
                newColorLabel.style.backgroundColor = colorValue;
        
                // Append radio button and label to the new color-check div
                newColorCheckDiv.appendChild(newColorRadio);
                newColorCheckDiv.appendChild(newColorLabel);
        
                // Find the addmoreColor element
                const addMoreColorElement = document.querySelector('.color-check.addmoreColor');
                
                // Insert new color-check div before addmoreColor
                document.querySelector('.colorContain').insertBefore(newColorCheckDiv, addMoreColorElement);
        
                // Trigger the change event on the new radio button to apply the image change
                newColorRadio.dispatchEvent(new Event('change'));
        
            } else {
                alert('Image upload failed: ' + data.message);
                console.error('Image upload failed:', data.message);
            }
        })
        .catch(error => {
            alert('Error occurred during upload: ' + error.message);
            console.error('Error:', error);
        });
    });
});

// Move controls
moveLeft.addEventListener('click', () => moveUploaded(-5, 0));
moveRight.addEventListener('click', () => moveUploaded(5, 0));
moveUp.addEventListener('click', () => moveUploaded(0, -5));
moveDown.addEventListener('click', () => moveUploaded(0, 5));

function moveUploaded(x, y) {
    uploadedImage.style.left = (parseInt(uploadedImage.style.left || 0) + x) + 'px';
    uploadedImage.style.top = (parseInt(uploadedImage.style.top || 0) + y) + 'px';
}

// Resize controls with unlimited scaling
increaseWidth.addEventListener('click', () => resizeUploaded(10, 0));
decreaseWidth.addEventListener('click', () => resizeUploaded(-10, 0));
increaseHeight.addEventListener('click', () => resizeUploaded(0, 10));
decreaseHeight.addEventListener('click', () => resizeUploaded(0, -10));

function resizeUploaded(widthChange, heightChange) {
    const newWidth = uploadedImage.offsetWidth + widthChange;
    const newHeight = uploadedImage.offsetHeight + heightChange;
    uploadedImage.style.width = newWidth + 'px';
    uploadedImage.style.height = newHeight + 'px';
}
// Flip controls
const flipXButton = document.getElementById('FlipX');
const flipYButton = document.getElementById('FlipY');

let flipXState = 1; // 1 means normal, -1 means flipped
let flipYState = 1; // 1 means normal, -1 means flipped

// Set the pivot point to the center
uploadedImage.style.transformOrigin = 'center';

// Add event listeners
flipXButton.addEventListener('click', () => flipImage('x'));
flipYButton.addEventListener('click', () => flipImage('y'));

function flipImage(axis) {
    if (axis === 'x') {
        flipXState *= -1; // Toggle flip state
    } else if (axis === 'y') {
        flipYState *= -1; // Toggle flip state
    }

    // Apply the transform with center pivot
    uploadedImage.style.transform = `scale(${flipXState}, ${flipYState})`;
}
