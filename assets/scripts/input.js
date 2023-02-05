document.querySelectorAll('.input-image').forEach(input => {
    const uploadImageInput = input.querySelector('.upload-image-input');
    const imagePreview = input.querySelector('.input-image-preview');
    const inputButton = input.querySelector('.btn');

    if(!uploadImageInput || !imagePreview || !inputButton) return;

    uploadImageInput.addEventListener('change', (event) => {
        if (!event.target.files[0]) return;

        const FR = new FileReader();
        FR.readAsDataURL(event.target.files[0]);
        FR.addEventListener("load", () => {
            imagePreview.src = FR.result;
            imagePreview.classList.remove('hidden')
            inputButton.classList.add('hidden')
        })
    })
});