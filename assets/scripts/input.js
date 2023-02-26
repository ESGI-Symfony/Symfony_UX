document.querySelectorAll('.input-image').forEach(input => {
    const uploadImageInput = input.querySelector('.upload-image-input');
    const imagePreview = input.querySelector('.input-image-preview');
    const inputButton = input.querySelector('.btn');

    if(!uploadImageInput || !imagePreview || !inputButton) return;

    uploadImageInput.addEventListener('change', (event) => {
        if (!event.target.files[0]) return;

        const fileReader = new FileReader();
        fileReader.readAsDataURL(event.target.files[0]);
        fileReader.addEventListener("load", () => {
            imagePreview.src = fileReader.result;
            imagePreview.classList.remove('hidden')
            inputButton.classList.add('hidden')
        })
    })
});